<?php

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use App\Models\Yfrekappresensi;
//ok1
function build_payroll($month, $year)
{
    // $jamKerjaKosong = Jamkerjaid::count();
    $adaPresensi = Yfrekappresensi::whereMonth('date',$month )
    ->whereYear('date',$year)
    ->count();

    // if ($jamKerjaKosong == null || $adaPresensi == null) {
    if ($adaPresensi == null) {
       
        return 0;
        clear_locks();
        $dispatch('error', message: 'Data Presensi Masih Kosong');
    }

    // AMBIL DATA TERAKHIR DARI REKAP PRESENSI PADA BULAN YBS
    $last_data_date = Yfrekappresensi::query()
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date', 'desc')
        ->first();

    //     delete jamkerjaid yg akan di build
    Jamkerjaid::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->delete();

    $jumlah_jam_terlambat = null;
    $jumlah_menit_lembur = null;
    $dt_name = null;
    $dt_date = null;
    $dt_karyawan_id = null;
    $late = null;
    $late1 = null;
    $late2 = null;
    $late3 = null;
    $late4 = null;
    $late5 = null;

    $filterArray = Yfrekappresensi::whereMonth('date', $month)
        ->whereYear('date', $year)
        // ->where('status')
        ->pluck('user_id')
        ->unique();

    if ($filterArray == null) {
        return 0;
    }

    

    $filteredData = Jamkerjaid::with(['karyawan' => ['id_karyawan', 'jabatan', 'placement']])
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->get();

    // disini mulai prosesnya

    //     foreach ($filteredData as $data) {
    foreach ($filterArray as $data) {
        $dataId = Yfrekappresensi::with('karyawan:id,jabatan')

            ->where('user_id', $data)
            ->whereBetween('date', [Carbon::parse($year . '-' . $month . '-01'), Carbon::parse($year . '-' . $month . '-01')->endOfMonth()])
            ->orderBy('date', 'desc')
            ->get();

        // ambil data per user id
        $n_noscan = 0;
        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $langsungLembur = 0;
        $tambahan_shift_malam = 0;
        $total_keterlambatan = 0;
        $total_tambahan_shift_malam = 0;
        //loop ini utk 1 user selama 22 hari
        foreach ($dataId as $d) {
            if ($d->no_scan === null) {
                $jam_lembur = 0;
                $tambahan_shift_malam = 0;
                $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);
                $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);

                if (is_sunday($d->date)) {
                    $jam_lembur = (hitungLembur($d->overtime_in, $d->overtime_out) / 60) * 2;
                } else {
                    $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                }

                if ($d->shift == 'Malam') {
                    if (is_saturday($d->date)) {
                        if ($jam_kerja >= 6) {
                            $tambahan_shift_malam = 1;
                        }
                    } elseif (is_sunday($d->date)) {
                        if ($jam_kerja >= 16) {
                            // $jam_lembur = $jam_lembur + 2;
                            $tambahan_shift_malam = 2;
                        }
                    } else {
                        if ($jam_kerja >= 8) {
                            // $jam_lembur = $jam_lembur + 1;
                            $tambahan_shift_malam = 1;
                        }
                    }
                }
                if ($jam_lembur >= 9 && is_sunday($d->date) == false) {
                    $jam_lembur = 0;
                }
                if ($d->karyawan->placement == 'YIG' || $d->karyawan->placement == 'YSM' || $d->karyawan->jabatan == 'Satpam') {
                    if (is_friday($d->date)) {
                        $jam_kerja = 7.5;
                    } elseif (is_saturday($d->date)) {
                        $jam_kerja = 6;
                    } else {
                        $jam_kerja = 8;
                    }
                }

                if ($d->karyawan->jabatan == 'Satpam' && is_sunday($d->date)) {
                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                }

                if ($d->karyawan->jabatan == 'Satpam' && is_saturday($d->date)) {
                    $jam_lembur = 0;
                }

                $total_hari_kerja++;
                $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                $total_jam_lembur = $total_jam_lembur + $jam_lembur;
                $total_keterlambatan = $total_keterlambatan + $terlambat;
                $total_tambahan_shift_malam = $total_tambahan_shift_malam + $tambahan_shift_malam;
            }
            if ($d->no_scan_history != null) {
                $n_noscan = $n_noscan + 1;
            }
        }
        if ($n_noscan == 0) {
            $n_noscan = null;
        }

        $dataArr[] = [
            'user_id' => $data,
            'total_hari_kerja' => $total_hari_kerja,
            'jumlah_jam_kerja' => $total_jam_kerja,
            'jumlah_menit_lembur' => $total_jam_lembur,
            'jumlah_jam_terlambat' => $total_keterlambatan,
            'tambahan_jam_shift_malam' => $total_tambahan_shift_malam,
            'total_noscan' => $n_noscan,
            'karyawan_id' => $d->karyawan->id,
            'date' => buatTanggal($d->date),
            'last_data_date' => $last_data_date->date,
        ];
        
    }
    $chunks = array_chunk($dataArr, 100);
    foreach ($chunks as $chunk) {
        Jamkerjaid::insert($chunk);
    }
