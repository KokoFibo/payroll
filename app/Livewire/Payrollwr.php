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
    public $selected_company;
    public $search;
    public $perpage = 10;

    public function rebuild () {
        $datas = Jamkerjaid::with('karyawan')->get();
        $subtotal = 0;
        Payroll::truncate();

        foreach( $datas as $data) {

            $payroll = new Payroll;
            $payroll->karyawan_id = $data->karyawan->id;
            $payroll->jamkerjaid_id = $data->id;
            if($payroll->karyawan->metode_penggajian == 'Perjam') {
                $payroll->subtotal = ($data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok/198)) + ($data->jumlah_menit_lembur/60 * $data->karyawan->gaji_overtime) ;
            } else {
                if($payroll->karyawan->gaji_overtime == 0) {
                    $payroll->subtotal = $data->total_hari_kerja * ($data->karyawan->gaji_pokok/26);
                } else {
                    $payroll->subtotal = $data->total_hari_kerja * ($data->karyawan->gaji_pokok/26) + ($data->jumlah_menit_lembur/60 * $data->karyawan->gaji_overtime) ;
                }
            }

            $payroll->pajak = 0;
            $payroll->jht = 0;
            $payroll->jp = 0;
            $payroll->jkk = 0;
            $payroll->jkm = 0;
            $payroll->kesehatan = 0;
            $payroll->total = $payroll->subtotal + $payroll->pajak;
            $payroll->save();
        }
        $this->dispatch( 'success', message: 'Data Payrol succesfully Rebuild' );

    }
    public function render()
    {

        switch($this->selected_company) {
            case 0 :
            $total = Payroll::sum('total');
            $payroll = Payroll::with('karyawan')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 1 :
            $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','placement', 'YCME')
            ->sum('total');
            $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','placement', 'YCME')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 2 :
            $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','placement', 'YEV')
            ->sum('total');
            $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','placement', 'YEV')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 3 :
            $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','placement', 'YIG')
            ->orWhereRelation('karyawan','placement', 'YSM')
            ->sum('total');
            $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YIG')
            ->orWhereRelation('karyawan','company', 'YSM')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 4 :
            $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'ASB')
            ->sum('total');
            $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'ASB')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 5 :
                $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'DPA')
            ->sum('total');
            $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'DPA')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 6 :
                $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YCME')
            ->sum('total');
            $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YCME')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 7 :
                $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YEV')
            ->sum('total');
                $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YEV')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 8 :
                $total = Payroll::with('karyawan')
                ->whereRelation('karyawan','company', 'YIG')
                ->sum('total');
                $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YIG')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            case 9 :
                $total = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YSM')
            ->sum('total');
                $payroll = Payroll::with('karyawan')
            ->whereRelation('karyawan','company', 'YSM')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;

            default :
            $total = Payroll::sum('total');
            $payroll = Payroll::with('karyawan')
            ->when($this->search, function ($query) {
                $query
                ->whereRelation('karyawan','id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','nama', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','jabatan', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','company', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhereRelation('karyawan','metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->orderBy('id', 'asc')->paginate($this->perpage);break;
        }

        // $payroll1 = $payroll


        $tgl = Payroll::select('updated_at')->first();
        if($tgl != null) {
            $last_build = Carbon::parse($tgl->updated_at)->diffForHumans();
        } else {
            $last_build = 0;

        }

        $data_kosong = Jamkerjaid::count();


        return view('livewire.payrollwr', compact(['payroll', 'total',  'last_build', 'data_kosong']));
    }
}
