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
  public $status_karyawan;
  public $cx;



  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
    $this->status_karyawan = "Resigned";
  }


  public function render()
  {
    $bd = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'BD')
      ->whereDate('date', '2024-01-13')->count();

    $engineering = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'Engineering')
      ->whereDate('date', '2024-01-13')->count();

    $exim = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'EXIM')
      ->whereDate('date', '2024-01-13')->count();

    $finance_accounting = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'Finance Accounting')
      ->whereDate('date', '2024-01-13')->count();

    $ga = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'GA')
      ->whereDate('date', '2024-01-13')->count();

    $gudang = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'Gudang')
      ->whereDate('date', '2024-01-13')->count();

    $hr = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'HR')
      ->whereDate('date', '2024-01-13')->count();

    $legal = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'Legal')
      ->whereDate('date', '2024-01-13')->count();

    $procurement = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'Procurement')
      ->whereDate('date', '2024-01-13')->count();

    $produksi = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'Produksi')
      ->whereDate('date', '2024-01-13')->count();

    $quality_control = Karyawan::join('Yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
      ->select('karyawans.*', 'yfrekappresensis.*')
      ->where('departemen', 'Quality Control')
      ->whereDate('date', '2024-01-13')->count();

    $total_presensi_by_departemen = $bd + $engineering + $exim + $finance_accounting + $ga + $gudang + $hr + $legal +
      $procurement + $produksi + $quality_control;






    return view('livewire.test', compact([
      'bd', 'engineering', 'exim', 'finance_accounting', 'ga', 'gudang', 'hr', 'legal',
      'procurement', 'produksi', 'quality_control', 'total_presensi_by_departemen'
    ]));
  }
}
