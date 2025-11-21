<?php

use Carbon\Carbon;
use App\Models\Ter;
use App\Models\Lock;
use App\Models\User;
use App\Models\Company;
use App\Models\Jabatan;
use App\Models\Payroll;
use App\Models\Timeoff;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Placement;
use App\Models\Requester;
use App\Models\Department;
use App\Models\Harikhusus;
use Illuminate\Support\Str;
use App\Models\Applicantdata;
use App\Models\Applicantfile;
use App\Models\Bonuspotongan;
use App\Models\Dashboarddata;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;
use App\Models\Timeoffrequester;
use App\Models\Personnelrequestform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


function quickRebuild($month, $year)
{
    $libur = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)->whereYear('tanggal_mulai_hari_libur', $year)->orderBy('tanggal_mulai_hari_libur', 'asc')->get('tanggal_mulai_hari_libur');
    $total_n_hari_kerja = getTotalWorkingDays($year, $month);
    $startOfMonth = Carbon::parse($year . '-' . $month . '-01');
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    $cx = 0;
    // isi ini dengan false jika mau langsung
    $pass = true;

    Payroll::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->delete();
    delete_failed_jobs();


    $jumlah_libur_nasional = jumlah_libur_nasional($month, $year);


    $datas = Yfrekappresensi::with('karyawan')
        ->whereBetween('date', [
            Carbon::parse("$year-$month-01"),
            Carbon::parse("$year-$month-01")->endOfMonth(),
        ])
        ->whereHas('karyawan', function ($query) {
            $query->where('status_karyawan', '!=', 'Blacklist');
        })
        ->pluck('user_id')
        ->unique()
        ->toArray();

    if (empty($datas)) {
        return 0;
    }

    foreach ($datas as $user_id) {

        $data = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $no_scan_history = 0;
        $late_history = 0;
        $shift_malam = 0;
        $late = 0;
        $tanggal = null;
        $hari_kerja_libur = 0;
        $jam_lembur_libur = 0;
        $total_jam_kerja_libur = 0;


        foreach ($data as $d) {
            $total_hari_kerja += $d->total_hari_kerja;
            $total_jam_kerja += $d->total_jam_kerja;
            $total_jam_lembur += $d->total_jam_lembur;
            $total_jam_kerja_libur += $d->total_jam_kerja_libur;
            $jam_lembur_libur += $d->$jam_lembur_libur;
            if ($d->no_scan_history) $no_scan_history++;
            if ($d->late_history) $late_history++;
            // $jam_lembur_libur = 0;
            // $total_jam_kerja_libur = 0;



            $shift_malam +=  $d->shift_malam;
            $late += $d->late;
            $tanggal = $d->date;
        }

        $karyawan = Karyawan::where('id_karyawan', $user_id)->first();
        //   hitung BPJS

        if ($karyawan->potongan_JP == 1) {
            if ($karyawan->gaji_bpjs <= 10042300) {
                $jp = $karyawan->gaji_bpjs * 0.01;
            } else {
                $jp = 10042300 * 0.01;
            }
        } else {
            $jp = 0;
        }

        if ($karyawan->potongan_JHT == 1) {
            $jht = $karyawan->gaji_bpjs * 0.02;
        } else {
            $jht = 0;
        }

        if ($karyawan->potongan_kesehatan == 1) {
            $data_gaji_bpjs = 0;
            if ($karyawan->gaji_bpjs >= 12000000) $data_gaji_bpjs = 12000000;
            else $data_gaji_bpjs = $karyawan->gaji_bpjs;

            $kesehatan = $data_gaji_bpjs * 0.01;
        } else {
            $kesehatan = 0;
        }

        if ($karyawan->tanggungan >= 1) {
            $tanggungan = $karyawan->tanggungan * $karyawan->gaji_bpjs * 0.01;
        } else {
            $tanggungan = 0;
        }

        if ($karyawan->potongan_JKK == 1) {
            $jkk = 1;
        } else {
            $jkk = 0;
        }

        if ($karyawan->potongan_JKM == 1) {
            $jkm = 1;
        } else {
            $jkm = 0;
        }

        // end of bpjs
        // $no_scan_history += $d->no_scan_history;
        // $late_history += $d->late_history;

        // denda no scan
        if ($no_scan_history > 3 && trim($karyawan->metode_penggajian) == 'Perjam') {
            $denda_noscan = ($no_scan_history - 3) * ($karyawan->gaji_pokok / 198);
        } else {
            $denda_noscan = 0;
        }

        // denda lupa absen
        if ($no_scan_history == null) {
            $denda_lupa_absen = 0;
        } else {
            if ($no_scan_history <= 3) {
                $denda_lupa_absen = 0;
            } else {
                $denda_lupa_absen = ($no_scan_history - 3) * ($karyawan->gaji_pokok / 198);
            }
        }

        $total_bonus_dari_karyawan = 0;
        $total_potongan_dari_karyawan = 0;
        $gaji_libur = 0;


        if ($karyawan->metode_penggajian == 'Perjam') {
            $gaji_libur = ($total_jam_kerja_libur * ($karyawan->gaji_pokok / 198));
        } else {
            $gaji_libur = ($hari_kerja_libur * ($karyawan->gaji_pokok / $total_n_hari_kerja) * 2);
        }
        // if ($karyawan->metode_penggajian == 'Perjam') {
        //     $gaji_libur = ($total_jam_kerja_libur * ($karyawan->gaji_pokok / 198) + ($jam_lembur_libur * $karyawan->gaji_overtime * 2));
        // } else {
        //     $gaji_libur = ($hari_kerja_libur * ($karyawan->gaji_pokok / $total_n_hari_kerja) * 2) + ($jam_lembur_libur * $karyawan->gaji_overtime * 2);
        // }

        // if ($total_jam_kerja_libur > 0) dd($karyawan->id_karyawan);

        $total_bonus_dari_karyawan = $karyawan->bonus + $karyawan->tunjangan_jabatan + $karyawan->tunjangan_bahasa + $karyawan->tunjangan_skill + $karyawan->tunjangan_lembur_sabtu + $karyawan->tunjangan_lama_kerja;
        $total_potongan_dari_karyawan = $karyawan->iuran_air + $karyawan->iuran_locker;
        $pajak = 0;
        $manfaat_libur = 0;
        $beginning_date = buat_tanggal($month, $year);

        if ($karyawan->metode_penggajian == 'Perbulan' && ($karyawan->tanggal_bergabung >= $beginning_date  || $karyawan->status_karyawan == 'Resigned')) {
            $manfaat_libur = manfaat_libur($month, $year, $libur, $user_id, $karyawan->tanggal_bergabung);
        } else {
            $manfaat_libur = $libur->count();
            $cx++;
        }

        // $gaji_karyawan_bulanan = ($karyawan->gaji_pokok / $total_n_hari_kerja) * ($total_hari_kerja + $manfaat_libur + $hari_kerja_libur);

        $total_gaji_libur = 0;
        $total_gaji_lembur_karyawan = 0;
        $gaji_karyawan_bulanan = 0;

        $total_gaji_libur = ($total_jam_kerja_libur * ($karyawan->gaji_pokok / 198)) + ($jam_lembur_libur * $karyawan->gaji_overtime);
        $total_gaji_lembur_karyawan = $total_jam_lembur * $karyawan->gaji_overtime;

        if (trim($karyawan->metode_penggajian) == 'Perjam') {
            $subtotal = $total_jam_kerja * ($karyawan->gaji_pokok / 198) + $total_gaji_lembur_karyawan + $total_gaji_libur;
        } else {
            $gaji_karyawan_bulanan = ($karyawan->gaji_pokok / $total_n_hari_kerja) * ($total_hari_kerja + $manfaat_libur) + $total_gaji_libur + $total_gaji_lembur_karyawan;
            $subtotal = $gaji_karyawan_bulanan;
        }

        // 

        $tambahan_shift_malam = $shift_malam * $karyawan->gaji_overtime;
        if ($karyawan->jabatan_id == 17) {
            $tambahan_shift_malam = $shift_malam * $karyawan->gaji_shift_malam_satpam;
        }

        $libur_nasional = 0;

        $total_gaji_lembur = $total_jam_lembur * $karyawan->gaji_overtime;
        $pph21 = hitung_pph21(
            $karyawan->gaji_bpjs,
            $karyawan->ptkp,
            $karyawan->potongan_JHT,
            $karyawan->potongan_JP,
            $karyawan->potongan_JKK,
            $karyawan->potongan_JKM,
            $karyawan->potongan_kesehatan,
            $total_gaji_lembur,
            $gaji_libur,
            0,
            $tambahan_shift_malam,
            $karyawan->company_id

        );
        //==================
        if ($karyawan->gaji_bpjs >= 12000000) {
            $gaji_bpjs_max = 12000000;
        } else {
            $gaji_bpjs_max = $karyawan->gaji_bpjs;
        }

        if (
            $karyawan->gaji_bpjs >= 10042300
        ) {
            $gaji_jp_max = 10042300;
        } else {
            $gaji_jp_max = $karyawan->gaji_bpjs;
        }
        if (
            $karyawan->potongan_kesehatan != 0
        ) {
            $kesehatan_company = ($gaji_bpjs_max * 4) / 100;
        } else {
            $kesehatan_company = 0;
        }

        if ($karyawan->potongan_JKK) {
            $jkk_company = ($karyawan->gaji_bpjs * 0.24) / 100;
            // rubah JKK company STI = 101
            if ($karyawan->company_id == 101) {
                $jkk_company = ($karyawan->gaji_bpjs * 0.89) / 100;
            }
        } else {
            $jkk_company = 0;
        }

        if ($karyawan->potongan_JKM) {
            $jkm_company = ($karyawan->gaji_bpjs * 0.3) / 100;
        } else {
            $jkm_company = 0;
        }

        // ====================
        $total_bpjs = $karyawan->gaji_bpjs +
            // $karyawan->ptkp +

            $jkk_company +
            $jkm_company +
            $kesehatan_company +
            $total_gaji_lembur +
            $gaji_libur +

            $tambahan_shift_malam;

        if ($karyawan->metode_penggajian == '') {
            dd('metode penggajian belum diisi', $karyawan->id_karyawan);
        }
        if ($karyawan->metode_penggajian == 'Perjam') {
            $hari_kerja_libur = 0;
        }

        Payroll::create([
            'jp' => $jp,
            'jht' => $jht,
            'kesehatan' => $kesehatan,
            'tanggungan' => $tanggungan,
            'jkk' => $jkk,
            'jkm' => $jkm,
            'denda_lupa_absen' => $denda_lupa_absen,
            'gaji_libur' => $gaji_libur,

            // 'jamkerjaid_id' => $data->id,
            'nama' => $karyawan->nama,
            'id_karyawan' => $karyawan->id_karyawan,

            'jabatan_id' => $karyawan->jabatan_id,
            'company_id' => $karyawan->company_id,
            'placement_id' => $karyawan->placement_id,
            'department_id' => $karyawan->department_id,

            'status_karyawan' => $karyawan->status_karyawan,
            'metode_penggajian' => $karyawan->metode_penggajian,
            'nomor_rekening' => $karyawan->nomor_rekening,
            'nama_bank' => $karyawan->nama_bank,
            'gaji_pokok' => $karyawan->gaji_pokok,
            'gaji_lembur' => $karyawan->gaji_overtime,
            'gaji_bpjs' => $karyawan->gaji_bpjs,
            'ptkp' => $karyawan->ptkp,
            // oll
            'libur_nasional' => $libur_nasional,

            // 'jkk' => $karyawan->jkk,
            // 'jkm' => $karyawan->jkm,

            'hari_kerja_libur' => $hari_kerja_libur,
            'jam_lembur_libur' => $jam_lembur_libur,


            'hari_kerja' => $total_hari_kerja,
            'jam_kerja' => $total_jam_kerja,
            'jam_lembur' => $total_jam_lembur,
            'jumlah_jam_terlambat' => $late,
            'total_noscan' => $no_scan_history,
            'thr' => $karyawan->bonus,
            'tunjangan_jabatan' => $karyawan->tunjangan_jabatan,
            'tunjangan_bahasa' => $karyawan->tunjangan_bahasa,
            'tunjangan_skill' => $karyawan->tunjangan_skill,
            'tunjangan_lama_kerja' => $karyawan->tunjangan_lama_kerja,
            'tunjangan_lembur_sabtu' => $karyawan->tunjangan_lembur_sabtu,
            'iuran_air' => $karyawan->iuran_air,
            'iuran_locker' => $karyawan->iuran_locker,
            'tambahan_jam_shift_malam' => $shift_malam,
            'tambahan_shift_malam' => $tambahan_shift_malam,
            'subtotal' => $subtotal,
            'date' => buatTanggal($tanggal),
            'pph21' => $pph21,
            // 'total' => $subtotal + $gaji_libur + $total_bonus_dari_karyawan + $libur_nasional + $tambahan_shift_malam - $total_potongan_dari_karyawan - $pajak - $jp - $jht - $kesehatan - $tanggungan - $denda_lupa_absen - $pph21,
            'total' => $subtotal + $total_bonus_dari_karyawan + $libur_nasional + $tambahan_shift_malam - $total_potongan_dari_karyawan - $pajak - $jp - $jht - $kesehatan - $tanggungan - $denda_lupa_absen - $pph21,
            'total_bpjs' => $total_bpjs,
            // 'created_at' => now()->toDateTimeString(),
            // 'updated_at' => now()->toDateTimeString()
        ]);
    }


    // Bonus dan Potongan

    $bonus = 0;
    $potongaan = 0;
    $all_bonus = 0;
    $all_potongan = 0;
    $bonuspotongan = Bonuspotongan::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->get();

    foreach ($bonuspotongan as $d) {
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

    // hitung ulang PPH21 utk karyawan bulanan yang ada bonus tambahan

    $karyawanWithBonus = Payroll::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->where('metode_penggajian', 'Perbulan')
        ->where('bonus1x', '>', 0)->get();

    foreach ($karyawanWithBonus as $kb) {

        $total_bpjs_company = 0;
        $total_bpjs_lama = $kb->total_bpjs;
        $total_bpjs_company = $total_bpjs_lama + $kb->bonus1x;

        $pph21_lama = $kb->pph21;
        $pph21simple = hitung_pph21_simple($total_bpjs_company, $kb->ptkp, $kb->gaji_bpjs);
        $total_lama = $kb->total;
        $kb->pph21 = $pph21simple;
        $kb->total = $total_lama + $pph21_lama - $pph21simple;
        $kb->total_bpjs = $total_bpjs_company;
        $kb->save();
        // if ($kb->id_karyawan == 101) {
        //     dd($pph21_lama - $pph21simple);
        // }
    }


    // ok 4
    // perhitungan untuk karyawan yg resign sebelum 3 bulan

    $data = Karyawan::where('tanggal_resigned', '!=', null)
        ->whereMonth('tanggal_resigned', $month)
        ->whereYear('tanggal_resigned', $year)
        ->get();

    foreach ($data as $d) {
        $lama_bekerja = lama_bekerja($d->tanggal_bergabung, $d->tanggal_resigned);
        if ($lama_bekerja <= 90) {
            $data_payrolls = Payroll::where('id_karyawan', $d->id_karyawan)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->first();

            // try {
            //     $data_payroll = Payroll::find($data_payrolls->id);
            // } catch (\Exception $e) {
            //     dd($e->getMessage(), $d->id_karyawan, $lama_bekerja);
            //     return $e->getMessage();
            // }

            if ($data_payrolls != null) {
                $data_payroll = Payroll::find($data_payrolls->id);
            } else {
                $data_payroll = null;
            }

            if ($data_payroll != null) {
                if (trim($data_payroll->metode_penggajian) == 'Perbulan') {
                    $data_payroll->denda_resigned = 3 * ($data_payroll->gaji_pokok / $total_n_hari_kerja);
                } else {
                    $data_payroll->denda_resigned = 24 * ($data_payroll->gaji_pokok / 198);
                }
                $data_payroll->total = $data_payroll->total - $data_payroll->denda_resigned;
                if ($data_payroll->total < 0) {
                    $data_payroll->total = 0;
                }
                $data_payroll->save();
            }
        }
    }

    // ok 5
    //  Zheng Guixin 1
    // Eddy Chan 2
    // Yang Xiwen 3
    // Rudy Chan 4
    // Yin kai 5
    // Li meilian 25
    // Wanto 6435
    // Chan Kai Wan 6


    $idArrTKA = [1, 3, 5, 25, 6];
    $idArrTionghoa = [4, 2, 6435]; // TKA hanya 3 orang
    $idKhusus = [4, 2, 6435, 1, 3, 5, 6, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 800, 900, 5576, 5693, 6566, 7511, 6576, 6577, 6578, 6579, 8127]; //TKA hanya 3 no didepan




    // ok 6
    // Libur nasional dan resigned sebelum 3 bulan bekerja

    // $jumlah_libur_nasional = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
    //     ->whereYear('tanggal_mulai_hari_libur', $year)
    //     ->sum('jumlah_hari_libur');

    // $current_date = Jamkerjaid::orderBy('date', 'desc')->first();

    $lock = Lock::find(1);
    $lock->rebuild_done = 1;
    $lock->save();

    return;
}


