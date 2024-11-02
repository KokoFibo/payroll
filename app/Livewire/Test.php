<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Ter;
use App\Models\User;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use App\Models\Department;
use App\Models\Liburnasional;
use App\Models\Personnelrequestform;
use App\Models\Placement;
use App\Models\Rekapbackup;
use App\Models\Requester;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

  public $department_id, $placement_id;
  public $passBaru;
  // public $karyawan;

  public function proses()
  {
    $this->dispatch(
      'message',
      type: 'success',
      title: 'Gak ngapa ngapain',
    );
  }

  public function render()
  {
    $second_out = '23:31';
    $perJam = 60;
    if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59')) {
      $t1 = strtotime('00:00:00');
      $t2 = strtotime($second_out);

      $diff = gmdate('H:i:s', $t1 - $t2);
      $late = ceil(hoursToMinutes($diff) / $perJam);
    }
    dd($late);





    return view('livewire.test');
  }
}
