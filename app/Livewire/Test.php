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
use App\Models\Liburnasional;
use App\Models\Personnelrequestform;
use App\Models\Requester;
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

  public function build()
  {
    build_payroll1('03', '2024');
  }
  public function shortJam($jam)
  {
    if ($jam != null) {
      $arrJam = explode(':', $jam);
      return $arrJam[0] . ':' . $arrJam[1];
    }
  }

  public function like()
  {
    $this->dispatch(
      'message',
      type: 'success',
      title: 'Data Karyawan Sudah di Save',
    );
  }




  public function render()
  {



    $requester_id = 7711;
    dd(is_same_approver($requester_id));





    $datas = Yfrekappresensi::where('late_history', 1)
      ->whereYear('date', 2024)
      ->whereMonth('date', 6)
      ->whereHas('karyawan', function ($query) {
        $query->where('department_id', 7);
      })
      ->with('karyawan')
      ->get();
    // ->paginate(10);
    // dd($datas->all());
    return view('livewire.test', [
      'datas' => $datas,
    ]);
  }
}