function khusus_checkFirstInLate($check_in, $shift, $tgl, $placement_id)

{
    // rubah angka ini utk bulan puasa
    $test = $placement_id;
    if (is_puasa($tgl)) {
        $jam_mulai_pagi = '07:33';
        $strtime_pagi = '07:33:00';
        $jam_mulai_sore = '19:33';
        $strtime_sore = '19:33:00';
        $jam_mulai_sore_sabtu = '16:03';
        $strtime_sore_sabtu = '16:03:00';
    } else {
        $jam_mulai_pagi = '08:03';
        $strtime_pagi = '08:03:00';
        $jam_mulai_sore = '20:03';
        $strtime_sore = '20:03:00';
        $jam_mulai_sore_sabtu = '17:03';
        $strtime_sore_sabtu = '17:03:00';
    }

    $perJam = 60;
    $late = null;

    if ($check_in != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($check_in)->betweenIncluded('05:30', $jam_mulai_pagi)) {
                $late = null;
            } else {
                $t1 = strtotime($strtime_pagi);
                $t2 = strtotime($check_in);
                $diff = gmdate('H:i:s', $t2 - $t1);
                $late = ceil(hoursToMinutes($diff) / $perJam);
                if ($late <= 5 && $late > 3.5) {
                    if (khusus_is_friday($tgl)) {
                        $late = 3.5;
                    } else {
                        $late = 4;
                    }
                } elseif ($late > 5) {
                    if (khusus_is_friday($tgl)) {
                        $late = $late - 1.5;
                    } else {
                        $late = $late - 1;
                    }
                }
            }
        } else {
            if (khusus_is_saturday($tgl)) {
                if (Carbon::parse($check_in)->betweenIncluded('14:00', $jam_mulai_sore_sabtu)) {
                    $late = null;
                } else {
                    $t1 = strtotime($strtime_sore_sabtu);
                    $t2 = strtotime($check_in);

                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                }
            } else {
                if (Carbon::parse($check_in)->betweenIncluded('16:00', $jam_mulai_sore)) {
                    $late = null;
                } else {
                    $t1 = strtotime($strtime_sore);
                    $t2 = strtotime($check_in);

                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                }
            }
        }
    }
    return $late;
}

