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



  public function mount()
  {
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
  }


  public function delete()
  {
    $data = Yfrekappresensi::where('first_in', null)
      ->where('first_out', null)
      ->where('second_in', null)
      ->where('second_out', null)
      ->where('overtime_in', null)
      ->where('overtime_out', null)
      ->get();

    foreach ($data as $d) {
      $data_delete = Yfrekappresensi::find($d->id);
      $data_delete->delete();
    }

    $this->dispatch('success', message: 'Data Absensi Kosong semua sudah di delete');
  }
  public function render()
  {
    $data = Karyawan::whereMonth('tanggal_resigned', 12)->whereYear('tanggal_resigned', 2023)
      ->orWhereMonth('tanggal_blacklist', 12)
      ->orWhereYear('tanggal_blacklist', 2023)
      ->paginate(10);

    $dataResigned = Karyawan::whereMonth('tanggal_resigned', 12)->whereYear('tanggal_resigned', 2023)
      ->count();
    $dataBlacklist = Karyawan::whereMonth('tanggal_blacklist', 12)->whereYear('tanggal_blacklist', 2023)
      ->count();

    $data = Karyawan::whereNotNull('tanggal_resigned')
      ->whereMonth('tanggal_resigned', 12)->whereYear('tanggal_resigned', 2023)
      ->whereRaw('DATEDIFF(tanggal_resigned, tanggal_bergabung) < 90')
      ->paginate(10);

    // dd($dataResigned, $dataBlacklist);

    return view('livewire.test', compact(['data', 'dataResigned', 'dataBlacklist']));
  }
}
