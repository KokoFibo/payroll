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



    // $data = DB::table('karyawans')
    //   ->join('jamkerjaids', 'karyawans.id_karyawan', '=', 'jamkerjaids.user_id')
    //   ->whereIn('karyawans.status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
    //   ->whereMonth('date', $bulan)
    //   ->whereYear('date', $tahun)
    //   ->where('jumlah_jam_terlambat', '>', 0)
    //   ->where('company', 'YSM')
    //   ->paginate(10);



    // return view('livewire.test', [
    //   'data' => $data,
    //   'bulan' => $bulan,
    //   'tahun' => $tahun,
    // ]);

    $data = DB::table('karyawans')
      ->join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->where('yfrekappresensis.date', '2024-04-09')
      ->whereIn('karyawans.placement', ['YEV', 'YEV SMOOT', 'YEV OFFERO', 'YEV SUNRA', 'YAM'])
      // ->where('karyawans.placement', 'YAM')
      // ->where('karyawans.gaji_overtime', '>', 0)
      ->paginate(10);
    return view('livewire.test', [
      'data' => $data
    ]);
  }
}
