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
    $data = Yfrekappresensi::whereMonth('date', '04')
      ->whereYear('date', '2024')->count();
    dd($data);



    $libur = Liburnasional::whereMonth('tanggal_mulai_hari_libur', '04')->whereYear('tanggal_mulai_hari_libur', '2024')->orderBy('tanggal_mulai_hari_libur', 'asc')->get('tanggal_mulai_hari_libur');

    $data = Payroll::join('karyawans', 'karyawans.id_karyawan', '=', 'payrolls.id_karyawan')
      ->whereMonth('payrolls.date', '04')->whereYear('payrolls.date', '2024')

      ->where('karyawans.status_karyawan', 'Resigned')
      ->whereMonth('tanggal_resigned', '04')
      ->where('denda_resigned', '>', 0)
      ->where('payrolls.metode_penggajian', 'Perbulan')
      ->get();
    // ->paginate(10);

    // dd($data->all());
    return view('livewire.test', [
      'data' => $data,
      'libur' => $libur
    ]);
  }
}
