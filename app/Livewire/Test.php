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
    adjustSalary();
    // $this->dispatch('success', message: 'Data Gaji Karyawan Sudah di Sesuaikan');

    return view('livewire.test');
  }
}
