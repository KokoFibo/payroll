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

  public function manfaat_libur($month, $year, $libur, $user_id)
  {
    $data = Yfrekappresensi::where('user_id', $user_id)->whereMonth('date', $month)->whereYear('date', $year)->orderBy('date', 'asc')->first();
    $tgl_mulai_kerja = Carbon::parse($data->date)->day;
    $manfaat_libur = 0;
    foreach ($libur as $l) {
      $tgl_libur = Carbon::parse($l->tanggal_mulai_hari_libur)->day;
      if ($tgl_mulai_kerja < $tgl_libur) $manfaat_libur++;
    }
    return $manfaat_libur;
  }
  public function render()
  {
    $libur = Liburnasional::whereMonth('tanggal_mulai_hari_libur', '03')->whereYear('tanggal_mulai_hari_libur', '2024')->orderBy('tanggal_mulai_hari_libur', 'asc')->get('tanggal_mulai_hari_libur');

    dd(manfaat_libur('03', '2024', $libur, 58));

    // $data = Yfrekappresensi::where('user_id', 5649)->whereMonth('date', '02')->whereYear('date', '2024')->orderBy('date', 'asc')->first();
    // $tgl_mulai_kerja = Carbon::parse($data->date)->day;
    // $manfaat_libur = 0;
    // foreach ($libur as $l) {
    //   $tgl_libur = Carbon::parse($l->tanggal_mulai_hari_libur)->day;
    //   if ($tgl_mulai_kerja < $tgl_libur) $manfaat_libur++;
    // }
    // dd($manfaat_libur);


    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
      ->whereMonth('date', '03')
      ->whereYear('date', '2024')
      ->where('metode_penggajian', 'Perbulan')
      ->where('hari_kerja', '<', 21)
      ->orderBy('hari_kerja', 'asc')
      ->paginate(10);


    return view('livewire.test', [
      'payroll' => $payroll
    ]);
  }
}
