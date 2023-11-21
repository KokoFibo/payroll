<?php

namespace App\Livewire;

use Carbon\Carbon;
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