// ok2
    $datas = Jamkerjaid::with('karyawan', 'yfrekappresensi')
    ->whereBetween('date', [Carbon::parse($year . '-' . $month . '-01'), Carbon::parse($year . '-' . $month . '-01')->endOfMonth()])
    ->get();

if ($datas->isEmpty()) {
    return 0;
}

$subtotal = 0;
$denda_noscan = 0;

Payroll::whereMonth('date', $month)
    ->whereYear('date', $year)
    ->delete();

foreach ($datas as $data) {
  //   $payroll = new Payroll();

  if ($data->total_noscan > 3 && $data->karyawan->metode_penggajian == 'Perjam') {
        $denda_noscan = ($data->total_noscan - 3) * ($data->karyawan->gaji_pokok / 198);
    } else {
        $denda_noscan = 0;
    }

  //   hitung BPJS

  if ($data->karyawan->potongan_JP == 1) {
        if ($data->karyawan->gaji_bpjs <= 9559600) {
            $jp = $data->karyawan->gaji_bpjs * 0.01;
        } else {
            $jp = 9559600 * 0.01;
        }
    } else {
        $jp = 0;
    }

    if ($data->karyawan->potongan_JHT == 1) {
        $jht = $data->karyawan->gaji_bpjs * 0.02;
    } else {
        $jht = 0;
    }

    if ($data->karyawan->potongan_kesehatan == 1) {
        $kesehatan = $data->karyawan->gaji_bpjs * 0.01;
    } else {
        $kesehatan = 0;
    }

    $pajak = 0;
    if ($data->karyawan->potongan_JKK == 1) {
        $jkk = 1;
    } else {
        $jkk = 0;
    }
    if ($data->karyawan->potongan_JKM == 1) {
        $jkm = 1;
    } else {
        $jkm = 0;
    }

    if ($data->total_noscan == null) {
        $denda_lupa_absen = 0;
    } else {
        if ($data->total_noscan <= 3) {
            $denda_lupa_absen = 0;
        } else {
            $denda_lupa_absen = ($data->total_noscan - 3) * ($data->karyawan->gaji_pokok / 198);
        }
    }

    $total_bonus_dari_karyawan = 0;
    $total_potongan_dari_karyawan = 0;

    $total_bonus_dari_karyawan = $data->karyawan->bonus + $data->karyawan->tunjangan_jabatan + $data->karyawan->tunjangan_bahasa + $data->karyawan->tunjangan_skill + $data->karyawan->tunjangan_lembur_sabtu + $data->karyawan->tunjangan_lama_kerja;
    $total_potongan_dari_karyawan = $data->karyawan->iuran_air + $data->karyawan->iuran_locker;
    $pajak = 0;
    $subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + $data->jumlah_menit_lembur * $data->karyawan->gaji_overtime;
    $tambahan_shift_malam = $data->tambahan_jam_shift_malam * $data->karyawan->gaji_overtime;
    
// ok3
    $payrollArr[] = [
    'jp' => $jp,
    'jht' => $jht,
    'kesehatan' => $kesehatan,
    'jkk' => $jkk,
    'jkm' => $jkm,
    'denda_lupa_absen' => $denda_lupa_absen,
    'jamkerjaid_id' => $data->id,
    'nama' => $data->karyawan->nama,
    'id_karyawan' => $data->karyawan->id_karyawan,
    'jabatan' => $data->karyawan->jabatan,
    'company' => $data->karyawan->company,
    'placement' => $data->karyawan->placement,
    'status_karyawan' => $data->karyawan->status_karyawan,
    'metode_penggajian' => $data->karyawan->metode_penggajian,
    'nomor_rekening' => $data->karyawan->nomor_rekening,
    'nama_bank' => $data->karyawan->nama_bank,
    'gaji_pokok' => $data->karyawan->gaji_pokok,
    'gaji_lembur' => $data->karyawan->gaji_overtime,
    'gaji_bpjs' => $data->karyawan->gaji_bpjs,
    'jkk' => $data->karyawan->jkk,
    'jkm' => $data->karyawan->jkm,
    'hari_kerja' => $data->total_hari_kerja,
    'jam_kerja' => $data->jumlah_jam_kerja,
    'jam_lembur' => $data->jumlah_menit_lembur,
    'jumlah_jam_terlambat' => $data->jumlah_jam_terlambat,
    'total_noscan' => $data->total_noscan,
    'thr' => $data->karyawan->bonus,
    'tunjangan_jabatan' => $data->karyawan->tunjangan_jabatan,
    'tunjangan_bahasa' => $data->karyawan->tunjangan_bahasa,
    'tunjangan_skill' => $data->karyawan->tunjangan_skill,
    'tunjangan_lama_kerja' => $data->karyawan->tunjangan_lama_kerja,
    'tunjangan_lembur_sabtu' => $data->karyawan->tunjangan_lembur_sabtu,
    'iuran_air' => $data->karyawan->iuran_air,
    'iuran_locker' => $data->karyawan->iuran_locker,
    'tambahan_jam_shift_malam' => $data->tambahan_jam_shift_malam,
    'tambahan_shift_malam' => $tambahan_shift_malam,
    'subtotal' => $subtotal,
    'date' => buatTanggal($data->date),
    'total' => $subtotal + $total_bonus_dari_karyawan + $tambahan_shift_malam - $total_potongan_dari_karyawan - $pajak - $jp - $jht - $kesehatan - $denda_lupa_absen
  ];
}
$chunks = array_chunk($payrollArr, 100);
foreach ($chunks as $chunk) {
    Payroll::insert($chunk);
}

// Bonus dan Potongan 

$bonus = 0;
$potongaan = 0;
$all_bonus = 0;
$all_potongan = 0;
$tambahan = Tambahan::whereMonth('tanggal', $month)
    ->whereYear('tanggal', $year)
    ->get();

foreach ($tambahan as $d) {
    $all_bonus = $d->uang_makan + $d->bonus_lain;
    $all_potongan = $d->baju_esd + $d->gelas + $d->sandal + $d->seragam + $d->sport_bra + $d->hijab_instan + $d->id_card_hilang + $d->masker_hijau + $d->potongan_lain;
    $id_payroll = Payroll::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->where('id_karyawan', $d->user_id)
        ->first();
    if ($id_payroll != null) {
        $payroll = Payroll::find($id_payroll->id);
        $payroll->bonus1x = $payroll->bonus1x + $all_bonus;
        $payroll->potongan1x = $payroll->potongan1x + $all_potongan;
        $payroll->total = $payroll->total + $all_bonus - $all_potongan;
        $payroll->save();
    }
}

$current_date = Jamkerjaid::orderBy('date', 'desc')->first();

return 1;

   
}