function khusus_checkSecondOutLate($second_out, $shift, $tgl, $jabatan, $placement_id)
{
    if (is_puasa($tgl)) {
        $jam_secondOut_pagi = '16:29';
        $strtime_secondOut_pagi = '16:30:00';
        $jam_secondOut_pagi_sabtu = '14:29';
        $strtime_secondOut_pagi_sabtu = '15:00:00';

        $jam_secondOut_sore = '04:59';
        $strtime_secondOut_sore = '05:00:00';
        $jam_secondOut_sore_sabtu = '22:59';
        $strtime_secondOut_sore_sabtu = '23:00:00';
    } else {
        $jam_secondOut_pagi = '16:59';
        $strtime_secondOut_pagi = '17:00:00';
        $jam_secondOut_pagi_sabtu = '14:59';
        $strtime_secondOut_pagi_sabtu = '15:00:00';

        $jam_secondOut_sore = '04:59';
        $strtime_secondOut_sore = '05:00:00';
        $jam_secondOut_sore_sabtu = '23:59';
        $strtime_secondOut_sore_sabtu = '23:59:00';
    }
    $perJam = 60;
    $late = null;

    if ($second_out != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (khusus_is_saturday($tgl)) {
                if (Carbon::parse($second_out)->betweenIncluded('12:00', $jam_secondOut_pagi_sabtu)) {
                    $t1 = strtotime($strtime_secondOut_pagi_sabtu);
                    $t2 = strtotime($second_out);
                    //kkk
                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            } else {
                if (Carbon::parse($second_out)->betweenIncluded('12:00', $jam_secondOut_pagi)) {
                    $t1 = strtotime($strtime_secondOut_pagi);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else if (Carbon::parse($second_out)->betweenIncluded('09:00', '11:59')) {
                    $t1 = strtotime('12:00:00');
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam + 4);
                } else {
                    $late = null;
                }
            }
        } else {
            if (khusus_is_saturday($tgl)) {
                // if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59') ) {
                if (Carbon::parse($second_out)->betweenIncluded('19:00', $jam_secondOut_sore_sabtu)) {
                    $t1 = strtotime($strtime_secondOut_sore_sabtu);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            } else {
                if (Carbon::parse($second_out)->betweenIncluded('00:00', $jam_secondOut_sore)) {
                    $t1 = strtotime($strtime_secondOut_sore);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);

                    // ook
                } elseif (Carbon::parse($second_out)->betweenIncluded('19:00', $jam_secondOut_sore_sabtu)) {
                    $t1 = strtotime($strtime_secondOut_pagi_sabtu);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam) + 4;
                } else {
                    $late = null;
                }
            }
        }
    }
    return $late;
}

