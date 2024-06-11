<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Prindexwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $periode;
    public $search = '';
    public $cx = 0;
    public $columnName = 'user_id';
    public $direction = 'desc';
    public $perpage = 10;
    public $year;
    public $month;
    public $select_month, $select_year;


    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }

    public function mount()
    {
        if (now()->day < 5) {
            $this->year =
                now()->subMonth()->year;
            $this->month =
                now()->subMonth()->month;
        } else {
            $this->year = now()->year;
            $this->month = now()->month;
        }


        $getTglTerakhir = Yfrekappresensi::select('date')
            ->orderBy('date', 'desc')
            ->first();
        if ($getTglTerakhir != null) {
            $this->periode = buatTanggal($getTglTerakhir->date);
        } else {
            $this->periode = '2000-01-01';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function buat_payroll()
    {
        // supaya tidak dilakukan bersamaan
        $lock = Lock::find(1);
        if ($lock->build == 1) {
            // $this->dispatch('error', message: 'Mohon dicoba sebentar lagi');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Mohon dicoba sebentar lagi',
            );
            return;
        } else {
            $lock->build = 1;
            $lock->save();
        }


        $result = build_payroll($this->month, $this->year);
        if ($result == 0) {
            // $this->dispatch('error', message: 'Data Presensi tidak ada');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data Presensi tidak ada',
            );
        } else {
            // $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Payroll Karyawan Sudah di Built',
            );
        }

        $lock->build = 0;
        $lock->save();
        return redirect()->to('/payrollindex');
    }

    // ok1
    // #[On('getPayroll')]

    public function getPayroll()
    {
        // supaya tidak dilakukan bersamaan
        $lock = Lock::find(1);
        if ($lock->build) {
            $lock->build = 0;
            return back()->with('error', 'Mohon dicoba sebentar lagi');
        } else {
            $lock->build = 1;
            $lock->save();
        }

        $jamKerjaKosong = Jamkerjaid::count();
        $adaPresensi = Yfrekappresensi::count();
        if ($jamKerjaKosong == null && $adaPresensi == null) {
            clear_locks();
            // $this->dispatch('error', message: 'Data Presensi Masih Kosong');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data Presensi Masih Kosong',
            );
            return back();
        }

        // AMBIL DATA TERAKHIR DARI REKAP PRESENSI PADA BULAN YBS
        $last_data_date = Yfrekappresensi::query()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'desc')
            ->first();

        Jamkerjaid::whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
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

        $filterArray = Yfrekappresensi::whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            // ->where('status')
            ->pluck('user_id')
            ->unique();

        // buat tabel user_id unique ook
        foreach ($filterArray as $item) {
            $filteredData = new Jamkerjaid();
            $filteredData->user_id = $item;
            $filteredData->karyawan_id = 1;
            $filteredData->date = $this->year . '-' . $this->month . '-01';
            $filteredData->save();
        }

        $filteredData = Jamkerjaid::with(['karyawan' => ['id_karyawan', 'jabatan', 'placement']])
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->get();

        // disini mulai prosesnya
        foreach ($filteredData as $data) {
            $dataId = Yfrekappresensi::with('karyawan')

                ->where('user_id', $data->user_id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->orderBy('date', 'desc')
                ->get();

            if (!$dataId) {
                dd('data kosong from Prindex.php', $dataId);
            } else {
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
                        // $tgl = tgl_doang($d->date);
                        $jam_lembur = 0;
                        $tambahan_shift_malam = 0;
                        $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                        $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);
                        //evaluasi ini
                        // if ($d->karyawan->jabatan === 'Satpam') {
                        //     $jam_kerja = $terlambat >= 6 ? 0.5 : $jam_kerja;
                        // }

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

                        // if($d->karyawan->jabatan == 'Satpam' && is_sunday($d->date)) {

                        //     $jam_kerja =  $jam_kerja * 2;
                        //     $jam_lembur = $jam_lembur * 2;

                        // }

                        // if($d->karyawan->jabatan == 'Satpam') {
                        //     if($jam_kerja >= 8){
                        //         if( is_friday($d->date) ) {
                        //             $jam_kerja = 7.5;
                        //         } elseif (is_saturday($d->date)) {
                        //             $jam_kerja = 6;
                        //         } else {
                        //             $jam_kerja = 8;
                        //         }
                        //     }
                        // }

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

                $data->total_hari_kerja = $total_hari_kerja;
                $data->jumlah_jam_kerja = $total_jam_kerja;
                $data->jumlah_menit_lembur = $total_jam_lembur;
                $data->jumlah_jam_terlambat = $total_keterlambatan;
                $data->tambahan_jam_shift_malam = $total_tambahan_shift_malam;
                $data->total_noscan = $n_noscan;

                // $data = Jamkerjaid::find($data->id);

                $data->karyawan_id = $d->karyawan->id;
                $data->date = buatTanggal($d->date);
                $data->last_data_date = $last_data_date->date;
                $data->save();
            }
            // DATA TOTAL per id yang sdh terkumpul ok4
            // dd($data->user_id, $grand_total_hari_kerja, $grand_total_jam_kerja, $grand_total_jam_lembur, $grand_total_keterlambatan, $grand_total_tambahan_shift_malam, $grand_n_noscan);
        }

        $current_date = Jamkerjaid::orderBy('date', 'desc')->first();
        // $this->periode = $current_date->date;

        $lock->build = false;
        $lock->save();

        // $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Payroll Karyawan Sudah di Built',
        );
        $this->rebuild();
    }

    // ok2
    public function rebuild()
    {
        $datas = Jamkerjaid::with('karyawan', 'yfrekappresensi')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->get();

        if ($datas->isEmpty()) {
            // $this->dispatch('error', message: 'Data Tidak Ditemukan');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data Tidak Ditemukan',
            );
            return back();
        }

        $subtotal = 0;
        $denda_noscan = 0;

        Payroll::whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->truncate();

        foreach ($datas as $data) {
            $payroll = new Payroll();
            $payroll->jamkerjaid_id = $data->id;
            $payroll->nama = $data->karyawan->nama;
            $payroll->id_karyawan = $data->karyawan->id_karyawan;
            $payroll->jabatan = $data->karyawan->jabatan;
            $payroll->company = $data->karyawan->company;
            $payroll->placement = $data->karyawan->placement;
            $payroll->status_karyawan = $data->karyawan->status_karyawan;
            $payroll->metode_penggajian = $data->karyawan->metode_penggajian;
            $payroll->nomor_rekening = $data->karyawan->nomor_rekening;
            $payroll->nama_bank = $data->karyawan->nama_bank;
            $payroll->gaji_pokok = $data->karyawan->gaji_pokok;
            $payroll->gaji_lembur = $data->karyawan->gaji_overtime;
            $payroll->gaji_bpjs = $data->karyawan->gaji_bpjs;
            $payroll->jkk = $data->karyawan->jkk;
            $payroll->jkm = $data->karyawan->jkm;
            $payroll->hari_kerja = $data->total_hari_kerja;
            $payroll->jam_kerja = $data->jumlah_jam_kerja;
            $payroll->jam_lembur = $data->jumlah_menit_lembur;
            $payroll->jumlah_jam_terlambat = $data->jumlah_jam_terlambat;
            $payroll->total_noscan = $data->total_noscan;

            //ok4
            if ($data->total_noscan > 3 && $payroll->metode_penggajian == 'Perjam') {
                $denda_noscan = ($data->total_noscan - 3) * ($payroll->gaji_pokok / 198);
            } else {
                $denda_noscan = 0;
            }
            // dari karyawan
            $payroll->thr = $data->karyawan->bonus;
            $payroll->tunjangan_jabatan = $data->karyawan->tunjangan_jabatan;
            $payroll->tunjangan_bahasa = $data->karyawan->tunjangan_bahasa;
            $payroll->tunjangan_skill = $data->karyawan->tunjangan_skill;
            $payroll->tunjangan_lama_kerja = $data->karyawan->tunjangan_lama_kerja;
            $payroll->tunjangan_lembur_sabtu = $data->karyawan->tunjangan_lembur_sabtu;

            $payroll->iuran_air = $data->karyawan->iuran_air;
            $payroll->iuran_locker = $data->karyawan->iuran_locker;
            $payroll->tambahan_jam_shift_malam = $data->tambahan_jam_shift_malam;

            $payroll->tambahan_shift_malam = $data->tambahan_jam_shift_malam * $payroll->gaji_lembur;

            // if ($data->karyawan->placement == 'YIG' || $data->karyawan->placement == 'YSM'|| $data->karyawan->jabatan == 'Satpam') {
            //     $payroll->subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + ($payroll->jam_lembur * $data->karyawan->gaji_overtime);
            // } else {
            // }

            $payroll->subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + $payroll->jam_lembur * $data->karyawan->gaji_overtime;

            if ($data->karyawan->potongan_JP == 1) {
                if ($data->karyawan->gaji_bpjs <= 9559600) {
                    $payroll->jp = $data->karyawan->gaji_bpjs * 0.01;
                } else {
                    $payroll->jp = 9559600 * 0.01;
                }
            } else {
                $payroll->jp = 0;
            }

            if ($data->karyawan->potongan_JHT == 1) {
                $payroll->jht = $data->karyawan->gaji_bpjs * 0.02;
            } else {
                $payroll->jht = 0;
            }

            if ($data->karyawan->potongan_kesehatan == 1) {
                $payroll->kesehatan = $data->karyawan->gaji_bpjs * 0.01;
            } else {
                $payroll->kesehatan = 0;
            }

            $payroll->pajak = 0;
            if ($data->karyawan->potongan_JKK == 1) {
                $payroll->jkk = 1;
            } else {
                $payroll->jkk = 0;
            }
            if ($data->karyawan->potongan_JKM == 1) {
                $payroll->jkm = 1;
            } else {
                $payroll->jkm = 0;
            }

            if ($data->total_noscan == null) {
                $payroll->denda_lupa_absen = 0;
            } else {
                if ($data->total_noscan <= 3) {
                    $payroll->denda_lupa_absen = 0;
                } else {
                    $payroll->denda_lupa_absen = ($data->total_noscan - 3) * ($payroll->gaji_pokok / 198);
                }
            }

            $payroll->date = $data->date;

            $total_bonus_dari_karyawan = 0;
            $total_potongan_dari_karyawan = 0;

            $total_bonus_dari_karyawan = $data->karyawan->bonus + $data->karyawan->tunjangan_jabatan + $data->karyawan->tunjangan_bahasa + $data->karyawan->tunjangan_skill + $data->karyawan->tunjangan_lembur_sabtu + $data->karyawan->tunjangan_lama_kerja;
            $total_potongan_dari_karyawan = $data->karyawan->iuran_air + $data->karyawan->iuran_locker;
            $payroll->total = $payroll->subtotal + $total_bonus_dari_karyawan + $payroll->tambahan_shift_malam - $total_potongan_dari_karyawan - $payroll->pajak - $payroll->jp - $payroll->jht - $payroll->kesehatan - $payroll->denda_lupa_absen;
            $payroll->save();
        }
        // $this->dispatch('success', message: 'Data Payrol succesfully Rebuild');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Payrol succesfully Rebuild',
        );
        $this->bonus_potongan();
    }

    // ok3
    public function bonus_potongan()
    {
        $bonus = 0;
        $potongaan = 0;
        $all_bonus = 0;
        $all_potongan = 0;
        $tambahan = Tambahan::whereMonth('tanggal', $this->month)
            ->whereYear('tanggal', $this->year)
            ->get();

        foreach ($tambahan as $d) {
            $all_bonus = $d->uang_makan + $d->bonus_lain;
            $all_potongan = $d->baju_esd + $d->gelas + $d->sandal + $d->seragam + $d->sport_bra + $d->hijab_instan + $d->id_card_hilang + $d->masker_hijau + $d->potongan_lain;
            $id_payroll = Payroll::whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
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

        // $this->dispatch('success', message: 'Bonus dan Potangan added');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Bonus dan Potangan added',
        );
    }

    public function updatedYear()
    {
        $this->select_month = Payroll::select(DB::raw('MONTH(date) as month'))->whereYear('date', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        $this->month = $this->select_month[0];
    }

    public function render()
    {
        $this->select_year = Payroll::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        $months = Payroll::select(DB::raw('MONTH(date) as month'))
            ->whereYear('date', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        if (!in_array($this->month, $months)) {
            $months[] = $this->month;
        }

        $this->select_month = $months;

        // $periodePayroll = DB::table('yfrekappresensis')
        //     ->select(DB::raw('YEAR(date) year, MONTH(date) month, MONTHNAME(date) month_name'))
        //     ->distinct()
        //     ->orderBy('year', 'desc')
        //     ->orderBy('month', 'desc')
        //     ->get();
        $this->cx++;


        $filteredData = Jamkerjaid::select(['jamkerjaids.*', 'karyawans.nama'])
            ->join('karyawans', 'jamkerjaids.karyawan_id', '=', 'karyawans.id')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            // ->whereMonth('date', 'like', '%' . $this->month . '%')
            //     ->whereYear('date', 'like', '%' . $this->year . '%')
            ->where(function ($query) {
                $query->when($this->search, function ($subQuery) {
                    $subQuery
                        ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                        ->orWhere('user_id', trim($this->search))
                        ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
                        ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%')
                        ->orWhere('status_karyawan', 'LIKE', '%' . trim($this->search) . '%');
                });
            })

            ->orderBy($this->columnName, $this->direction)
            ->orderBy('user_id', 'asc')
            ->paginate($this->perpage);

        // $filteredData = Jamkerjaid::with('karyawan')
        // ->whereMonth('date', 'like', '%' . $this->month . '%')
        // ->whereYear('date', 'like', '%' . $this->year . '%')
        // ->orWhereRelation('karyawan', 'nama', 'LIKE', '%' . trim($this->search) . '%')
        // ->orWhereRelation('karyawan', 'user_id', trim($this->search))
        // ->orWhereRelation('karyawan', 'jabatan', trim($this->search))
        // ->orderBy($this->columnName, $this->direction)
        // ->sortBy('karyawan.id_karyawan')
        // ->paginate($this->perpage);

        if ($filteredData->isNotEmpty()) {
            $lastData = $filteredData[0]->last_data_date;
        } else {
            $lastData = null;
        }

        $tgl = Jamkerjaid::whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->select('created_at')->first();
        if ($tgl != null) {
            $last_build = Carbon::parse($tgl->created_at)->diffForHumans();
        } else {
            $last_build = 0;
        }

        // return view('livewire.prindexwr', compact(['filteredData', 'periodePayroll', 'lastData', 'last_build']));
        return view('livewire.prindexwr', compact(['filteredData',  'lastData', 'last_build']));
    }
}
