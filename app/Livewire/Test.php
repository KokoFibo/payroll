<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;

class Test extends Component
{
    // public $saturday;
    use WithPagination;
    public $month = 12;
    public $year = 2023;
    public function render()
    {
 $jumlah_libur_nasional = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $this->month)->whereYear('tanggal_mulai_hari_libur', $this->year)->sum('jumlah_hari_libur');
     

       


        return view('livewire.test');
    }
}
