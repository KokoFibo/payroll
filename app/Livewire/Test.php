<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;

class Test extends Component
{
    // public $saturday;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month;
    public $year;
    public $today;



    public function mount()
    {
        $this->today = now();
       
        $this->year = now()->year;
        $this->month = now()->month;
    }

    

    public function render()
    {

        $shift_pagi = Yfrekappresensi::whereMonth('date', $this->month)->whereYear('date', $this->year)->where('shift', 'Pagi')->count();
        $shift_malam = Yfrekappresensi::whereMonth('date', $this->month)->whereYear('date', $this->year)->where('shift', 'Malam')->count();
        $uniqueDates = Yfrekappresensi::whereMonth('date', $this->month)->whereYear('date', $this->year)->distinct()->pluck('date');
        $total = $shift_pagi + $shift_malam;
        dd( $shift_pagi/$total*100,  $shift_malam/$total*100, $uniqueDates);

        return view('livewire.test', compact(['data']));
    }
}
