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
    dd('aman');



    $data = Yfrekappresensi::where('user_id', 985)
      ->get(['date'])
      ->groupBy(function ($item) {
        return date('Y-m', strtotime($item->date));
      })
      ->map(function ($group, $key) {
        return [
          'year_month' => $key,
          'year' => date('Y', strtotime($group->first()->date)),
          'month' => date('m', strtotime($group->first()->date)),
          'records_count' => $group->count(),
          'dates' => $group->pluck('date')->toArray()
        ];
      })
      ->values();


    // {
    //   "month_year": "November 2025",
    //   "month": "11",
    //   "year": "2025"
    // },
    // {
    //   "month_year": "December 2025",
    //   "month": "12",
    //   "year": "2025"
    // },
    // {
    //   "month_year": "January 2026",
    //   "month": "1",
    //   "year": "2026"
    // },

    $data = Yfrekappresensi::where('user_id', 985)
      ->select('date')
      ->get()
      ->map(function ($item) {
        $date = strtotime($item->date);
        return [
          'month_year' => date('F Y', $date),
          'month' => date('n', $date), // 'n' untuk month tanpa leading zero
          'year' => date('Y', $date)
        ];
      })
      ->unique('month_year')
      ->values()
      ->toArray();


    dd($data);
    return response()->json([
      'success' => true,
      'data' => $data
    ]);

    $data = Yfrekappresensi::where('id_karyawan', 985)->whereMonth('date', $month)
      ->whereYear('date', $year)->get();
    dd($data);

    $data = Yfrekappresensi::join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
      ->select(
        'yfrekappresensis.*',
        'karyawans.metode_penggajian',
        'karyawans.nama'
      )
      ->whereMonth('yfrekappresensis.date', $month)
      ->whereYear('yfrekappresensis.date', $year)
      ->where('karyawans.metode_penggajian', 'Perbulan')
      // ->where('total_jam_kerja_libur', '<', 4)
      ->where('total_jam_kerja', '<=', 7)
      ->where('total_hari_kerja', 0)
      ->get();
    $total = 0;
    $total = $data->count();
    // dd($data);
    return view('livewire.test', [
      'data' => $data,
      'total' => $total
    ]);
  }
}
