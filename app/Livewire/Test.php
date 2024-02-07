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

  public function render()
  {
    $date = '2024-02-06';
    // $data = Yfrekappresensi::where('date',  $date)
    //   ->where('first_in', '!=', null)
    //   ->where('first_out', '!=', null)
    //   ->where('second_out', null)
    //   ->where('second_out', null)
    //   ->paginate(10);
    $data = Yfrekappresensi::where('date',  $date)
      ->where('first_in',  null)
      ->where('first_out',  null)
      ->where('second_out', '!=', null)
      ->where('second_out', '!=', null)
      ->paginate(10);

    $id = 123833;
    $d = Yfrekappresensi::find($id);
    dd(is_halfday($d->first_in, $d->first_out, $d->second_in, $d->second_out));

    //   ->paginate(10);



    return view('livewire.test', [
      'data' => $data
    ]);
  }
}
