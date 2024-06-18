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

    $month = '10';
    $year = '2024';

    // $filename = 'CamScanner .18-06-2024. 08.47_11.zon (1).pdf';
    $filename = 'Cam.Scanner.pdf';


    dd(clear_dot($filename));
    // if (count($arr) > 2) {
    //   dd(last(arr));
    // } else {
    //   dd('dibawah 2');
    // }

    // $datas = Yfrekappresensi::where('date', '2024-05-14')->where('no_scan', 'No Scan')->paginate(10);
    // Blacklist
    // $datas = Payroll::join('karyawans', 'payrolls.id_karyawan', '=', 'karyawans.id_karyawan')
    //   ->whereIn('karyawans.status_karyawan', ['Resigned', 'PKWT', 'PKWTT'])
    //   ->whereMonth('payrolls.date', '05')
    //   ->whereYear('payrolls.date', '2024')
    //   ->whereMonth('karyawans.tanggal_bergabung', '05')
    //   ->whereYear('karyawans.tanggal_bergabung', '2024')
    //   ->where('payrolls.metode_penggajian', 'Perbulan')
    //   ->orderBy('karyawans.tanggal_resigned', 'desc')
    //   ->paginate(10);



    $datas = Karyawan::whereIn('placement', ['ASB', 'DPA',  'GAMA', 'WAS'])
      ->paginate(10);






    // dd(selisih_hari($date1, $date2));

    return view('livewire.test', [
      'datas' => $datas,
    ]);
  }
}
