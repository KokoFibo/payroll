<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class Payrollwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $selected_company = 0;
    public $search;
    public $perpage = 10;
    public $month;
    public $year;
    public $columnName = 'id_karyawan';
    public $direction = 'asc';
    public $status = 1;
    public $data_payroll;
    public $data_karyawan;

    public function showDetail($id_karyawan)
    {
        $this->data_payroll = Payroll::with('jamkerjaid')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->where('id_karyawan', $id_karyawan)
            ->first();

        $this->data_karyawan = Karyawan::where('id_karyawan', $id_karyawan)->first();
        // dd($this->data_karyawan);
    }

    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $data = Payroll::first();

        $this->year = now()->year;
        $this->month = now()->month;
        if($data != null) {

            $this->data_payroll = Payroll::with('jamkerjaid')
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->where('id_karyawan', $data->id_karyawan)
                ->first();
    
            $this->data_karyawan = Karyawan::where('id_karyawan', $data->id_karyawan)->first();
        }
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
            $this->dispatch('error', message: 'Data Presensi Masih Kosong');
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

        $filteredData = Jamkerjaid::with(['karyawan' => ['id_karyawan, jabatan']])
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->get();

        // disini mulai prosesnya
        foreach ($filteredData as $data) {
            // $dataId = Yfrekappresensi::with(['karyawan'=>['id_karyawan, jabatan']])
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
                        if ($d->karyawan->jabatan === 'Satpam') {
                            $jam_kerja = $terlambat >= 6 ? 0.5 : $jam_kerja;
                        }

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

        $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built');
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
            $this->dispatch('error', message: 'Data Tidak Ditemukan');
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
            //ok4
            if ($data->total_noscan > 3 && $payroll->metode_penggajian == 'Perjam') {
                $denda_noscan = ($data->total_noscan - 3) * ($payroll->gaji_pokok / 198);
            } else {
                $denda_noscan = 0;
            }

            $payroll->bonus1x = $data->karyawan->bonus + $data->karyawan->tunjangan_jabatan + $data->karyawan->tunjangan_bahasa + $data->karyawan->tunjangan_skill + $data->karyawan->tunjangan_lama_kerja;
            $payroll->potongan1x = $data->karyawan->iuran_air + $data->karyawan->iuran_locker + $data->karyawan->denda + $denda_noscan;

            $payroll->tambahan_shift_malam = $data->tambahan_jam_shift_malam * $payroll->gaji_lembur;

            // if ($payroll->metode_penggajian == 'Perjam') {
            //     $payroll->subtotal = $payroll->tambahan_shift_malam +  $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + ($data->jumlah_menit_lembur / 60) * $data->karyawan->gaji_overtime;
            // } else {
            //     if ($payroll->gaji_lembur == 0) {
            //         $payroll->subtotal = $data->total_hari_kerja * ($data->karyawan->gaji_pokok / 26);
            //     } else {
            //         $payroll->subtotal = $payroll->tambahan_shift_malam + $data->total_hari_kerja * ($data->karyawan->gaji_pokok / 26) + ($data->jumlah_menit_lembur / 60) * $data->karyawan->gaji_overtime;
            //     }
            // }

            if ($payroll->gaji_lembur == 0) {
                $payroll->subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198);
                if ($data->karyawan->placement == 'YIG' || $data->karyawan->placement == 'YSM') {
                    $payroll->subtotal = $payroll->hari_kerja * (($data->karyawan->gaji_pokok / 198) * 8);
                }
            } else {
                $payroll->subtotal = $payroll->tambahan_shift_malam + $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + ($data->jumlah_menit_lembur / 60) * $data->karyawan->gaji_overtime;
                if ($data->karyawan->placement == 'YIG' || $data->karyawan->placement == 'YSM') {
                    $payroll->subtotal = $payroll->tambahan_shift_malam + $payroll->hari_kerja * (($data->karyawan->gaji_pokok / 198) * 8) + ($data->jumlah_menit_lembur / 60) * $data->karyawan->gaji_overtime;
                }
            }

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
            $payroll->total = $payroll->subtotal + $payroll->bonus - $payroll->potongan1x - $payroll->pajak - $payroll->jp - $payroll->jht - $payroll->kesehatan - $payroll->denda_lupa_absen;
            $payroll->save();
        }
        $this->dispatch('success', message: 'Data Payrol succesfully Rebuild');
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
            $all_bonus = $d->uang_makan + $d->bonus + $d->bonus_lain;
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

        $this->dispatch('success', message: 'Bonus dan Potangan added');

        // $bonus1x = $bonus1x + $all_bonus;
        // $potongaan = $potongaan + $all_potongan;
    }

    public function getPayrollQuery($statuses, $search = null, $placement = null, $company = null)
    {
        return Payroll::query()
            ->whereIn('status_karyawan', $statuses)
            ->when($search, function ($query) use ($search) {
                $query
                    ->where('id_karyawan', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('nama', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('jabatan', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('company', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('metode_penggajian', 'LIKE', '%' . trim($search) . '%');
            })
            ->when($placement, function ($query) use ($placement) {
                $query->where('placement', $placement);
            })
            ->when($company, function ($query) use ($company) {
                $query->where('company', $company);
            })

            ->orderBy($this->columnName, $this->direction);
    }

    public function render()
    {
        $latest_payroll_id = Payroll::latest()->first();

        if(Payroll::count() == 0){
            $this->rebuild();
        } else {
            if(Jamkerjaid::find($latest_payroll_id->jamkerjaid_id)==null){
                $this->rebuild();
            }
        }

        if ($this->status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan'];
        } elseif ($this->status == 2) {
            $statuses = ['Resigned', 'Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }

        switch ($this->selected_company) {
            case 0:
                $total = Payroll::whereIn('status_karyawan', $statuses)->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search)->paginate($this->perpage);
                break;

            case 1:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YCME')
                    ->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YCME')->paginate($this->perpage);
                break;

            case 2:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV')
                    ->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV')->paginate($this->perpage);
                break;

            case 3:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->whereIn('placement', ['YIG', 'YSM'])
                    ->sum('total');

                $payroll = Payroll::query()
                    ->whereIn('status_karyawan', $statuses)
                    ->when($this->search, function ($query) {
                        $query
                            ->where('id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('company', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->whereIn('placement', ['YIG', 'YSM'])
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate($this->perpage);
                break;

            case 4:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'ASB')
                    ->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'ASB')
                    ->where('company', 'ASB')
                    ->paginate($this->perpage);
                break;

            case 5:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'DPA')
                    ->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'DPA')
                    ->where('company', 'DPA')
                    ->paginate($this->perpage);
                break;

            case 6:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YCME')
                    ->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YCME')
                    ->where('company', 'YCME')
                    ->paginate($this->perpage);
                break;

            case 7:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YEV')
                    ->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YEV')
                    ->where('company', 'YEV')
                    ->paginate($this->perpage);
                break;

            case 8:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YIG')
                    ->sum('total');

                $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YIG')
                    ->where('company', 'YIG')
                    ->paginate($this->perpage);
                break;

            case 9:
                $total = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YSM')
                    ->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YSM')
                    ->where('company', 'YSM')
                    ->paginate($this->perpage);
                break;
        }

        /**
         * Get the payroll query based on parameters.
         *
         * @param array|string $statuses
         * @param string|null $search
         * @param array|string|null $placement
         *
         * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
         */

        $tgl = Payroll::select('updated_at')->first();
        if ($tgl != null) {
            $last_build = Carbon::parse($tgl->updated_at)->diffForHumans();
        } else {
            $last_build = 0;
        }

        $data_kosong = Jamkerjaid::count();

        return view('livewire.payrollwr', compact(['payroll', 'total', 'last_build', 'data_kosong']));
    }
}
