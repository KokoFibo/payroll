<?php

namespace App\Livewire;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Ter;
use App\Models\User;

use App\Models\Company;
use App\Models\Jabatan;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Placement;
use App\Models\Requester;
use App\Models\Department;
use App\Models\Jamkerjaid;
use App\Models\Rekapbackup;
use Livewire\WithPagination;
use App\Models\Applicantfile;
use App\Models\Bonuspotongan;
use App\Models\Liburnasional;
use Illuminate\Http\Response;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use App\Models\Personnelrequestform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class Test extends Component
{
  // public $saturday;
  use WithPagination;
  protected $paginationTheme = 'bootstrap';
  public $month;
  public $year;
  public $today;
  public $cx;
  public $test;


  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
  }


  public function deleteSTI()
  {
    // company sti = 101
    // placement sti = 104
    DB::transaction(function () {
      // Ambil semua karyawan dari placement_id = 104
      $karyawans = Karyawan::where('placement_id', 104)->get();

      foreach ($karyawans as $karyawan) {
        // Hapus user
        User::where('username', $karyawan->id_karyawan)->delete();

        // Hapus presensi bulan 9 / 2025
        Yfrekappresensi::where('user_id', $karyawan->id_karyawan)
          ->whereMonth('date', 9)
          ->whereYear('date', 2025)
          ->delete();
      }

      // Hapus karyawan terakhir
      Karyawan::where('placement_id', 104)->delete();
      $this->dispatch(
        'message',
        type: 'success',
        title: 'Semua data STI telah didelete'
      );
    });
  }

  public function render()
  {
    $year = 2025;
    $month = 10;

    $datas = Yfrekappresensi::whereMonth('date', $month)
      ->whereYear('date', $year)
      ->get();

    foreach ($datas as $data) {
      $is_sunday = is_sunday($data->date);
      $is_hari_libur_nasional = is_libur_nasional($data->date);

      if ($is_sunday || $is_hari_libur_nasional) {
        $data->total_hari_kerja_libur = 1;
        $data->total_jam_lembur_libur = $data->total_jam_lembur;
        $data->save();
      }
    }

    dd('done');

    return response()->json([
      'success' => true,
      'data' => $periods
    ]);




    return view('livewire.test');
  }
}
