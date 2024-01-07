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


  public function delete()
  {
    $data = Karyawan::where('status_karyawan', $this->status_karyawan)
      ->when($this->status_karyawan == "Resigned", function ($query) {
        return $query->whereMonth('tanggal_resigned', $this->month)
          ->whereYear('tanggal_resigned', $this->year);
      })
      ->when($this->status_karyawan == "Blacklist", function ($query) {
        return $query->whereMonth('tanggal_blacklist', $this->month)
          ->whereYear('tanggal_blacklist', $this->year);
      })->get();
    $cx = 0;
    foreach ($data as $d) {
      if ($d->email != acakEmail($d->nama)) {
        $cx++;
        $data_hapus = User::where('username', $d->id_karyawan)->first();
        $data_karyawan = Karyawan::find($d->id);
        $data_id = User::find($data_hapus->id);

        // dd($data_karyawan->nama, $data_karyawan->id_karyawan, $data_id->name, $data_id->username);
        $data_id->email = acakEmail($d->nama);
        $data_id->password = acakPassword($d->nama);
        $data_karyawan->email = acakEmail($d->nama);
        $data_id->save();
        $data_karyawan->save();
      }
    }

    if ($cx > 0) {
      $this->dispatch('success', message: 'Data Absensi Kosong semua sudah di delete');
    }
  }

  public function UpdatedStatusKaryawan()
  {
    $this->render();
  }
  public function render()
  {
    $data = Karyawan::where('status_karyawan', $this->status_karyawan)
      ->when($this->status_karyawan == "Resigned", function ($query) {
        return $query->whereMonth('tanggal_resigned', $this->month)
          ->whereYear('tanggal_resigned', $this->year);
      })
      ->when($this->status_karyawan == "Blacklist", function ($query) {
        return $query->whereMonth('tanggal_blacklist', $this->month)
          ->whereYear('tanggal_blacklist', $this->year);
      })
      ->paginate(10);


    // $dataResigned = Karyawan::whereMonth('tanggal_resigned', 12)->whereYear('tanggal_resigned', 2023)
    //   ->count();
    // $dataBlacklist = Karyawan::whereMonth('tanggal_blacklist', 12)->whereYear('tanggal_blacklist', 2023)
    //   ->count();

    // $data = Karyawan::whereNotNull('tanggal_resigned')
    //   ->whereMonth('tanggal_resigned', 12)->whereYear('tanggal_resigned', 2023)
    //   ->whereRaw('DATEDIFF(tanggal_resigned, tanggal_bergabung) < 90')
    //   ->paginate(10);




    return view('livewire.test', compact(['data']));
  }
}
