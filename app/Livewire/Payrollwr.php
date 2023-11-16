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
    public $month;
    public $year;
    public $columnName = 'id_karyawan';
    public $direction = 'asc';

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

        Payroll::with('karyawan')->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->truncate();

        foreach ($datas as $data) {
            $payroll = new Payroll();
            $payroll->karyawan_id = $data->karyawan->id;
            $payroll->jamkerjaid_id = $data->id;
            if ($payroll->karyawan->metode_penggajian == 'Perjam') {
                $payroll->subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + ($data->jumlah_menit_lembur / 60) * $data->karyawan->gaji_overtime;
            } else {
                if ($payroll->karyawan->gaji_overtime == 0) {
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
    public function render()
    {
        $total = 0;
    $query = Payroll::with('karyawan')
    ->whereMonth('date', $this->month)
    ->whereYear('date', $this->year)->get();
    // foreach($query as $q) {
    //     // dd($q->karyawan->nama);
    //     dd($q->karyawan->id);

    // }

switch ($this->selected_company) {
    case 1:
        $query->whereRelation('karyawan', 'placement', 'YCME');
        break;

    case 2:
        $query->whereRelation('karyawan', 'placement', 'YEV');
        break;

    case 3:
        $query->whereRelation('karyawan', 'placement', 'YIG')
            ->whereRelation('karyawan', 'placement', 'YSM');
        break;

    case 4:
        $query->whereRelation('karyawan', 'company', 'ASB');
        break;

    case 5:
        $query->whereRelation('karyawan', 'company', 'DPA');
        break;

    case 6:
        $query->whereRelation('karyawan', 'company', 'YCME');
        break;

    case 7:
        $query->whereRelation('karyawan', 'company', 'YEV');
        break;

    case 8:
        $query->whereRelation('karyawan', 'company', 'YIG');
        break;

    case 9:
        $query->whereRelation('karyawan', 'company', 'YSM');
        break;
}

$total = $query->sum('total');

$payroll = $query->when($this->search, function ($query) {
        $query
            ->whereRelation('karyawan', 'id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
            ->orWhereRelation('karyawan', 'nama', 'LIKE', '%' . trim($this->search) . '%')
            ->orWhereRelation('karyawan', 'jabatan', 'LIKE', '%' . trim($this->search) . '%')
            ->orWhereRelation('karyawan', 'company', 'LIKE', '%' . trim($this->search) . '%')
            ->orWhereRelation('karyawan', 'metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
    })
    // ->orderBy('id', 'asc')
    ->paginate($this->perpage);


        // $payroll1 = $payroll

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