function khusus_checkOvertimeInLate($overtime_in, $shift, $tgl)
{
    $persetengahJam = 30;
    $late = null;
    if ($overtime_in != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($overtime_in)->betweenIncluded('12:00', '18:33')) {
                $late = null;
            } else {
                $t1 = strtotime('18:33:00');
                $t2 = strtotime($overtime_in);

                $diff = gmdate('H:i:s', $t2 - $t1);
                $late = ceil(hoursToMinutes($diff) / $persetengahJam);
            }
        }
    }
    return $late;
}

function khusus_checkFirstOutLate($first_out, $shift, $tgl, $jabatan, $placement_id)
{
    //ok
    $perJam = 60;
    $late = null;

    if (is_puasa($tgl)) {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            if ($first_out != null) {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (Carbon::parse($first_out)->betweenIncluded('08:00', '11:29')) {
                        $t1 = strtotime('11:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {

                    if (Carbon::parse($first_out)->betweenIncluded('01:00', '02:30')) {
                        $t1 = strtotime('02:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                }
            }
        }
    } else {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            if ($first_out != null) {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (Carbon::parse($first_out)->betweenIncluded('08:00', '11:29')) {
                        $t1 = strtotime('11:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else { // shift malam
                    if (khusus_is_saturday($tgl)) {
                        if (Carbon::parse($first_out)->betweenIncluded('17:01', '20:29')) {
                            $t1 = strtotime('20:30:00');
                            $t2 = strtotime($first_out);

                            $diff = gmdate('H:i:s', $t1 - $t2);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        } else {
                            $late = null;
                        }
                    } else {
                        if (Carbon::parse($first_out)->betweenIncluded('20:00', '23:29')) {
                            $t1 = strtotime('23:30:00');
                            $t2 = strtotime($first_out);

                            $diff = gmdate('H:i:s', $t1 - $t2);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        } else {
                            $late = null;
                        }
                    }
                }
            }
        }
    }
    return $late;
}

function khusus_checkSecondInLate($second_in, $shift, $firstOut, $tgl, $jabatan, $placement_id)
{
    $perJam = 60;
    $late = null;


    if (is_puasa($tgl)) {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            // jangan remark ini kalau ada error
            // $groupIstirahat;

            if ($second_in != null) {
                if ($shift == 'Pagi') {
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if (khusus_is_friday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('11:30', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);
                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('08:00', '12:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('12:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('11:00', '13:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('13:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        }
                    } else {
                        //jika first out null
                        if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('13:03:00');
                            $t2 = strtotime($second_in);

                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                        // if ($shift == 'Pagi') {
                        //     if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('13:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // } else {

                        //     if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('01:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // }
                    }
                } else { // shift malam
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('01:00', '02:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('03:00', '03:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if ($groupIstirahat == 1) {
                            if (Carbon::parse($second_in)->betweenIncluded('01:00', '03:29')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('03:33:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } elseif ($groupIstirahat == 2) {
                            if (Carbon::parse($second_in)->betweenIncluded('03:00', '03:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('04:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            $late = null;
                        }
                    } else {
                        if (Carbon::parse($second_in)->betweenIncluded('02:30', '04:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('04:03:00');
                            $t2 = strtotime($second_in);

                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                    }
                }
            }
        }
    } else {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            // jangan remark ini kalau ada error
            // $groupIstirahat;

            if ($second_in != null) {
                if ($shift == 'Pagi') {
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if (khusus_is_friday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('11:30', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);
                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('08:00', '12:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('12:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('11:00', '13:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('13:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        }
                    } else {
                        //jika first out null
                        if ($shift == 'Pagi') {
                            if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if (khusus_is_saturday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            }
                        }
                    }
                } else { //shift Malam
                    if (khusus_is_saturday($tgl)) { //ini ya 
                        if ($firstOut != null) {
                            if (Carbon::parse($firstOut)->betweenIncluded('17:00', '20:59')) {
                                $groupIstirahat = 1;
                            } elseif (Carbon::parse($firstOut)->betweenIncluded('21:00', '22:00')) {
                                $groupIstirahat = 2;
                            } else {
                                $groupIstirahat = 0;
                            }
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('17:00', '21:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('21:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('21:00', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        } else {
                            //jika first out null


                            if (Carbon::parse($second_in)->betweenIncluded('20:30', '22:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('22:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        }
                    } else {
                        if ($firstOut != null) {
                            if (Carbon::parse($firstOut)->betweenIncluded('20:00', '23:59')) {
                                $groupIstirahat = 1;
                            } elseif (Carbon::parse($firstOut)->betweenIncluded('00:00', '00:59')) {
                                $groupIstirahat = 2;
                            } else {
                                $groupIstirahat = 0;
                            }

                            // Shift Pagi ggg
                            if (khusus_is_friday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('23:30', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);
                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if ($groupIstirahat == 1) {
                                    if (Carbon::parse($second_in)->betweenIncluded('20:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '00:33')) {
                                        $late = null;
                                    } else {
                                        $t1 = strtotime('00:33:00');
                                        $t2 = strtotime($second_in);

                                        $diff = gmdate('H:i:s', $t2 - $t1);
                                        $late = ceil(hoursToMinutes($diff) / $perJam);
                                    }
                                } elseif ($groupIstirahat == 2) {
                                    if (Carbon::parse($second_in)->betweenIncluded('23:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                        $late = null;
                                    } else {
                                        $t1 = strtotime('01:03:00');
                                        $t2 = strtotime($second_in);

                                        $diff = gmdate('H:i:s', $t2 - $t1);
                                        $late = ceil(hoursToMinutes($diff) / $perJam);
                                    }
                                } else {
                                    $late = null;
                                }
                            }
                        } else {
                            //jika first out null

                            if (khusus_is_saturday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if (Carbon::parse($second_in)->betweenIncluded('23:30', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $late;
}


function khusus_noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out)
{
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null && (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null))) {
        return 'No Scan';
    }
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null) {
        return null;
    }
    if (($first_in == null) & ($first_out != null) || ($first_in != null) & ($first_out == null)) {
        return 'No Scan';
    }
    if (($second_in == null) & ($second_out != null) || ($second_in != null) & ($second_out == null)) {
        return 'No Scan';
    }
    // if (( $second_in == null ) && ( $second_out == null )) {
    //     return 'No Scan';
    // }
    // if ( ( $first_in == null ) && ( $first_out == null ) ) {
    //     return 'No Scan';
    // }

    if (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null)) {
        return 'No Scan';
    }
}

function khusus_hitungLembur($overtime_in, $overtime_out)
{
    if ($overtime_in != '' || $overtime_out != '') {
        $t1 = strtotime(pembulatanJamOvertimeIn($overtime_in));
        $t2 = strtotime(pembulatanJamOvertimeOut($overtime_out));

        $diff = gmdate('H:i:s', $t2 - $t1);
        $diff = explode(':', $diff);
        $jam = (int) $diff[0];
        $menit = (int) $diff[1];
        // if ( $menit<30 ) {
        //     $menit = 0;
        // } else {
        //     $menit = 30;
        // }
        $totalMenit = $jam * 60 + $menit;

        return $totalMenit;
    } else {
        return 0;
    }
}

function khusus_hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $tgl, $jabatan, $placement_id)
{
    $perJam = 60;
    if (is_puasa($tgl)) {
        if ($late == null) {
            if ($shift == 'Pagi') {
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } elseif (khusus_is_friday($tgl)) {
                    $jam_kerja = 7.5;
                } else {
                    $jam_kerja = 8;
                }
            } else {
                $jam_kerja = 8;
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } else {
                    $jam_kerja = 8;
                }
            }
        } else {
            // check late kkk
            $total_late = khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id);
            //    dd($first_in, $first_out, $second_in, $second_out);
            //jok
            if ($second_in === null && $second_out === null && ($first_in === null && $first_out === null)) {
                $jam_kerja = 0;
            } elseif (($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)) {
                if (khusus_is_saturday($tgl)) {
                    if ($first_in === null && $first_out === null) {
                        $jam_kerja = 2 - $total_late;
                        // $jam_kerja = 2 ;
                    } else {
                        $jam_kerja = 4 - $total_late;
                        // $jam_kerja = 4 ;
                    }
                } else {
                    $jam_kerja = 4 - $total_late;
                    // $jam_kerja = 4 ;
                }
            } else {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } elseif (khusus_is_friday($tgl)) {
                        $jam_kerja = 7.5 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                }
            }
        }
    } else {
        if ($late == null) {
            if ($shift == 'Pagi') {
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } elseif (khusus_is_friday($tgl)) {
                    $jam_kerja = 7.5;
                } else {
                    $jam_kerja = 8;
                }
            } else {
                $jam_kerja = 8;
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } else {
                    $jam_kerja = 8;
                }
            }
        } else {
            // check late kkk
            $total_late = khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id);
            //    dd($first_in, $first_out, $second_in, $second_out);
            //jok
            if ($second_in === null && $second_out === null && ($first_in === null && $first_out === null)) {
                $jam_kerja = 0;
            } elseif (($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)) {
                if (khusus_is_saturday($tgl)) {
                    if ($first_in === null && $first_out === null) {
                        $jam_kerja = 2 - $total_late;
                        // $jam_kerja = 2 ;
                    } else {
                        $jam_kerja = 4 - $total_late;
                        // $jam_kerja = 4 ;
                    }
                } else {
                    $jam_kerja = 4 - $total_late;
                    // $jam_kerja = 4 ;
                }
            } else {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } elseif (khusus_is_friday($tgl)) {
                        $jam_kerja = 7.5 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                }
            }
        }
    }



    // lolo
    if (khusus_is_sunday($tgl)) {

        // $t1 = strtotime($first_in);
        // $t2 = strtotime($second_out);
        // $t1 = strtotime(pembulatanJamOvertimeIn($first_in));
        // $t2 = strtotime(pembulatanJamOvertimeOut($second_out));



        // $diff = gmdate('H:i:s', $t2 - $t1);

        // $diff = explode(':', $diff);
        // $jam = (int) $diff[0];
        // $menit = (int) $diff[1];

        // if ($menit >= 45) {
        //     $jam = $jam + 1;
        // } elseif ($menit < 45 && $menit > 15) {
        //     $jam = $jam + 0.5;
        // } else {
        //     $jam;
        // }
        // $jam_kerja = $jam * 2;
        $jam_kerja *= 2;
    }
    if ($jabatan == 17 && khusus_is_sunday($tgl) == false) {
        $jam_kerja = 12;
        // $jam_kerja = $jam_kerja - $total_late;
    }

    return $jam_kerja;
}

function khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id)
{
    $late_1 = 0;
    $late_2 = 0;
    $late_3 = 0;
    $late_4 = 0;
    $late1 = khusus_checkFirstInLate($first_in, $shift, $tgl, $placement_id);
    $late2 = khusus_checkFirstOutLate($first_out, $shift, $tgl, $jabatan, $placement_id);
    $late3 = khusus_checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan, $placement_id);
    $late4 = khusus_checkSecondOutLate($second_out, $shift, $tgl, $jabatan, $placement_id);



    return $late1 + $late2 + $late3 + $late4;
}

function khusus_saveDetail($user_id, $first_in, $first_out, $second_in, $second_out, $late, $shift, $date, $jabatan_id, $no_scan, $placement_id, $overtime_in, $overtime_out)
{
    $tambahan_shift_malam = 0;
    if ($no_scan === null) {
        $tgl = tgl_doang($date);
        $jam_kerja = khusus_hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $date, $jabatan_id, get_placement($user_id));
        $terlambat = khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $date, $jabatan_id, get_placement($user_id));

        $langsungLembur = khusus_langsungLembur($second_out, $date, $shift, $jabatan_id, $placement_id);
        if (khusus_is_sunday($date)) {
            $jam_lembur = khusus_hitungLembur($overtime_in, $overtime_out) / 60 * 2
                + $langsungLembur * 2;
        } else {
            $jam_lembur = khusus_hitungLembur($overtime_in, $overtime_out) / 60 + $langsungLembur;
        }

        if ($shift == 'Malam') {
            if (khusus_is_saturday($date)) {
                if ($jam_kerja >= 6) {
                    // $jam_lembur = $jam_lembur + 1;
                    $tambahan_shift_malam = 1;
                }
            } else if (khusus_is_sunday($date)) {
                if ($jam_kerja >= 16) {
                    // $jam_lembur = $jam_lembur + 2;
                    $tambahan_shift_malam = 1;
                }
            } else {
                if ($jam_kerja >= 8) {
                    // $jam_lembur = $jam_lembur + 1;
                    $tambahan_shift_malam = 1;
                }
            }
        }
        // 22 driver
        if (($jam_lembur >= 9) && (khusus_is_sunday($date) == false) && ($jabatan_id != 22)) {
            $jam_lembur = 0;
        }
        // yig = 12, ysm = 13
        // if ($placement_id == 12 || $placement_id == 13 || $jabatan_id == 17) {
        if ($jabatan_id == 17) {
            if (khusus_is_friday($date)) {
                $jam_kerja = 7.5;
            } elseif (khusus_is_saturday($date)) {
                $jam_kerja = 6;
            } else {
                $jam_kerja = 8;
            }
        }
        if ($jabatan_id == 17 && khusus_is_sunday($date)) {
            $jam_kerja = hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $date, $jabatan_id, get_placement($user_id));
        }
        if ($jabatan_id == 17 && khusus_is_saturday($date)) {
            // $jam_lembur = 0;
        }
        // 23 translator
        if ($jabatan_id != 23) {
            if (
                khusus_is_libur_nasional($date) &&  !khusus_is_sunday($date)
                && $jabatan_id != 23

            ) {
                $jam_kerja *= 2;
                $jam_lembur *= 2;
            }
        } else {
            if (khusus_is_sunday($date)) {
                $jam_kerja /= 2;
                $jam_lembur /= 2;
            }
        }

        // $this->dataArr->push([
        //     'tgl' => $tgl,
        //     'jam_kerja' => $jam_kerja,
        //     'terlambat' => $terlambat,
        //     'jam_lembur' => $jam_lembur,
        //     'tambahan_shift_malam' => $tambahan_shift_malam,
        // ]);

        return [
            'tgl' => $tgl,
            'jam_kerja' => $jam_kerja,
            'terlambat' => $terlambat,
            'jam_lembur' => $jam_lembur,
            'tambahan_shift_malam' => $tambahan_shift_malam
        ];
    }
}

function khusus_langsungLembur($second_out, $tgl, $shift, $jabatan, $placement_id)
{

    // betulin
    if ($second_out != null) {
        $t2 = strtotime($second_out);
        if (!khusus_is_saturday($tgl) && $shift == 'Pagi' && $t2 < strtotime('04:00:00')) {
            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
            $diff = $diff + 7;
            return $diff;
        }
    }
    if (is_puasa($tgl)) {
        if ($second_out != null) {
            $lembur = 0;
            $t2 = strtotime($second_out);
            if ($jabatan == 17) {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:00:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:00:00') && $t2 > strtotime('11:30:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:29:00') && $t2 >= strtotime('20:00:00')) {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;

                                // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('19:30:00')) / 60;
                            } else {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:00:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('04:30:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:00:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('07:30:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (khusus_is_saturday($tgl)) {
                        if ($t2 < strtotime('15:00:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('14:30:00')) / 60;
                    } else {
                        if ($t2 < strtotime('17:00:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                    }
                } else {
                    //Shift Malam
                    if (khusus_is_saturday($tgl)) {
                        // if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                        //     return $lembur = 0;
                        // }
                        // if ( $t2 <= strtotime('23:29:00'))  || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:29:00')) {
                        //     return $lembur = 0;
                        // }
                        $t23_29 = strtotime('23:29:00');
                        $t23_30 = strtotime('23:30:00');
                        $t23_00 = Carbon::parse('23:00:00');
                        $t00_00 = strtotime('00:00:00');
                        $t05_00 = strtotime('05:00:00');

                        $t2 = strtotime($second_out);
                        $t20_00 = strtotime('20:00:00');
                        $t23_29 = strtotime('23:29:00');

                        // Jika $t2 berada di antara 22:00:00 dan 23:29:00, lembur = 0
                        if ($t2 >= $t20_00 && $t2 <= $t23_29) {
                            return $lembur = 0;
                        }


                        // if ($t2 >= strtotime('23:30:00') || ($t2 >= strtotime('00:00:00') && $t2 <= $t05_00)) {
                        //     $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->addDay()->diffInMinutes($t23_00) / 60;
                        // }
                        if ($t2 >= strtotime('00:00:00') && $t2 <= $t05_00) {
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->addDay()->diffInMinutes($t23_00) / 60;
                        }
                        if ($t2 >= strtotime('23:30:00')) {
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes($t23_00) / 60;
                        }

                        // Default jika tidak masuk kondisi apapun
                    } else {
                        // if ($t2 < strtotime('05:00:00') && $t2 <= strtotime('23:29:00')) {
                        //     return $lembur = 0;
                        // }
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }
            if (isset($diff) && $diff !== null) return $diff;
            // return $diff;
        } else {
            return $lembur = 0;
        }
    } else {
        if ($second_out != null) {

            $lembur = 0;

            $t2 = strtotime($second_out);
            // ini puasa kah
            if ($jabatan == 17) {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:30:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:30:00') && $t2 > strtotime('12:00:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:59:00') && $t2 >= strtotime('20:30:00')) {

                                // mk

                                // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('19:30:00')) / 60;
                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;
                            } else {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (khusus_is_saturday($tgl)) {
                        // if ($tgl == '2025-04-18') {
                        if (khusus_is_friday($tgl)) {
                            if ($t2 < strtotime('16:00:00')) {
                                return $lembur = 0;
                            }
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:30:00')) / 60;
                        } else {
                            if ($t2 < strtotime('15:30:00')) {
                                return $lembur = 0;
                            }
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('17:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                    }
                } else {
                    //Shift Malam
                    if (khusus_is_saturday($tgl)) {
                        if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
                    } else {
                        if ($t2 < strtotime('05:30:00') && $t2 <= strtotime('23:59:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }

            return $diff;
        } else {
            return $lembur = 0;
        }
    }
}

function khusus_late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl, $id)
{
    // if ($tgl === '2025-04-18') {
    if (is_friday($tgl)) {
        return $late = 0;
    }
    try {
        $data_jabatan = Karyawan::where('id_karyawan', $id)->first();
        $jabatan = $data_jabatan->jabatan_id;
        $jabatan_khusus = is_jabatan_khusus($jabatan);
    } catch (\Exception $e) {
        dd('ID karyawan tidak ada dalam database = ', $id);
        return $e->getMessage();
    }

    $late5 = null;

    // if(($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)){
    if (($second_in === '' && $second_out === '') || ($first_in === '' && $first_out === '')) {
        // $data->late = 1;
        // dd($data->late, $data->user_id);
        return $late = 1;
    }

    if (khusus_checkFirstInLate($first_in, $shift, $tgl, get_placement($id))) {
        //  return $late = $late + 1;
        return $late = 1;
        // $late1 = 1;
    }
    if (khusus_checkFirstOutLate($first_out, $shift, $tgl, $jabatan_khusus, get_placement($id))) {
        // if ($jabatan_khusus == '') {
        //     return $late = 1;
        // }
        return $late = 1;
    }
    if (khusus_checkSecondOutLate($second_out, $shift, $tgl, $jabatan, get_placement($id))) {
        //  return $late = $late + 1;
        // if ($jabatan_khusus != '1') {
        //     return $late = 1;
        // }
        return $late = 1;

        // return $late = 1;
        // $late3 = 1;
    }


    if (khusus_checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan_khusus, get_placement($id))) {
        // return $late = $late + 1 ;

        // if ($jabatan_khusus == '') {
        //     return $late = 1;
        // }
        return $late = 1;
        // $late5 = 1;
    }

    if ($second_in == null && $second_out == null) {
        return $late = 1;
    }
    if ($first_in == null && $first_out == null) {
        return $late = 1;
    }
    // $late = $late1 + $late2 + $late3+ $late4 + $late5 ;
    // return $late;
}

function khusus_is_friday($tgl)
{
    $tgl_khusus = Harikhusus::where('date', $tgl)->first();
    if ($tgl_khusus->is_friday === 0) {
        return false;
    }

    if ($tgl) {
        return Carbon::parse($tgl)->isFriday();
    }
}

function khusus_is_saturday($tgl)
{
    $tgl_khusus = Harikhusus::where('date', $tgl)->first();
    if ($tgl_khusus->is_saturday === 1) {
        return true;
    } else {
        return false;
    }
}

function khusus_is_libur_nasional($tanggal)
{
    $tgl_khusus = Harikhusus::where('date', $tanggal)->first();
    if ($tgl_khusus->is_hari_libur_nasional === 0) {
        return false;
    }

    $data = Liburnasional::where('tanggal_mulai_hari_libur', $tanggal)->first();
    if ($data != null) return true;
    return false;
}

function khusus_is_sunday($tgl)
{
    $tgl_khusus = Harikhusus::where('date', $tgl)->first();
    if ($tgl_khusus->is_sunday === 0) {
        return false;
    }
    if ($tgl) {
        return Carbon::parse($tgl)->isSunday();
    }
}
