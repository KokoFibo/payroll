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
    $datakaryawan = Karyawan::select('id_karyawan')->get()->toArray();
    $datauser = User::select('username')->get()->toArray();

    // Extract values from arrays
    $karyawanIds = array_column($datakaryawan, 'id_karyawan');
    $usernames = array_column($datauser, 'username');

    // Find elements in $karyawanIds that are not in $usernames
    $missingKaryawanIds = array_diff($karyawanIds, $usernames);

    // Output the result
    // dd($missingKaryawanIds);












    return view('livewire.test', compact('missingKaryawanIds'));
  }
}
