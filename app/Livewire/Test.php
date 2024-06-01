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



  public function render()
  {
    $month = '04';
    $year = '2024';
    $startTime = '07:46';
    $endTime = '07:47';
    $first_out = '11:30:00';

    $filename = 'Applicants/Obcaecati_aliquam_cu_2000_11_21/217749.jpg';

    $currentDate = Carbon::now()->toDateString();



    // $datas = Yfrekappresensi::where('date', '2024-05-14')->where('no_scan_history', 'No Scan')->orWhere('no_scan', 'No Scan')->paginate(10);
    $datas = Yfrekappresensi::whereMonth('date', '03')->whereYear('date', '2024')->paginate(10);
    // $datas = Yfrekappresensi::where('date', '2024-05-14')->where('no_scan', 'No Scan')->paginate(10);



    return view('livewire.test', [
      'datas' => $datas,
    ]);
  }
}
