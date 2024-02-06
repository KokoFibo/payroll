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
  public $etnis;



  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
    $this->status_karyawan = "Resigned";
    $this->etnis = "lainnya";
  }

  public function delete($id)
  {
    $data = Karyawan::find($id);
    $data->iuran_locker = 0;
    $data->save();
    $this->dispatch('success', message: 'Iuran Locker ' . $data->nama . ' sudah di kosongkan');
  }





  public function change()
  {

    $data = Karyawan::where('etnis', $this->etnis)->get();

    foreach ($data as $d) {
      $data_karyawan = Karyawan::find($d->id);
      $data_karyawan->etnis = 'Lainnya';
      $data_karyawan->save();
      $this->dispatch('success', message: 'data berhasil dirubah');
    }
  }

  public function render()
  {
    $this->resetPage();

    $data = Karyawan::where('etnis', $this->etnis)->paginate(10);

    return view('livewire.test', [
      'data' => $data
    ]);
  }
}
