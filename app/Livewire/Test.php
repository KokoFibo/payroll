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

    $jam_kerja = 0;
    $first_in = '07:53:00';
    $second_out = '14:37:00';

    $t1 = strtotime($first_in);
    $t2 = strtotime($second_out);
    $t1 = strtotime(pembulatanJamOvertimeIn($first_in));
    $t2 = strtotime(pembulatanJamOvertimeOut($second_out));



    $diff = gmdate('H:i:s', $t2 - $t1);

    $diff = explode(':', $diff);
    $jam = (int) $diff[0];
    $menit = (int) $diff[1];

    if ($menit >= 45) {
      $jam = $jam + 1;
    } elseif ($menit < 45 && $menit > 15) {
      $jam = $jam + 0.5;
    } else {
      $jam;
    }
    $jam_kerja = $jam * 2;
    dd($jam_kerja);


    // return view('livewire.test', [
    //   'data' => $data
    // ]);
  }
}
