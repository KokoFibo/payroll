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




  public function is_halfday($first_in, $first_out, $second_in, $second_out)
  {
    if ($first_in != null  && $first_out != null && $second_in == null && $second_out == null) {
      return 1;
    } else if ($first_in == null  && $first_out == null && $second_in != null && $second_out != null) {
      return 2;
    } else {
      return 0;
    }
  }

  public function is_libur_nasional($tanggal)
  {
    $data = Liburnasional::where('tanggal_mulai_hari_libur', $tanggal)->first();
    if ($data != null) return true;
    return false;
  }

  public function render()
  {
    // get_data_karyawan();
    // $this->dispatch('success', message: 'data karyawan queried');





    return view('livewire.test');
  }
}
