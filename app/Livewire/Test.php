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




  public function render()
  {

    // $data = Karyawan::where('etnis', 'China')->whereIn('status_karyawan', ['PKWT', 'PKWTT'])->get();
    // dd($data);
    dd('aman');
    $year = 2025;
    $month = 11;
    // $user_id = 3084;
    // $user_id = 3251;
    $data = Yfrekappresensi::where('date', '2025-11-09')
      ->get();
    // Lihat hasilnya
    foreach ($data as $d) {
      $d->total_jam_kerja = $d->total_jam_kerja / 2;
      $d->total_jam_kerja_libur = $d->total_jam_kerja;
      $d->total_jam_lembur = $d->total_jam_lembur / 2;
      $d->total_jam_lembur_libur = $d->total_jam_lembur;
      $d->total_hari_kerja_libur = 0;
      $d->total_hari_kerja = 0;
      if ($d->total_jam_kerja < 0) {
        $d->total_jam_kerja = 0;
        $d->total_jam_kerja_libur = 0;
      }
      if ($d->total_jam_lembur < 0) {
        $d->total_jam_lembur = 0;
        $d->total_jam_lembur_libur = 0;
      }
      $d->save();
    }

    dd('done');




    // ambil list tanggal libur nasional
    $libur_nasional = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
      ->whereYear('tanggal_mulai_hari_libur', $year)
      ->pluck('tanggal_mulai_hari_libur')
      ->toArray();

    // $data = Yfrekappresensi::where('user_id', $user_id)
    $data = Yfrekappresensi::whereMonth('date', $month)
      ->whereYear('date', $year)
      ->where(function ($q) use ($libur_nasional) {
        $q->whereRaw('DAYOFWEEK(DATE(date)) = 1')  // hari minggu
          ->orWhereIn(DB::raw('DATE(date)'), $libur_nasional); // libur nasional
      })
      ->get();

    dd($data);

    // dd($libur_nasional);

    return view('livewire.test');
  }
}
