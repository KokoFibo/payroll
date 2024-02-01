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

  public function delete($id)
  {
    $data = Karyawan::find($id);
    $data->iuran_locker = 0;
    $data->save();
    $this->dispatch('success', message: 'Iuran Locker ' . $data->nama . ' sudah di kosongkan');
  }
  public function render()
  {
    $data = Karyawan::where('iuran_locker', '>', 0)
      ->where('tanggal_bergabung', '<', Carbon::now()->subDays(365))->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
      ->paginate(10);
    return view('livewire.test', compact('data'));
  }
}
