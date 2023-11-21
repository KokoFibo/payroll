<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class Payrollwr extends Component
{
    use WithPagination;
    public $selected_company=0;
    public $search;
    public $perpage = 10;
    public $month;
    public $year;
    public $columnName = 'id_karyawan';
    public $direction = 'asc';
    public $status = 1;

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
        $this->year = now()->year;
        $this->month = now()->month;
    }

    // #[On('getPayroll')]
    public function getPayroll()
    {
        // supaya tidak dilakukan bersamaan
        $lock = Lock::find(1);
        if($lock->build) {
            $lock->build = 0;
            return back()->with( 'error', 'Mohon dicoba sebentar lagi' );
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



        // $tglsementara = Yfrekappresensi::where('no_scan', 'No Scan')
        //     ->whereYear('date', $this->year)
        //     ->whereMonth('date', $this->month)
        //     ->count();

        // if ($tglsementara) {
        //     clear_locks();
        //     $this->dispatch('error', message: 'Masih ada data no scan');
        //     return back();
        // }

        $checkIfJamKerjaExist = Jamkerjaid::whereMonth('date', $this->month)
        ->whereYear('date', $this->year)
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
            $filteredData->date = $this->year.'-'.$this->month.'-01';
            $filteredData->save();
        }
        $filteredData = Jamkerjaid::with('karyawan')->whereMonth('date', $this->month)
        ->whereYear('date', $this->year)->get();
        foreach ($filteredData as $data) {
            $jumlah_menit_lembur = 0;
            $jumlah_jam_terlambat = 0;
            $jumlah_menit_lembur = 0;
            $jumlah_hari_kerja = 0;

            $total_noscan = 0;
            $n_noscan = 0;

            $total_late_1 = 0;
            $total_late_2 = 0;
            $total_late_3 = 0;
            $total_late_4 = 0;
            $total_late_5 = 0;
            $total_late = 0;
            $jam_kerja = 0;
            $total_jam_kerja = 0;
            $total_langsungLembur = 0;
            $satpam_halfday = 0;

            $dataId = Yfrekappresensi::with('karyawan')->where('user_id', $data->user_id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->get();


            if (!$dataId) {
                dd('data kosong from Prindex.php', $dataId);
            } else {
                // ambil data per user id ok3
                $n_noscan = 0;
                foreach ($dataId as $dt) {
                if($dt->no_scan != 'No Scan') {

                    $langsungLembur = 0 ;
                    $jam_kerja = 0;

                    if($dt->no_scan_history == 'No Scan') {
                            $n_noscan++;
                        }
                $jam_kerja = hitung_jam_kerja($dt->first_in, $dt->first_out, $dt->second_in, $dt->second_out, $dt->late, $dt->shift, $dt->date, $dt->karyawan->jabatan);
                // if($dt->shift == 'Malam' || is_jabatan_khusus($dt->user_id)) {
                    $langsungLembur = langsungLembur( $dt->second_out, $dt->date, $dt->shift, $dt->karyawan->jabatan);
                // }

                // $jam_kerja = $jam_kerja_harian;
                $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                $total_langsungLembur = $total_langsungLembur + ($langsungLembur * 60 );



                    if ($dt->late == null) {
                        // if($dt->no_scan_history) {
                        //     $n_noscan++;
                        // }
                        // $n_noscan = $dt->no_scan_history;

                        // khusus NO Late
                        $jumlah_hari_kerja = $dataId->count();

                        if ($dt->overtime_in != null) {
                            // $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            // $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                            try {
                                $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                                $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                            } catch (\Exception $e) {
                                //  return $e->getMessage();ook
                                // $this->dispatch('success', message: 'Error user ID:' . $dt->user_id . 'Tanggal :' . $dt->date);
                                $errorId = 'Error user ID: ' . $dt->user_id . ', Tanggal : ' . $dt->date;
                                clear_locks();
                                $this->dispatch('foundError', title: $errorId);

                                return $e->getMessage();
                                //  dd($dt->user_id, $dt->date);
                            }
                        }
                    } else {
                        // khusus yang late

                        $jumlah_hari_kerja = $dataId->count();
                        // if($dt->no_scan_history) {
                        //     $n_noscan++;
                        // }


                        // check keterlambatan di hari kerja non overtime
                        $late1 = checkFirstInLate($dt->first_in, $dt->shift, $dt->date);
                        $late2 = checkFirstOutLate($dt->first_out, $dt->shift, $dt->date, $dt->karyawan->jabatan);
                        $late3 = checkSecondInLate($dt->second_in, $dt->shift, $dt->first_out, $dt->date, $dt->karyawan->jabatan);
                        $late4 = checkSecondOutLate($dt->second_out, $dt->shift, $dt->date, $dt->karyawan->jabatan);
                        // $late5 = checkOvertimeInLate($dt->overtime_in, $dt->shift, $dt->date);

                        if($dt->karyawan->jabatan == 'Satpam') {
                            if($late4 >= 6) {

                                $satpam_halfday = $satpam_halfday + 0.5;
                            }
                        }

                        if(($dt->second_in === null && $dt->second_out === null) || ($dt->first_in === null && $dt->first_out === null)){
                            $late1 = 0;
                            $late2 = 0;
                            $late3 = 0;
                            $late4 = 0;
                        }


                        $total_late_1 = $total_late_1 + $late1;
                        $total_late_2 = $total_late_2 + $late2;
                        $total_late_3 = $total_late_3 + $late3;
                        $total_late_4 = $total_late_4 + $late4;

                        if(($dt->second_in === null && $dt->second_out === null) || ($dt->first_in === null && $dt->first_out === null)){
                            if(is_saturday( $dt->date )) {
                                if($dt->first_in === null && $dt->first_out === null) {
                                    $jam_kerja = $jam_kerja - 4;
                                } else {
                                    $jam_kerja = $jam_kerja -2;
                                }
                            } else {
                                $jam_kerja = $jam_kerja - 4;
                            }


                        }
                        $total_late = $total_late_1 + $total_late_2 + $total_late_3 + $total_late_4 ;
                        if ($dt->overtime_in != null) {
                            $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                        }
                    }
                    $total_noscan = $total_noscan + $n_noscan;

                }
                }
                $dt_name = $dt->name;
                $dt_date = $dt->date;
                $dt_karyawan_id = $dt->karyawan_id;

                $jumlah_jam_terlambat = $jumlah_jam_terlambat + $late;
            }
            // DATA TOTAL per id yang sdh terkumpul ok4

            if($total_noscan == 0) $total_noscan=null;
            // $jumlah_jam_kerja = $jam_kerja  - $total_late ;
            $jumlah_jam_kerja = $total_jam_kerja  - $total_late ;

            $data = Jamkerjaid::find($data->id);

            $data->karyawan_id = $dt_karyawan_id;

            $data->date = buatTanggal($dt_date);
            // $data->last_data_date = $last_data_date;
            $data->last_data_date = $last_data_date->date;
            $data->jumlah_jam_kerja = $jumlah_jam_kerja;
            $data->jumlah_menit_lembur = $jumlah_menit_lembur + $total_langsungLembur;
            // $data->total_noscan = $total_noscan;
            $data->total_noscan = $n_noscan;
            $data->jumlah_jam_terlambat = $total_late == 0 ? null : $total_late;
            $data->first_in_late = $total_late_1 == 0 ? null : $total_late_1;
            $data->first_out_late = $total_late_2 == 0 ? null : $total_late_2;
            $data->second_in_late = $total_late_3 == 0 ? null : $total_late_3;
            $data->second_out_late = $total_late_4 == 0 ? null : $total_late_4;
            $data->total_hari_kerja = $jumlah_hari_kerja - $satpam_halfday;
            // $data->overtime_in_late = $total_late_5 == 0 ? null : $total_late_5;
            $data->save();
        }
        $current_date = Jamkerjaid::orderBy('date', 'desc')->first();
        // $this->periode = $current_date->date;

        $lock->build = false;
        $lock->save();

        $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built');
        $this->rebuild();
    }

    public function rebuild()
    {
        $datas = Jamkerjaid::with('karyawan')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->get();

        if ($datas->isEmpty()) {
            $this->dispatch('error', message: 'Data Tidak Ditemukan');
            return back();
        }

        $subtotal = 0;

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
            $payroll->gaji_pokok = $data->karyawan->gaji_pokok;
            $payroll->gaji_lembur = $data->karyawan->gaji_overtime;
            $payroll->gaji_bpjs = $data->karyawan->gaji_bpjs;
            $payroll->jkk = $data->karyawan->jkk;
            $payroll->jkm = $data->karyawan->jkm;
            $payroll->hari_kerja = $data->total_hari_kerja;
            $payroll->jam_kerja = $data->jumlah_jam_kerja;
            $payroll->jam_lembur = $data->jumlah_menit_lembur;
            if ($payroll->metode_penggajian == 'Perjam') {
                $payroll->subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + ($data->jumlah_menit_lembur / 60) * $data->karyawan->gaji_overtime;
            } else {
                if ($payroll->gaji_lembur == 0) {
                    $payroll->subtotal = $data->total_hari_kerja * ($data->karyawan->gaji_pokok / 26);
                } else {
                    $payroll->subtotal = $data->total_hari_kerja * ($data->karyawan->gaji_pokok / 26) + ($data->jumlah_menit_lembur / 60) * $data->karyawan->gaji_overtime;
                }
            }

            if($data->karyawan->potongan_JP==1) {
                if($data->karyawan->gaji_bpjs <= 9559600) {
                    $payroll->jp = $data->karyawan->gaji_bpjs * 0.01;
                } else {
                    $payroll->jp = 9559600 * 0.01;

                }

            } else {
                $payroll->jp = 0;
            }

            if($data->karyawan->potongan_JHT==1) {
                $payroll->jht = $data->karyawan->gaji_bpjs * 0.02;
            }
            else {
                $payroll->jht = 0;
            }

            if($data->karyawan->potongan_kesehatan==1) {
                $payroll->kesehatan = $data->karyawan->gaji_bpjs * 0.01;
            } else {
                $payroll->kesehatan = 0;
            }

            $payroll->pajak = 0;
            if($data->karyawan->potongan_JKK == 1){

                $payroll->jkk = 1;
            } else {

                $payroll->jkk = 0;
            }
            if($data->karyawan->potongan_JKM == 1){

                $payroll->jkm = 1;
            } else {

                $payroll->jkm = 0;
            }



            $payroll->date = $data->date;
            $payroll->total = $payroll->subtotal - $payroll->pajak - $payroll->jp - $payroll->jht - $payroll->kesehatan ;
            $payroll->save();
        }
        $this->dispatch('success', message: 'Data Payrol succesfully Rebuild');
    }


    // if($this->status==1) {
                //     $payroll2 = Payroll::where('status_karyawan','PKWT')
                //     ->orWhere('status_karyawan','PKWTT')
                //     ->orWhere('status_karyawan','Dirumahkan');

                // } elseif($this->status==2)  {
                //     $payroll2 = Payroll::where('status_karyawan','Resigned')
                //     ->orWhere('status_karyawan','Blacklist');

                // } else {
                //     $payroll2 = Payroll::orderBy($this->columnName, $this->direction);
                // }

                public function getPayrollQuery($statuses, $search = null, $placement = null)
        {
            return Payroll::query()
                ->whereIn('status_karyawan', $statuses)
                ->when($search, function ($query) use ($search) {
                    $query->where('id_karyawan', 'LIKE', '%' . trim($search) . '%')
                        ->orWhere('nama', 'LIKE', '%' . trim($search) . '%')
                        ->orWhere('jabatan', 'LIKE', '%' . trim($search) . '%')
                        ->orWhere('company', 'LIKE', '%' . trim($search) . '%')
                        ->orWhere('metode_penggajian', 'LIKE', '%' . trim($search) . '%');
                })
                ->when($placement, function ($query) use ($placement) {
                    $query->where('placement', $placement);
                })
                ->orderBy($this->columnName, $this->direction);
        }

    public function render()
    {
        $latest_payroll_id = Payroll::latest()->first();;

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
            $statuses =['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }

        switch ($this->selected_company) {
            case 0:
                $total = Payroll::sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search)->paginate($this->perpage);
                break;
            case 1:
                $total = Payroll::where('placement', 'YCME')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YCME')->paginate($this->perpage);
                break;
            case 6:
                $total = Payroll::where('company', 'YCME')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YCME')
                ->where('company','YCME')
                ->paginate($this->perpage);
                break;
            case 2:
                $total = Payroll::where('placement', 'YEV')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV')->paginate($this->perpage);
                break;
            case 7:
                $total = Payroll::where('company', 'YEV')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV')
                ->where('company','YEV')
                ->paginate($this->perpage);
                break;
            case 3:
                $total = Payroll::whereIn('placement', ['YIG', 'YSM'])->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, ['YIG'])
                ->orWhere('placement', 'YSM')
                ->paginate($this->perpage);
                break;

            case 8:
                $total = Payroll::where('company', 'YIG')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YIG')
                ->where('company','YIG')
                ->paginate($this->perpage);
                break;
            case 4:
                $total = Payroll::where('company', 'ASB')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'ASB')
                ->where('company','ASB')
                ->paginate($this->perpage);
                break;
            case 5:
                $total = Payroll::where('company', 'DPA')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'DPA')
                ->where('company','DPA')
                ->paginate($this->perpage);
                break;
            case 9:
                $total = Payroll::where('company', 'YSM')->sum('total');
                $payroll = $this->getPayrollQuery($statuses, $this->search, 'YSM')
                ->where('company','YSM')
                ->paginate($this->perpage);
                break;
            // default:
                // Handle default case
                // $total = Payroll::sum('total');
                // $payroll = $this->getPayrollQuery($statuses, $this->search)->paginate($this->perpage);
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
