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
use Illuminate\Support\Facades\DB;

class Test extends Component
{
  // public $saturday;
  use WithPagination;
  protected $paginationTheme = 'bootstrap';
  public $month;
  public $year;
  public $today;
  public $cx;

  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
  }

  public function checkOvertimeInLate($overtime_in, $shift)
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


  public function render()
  {


    // return view('livewire.test', [
    //   'data' => $data
    // ]);
  }
}
