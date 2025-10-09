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
    $month = 9;


    // untuk ganti hari kerja jadi 0 jika hari minggu atau libur nasional untuk status perbulan
    $data = Payroll::whereYear('date', $year)
      ->whereMonth('date', $month)
      ->where('metode_penggajian', 'Perbulan')
      ->get();

    foreach ($data as $d) {
      $presensis = Yfrekappresensi::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->where('user_id', $d->id_karyawan)
        ->get();

      foreach ($presensis as $p) {
        $isSunday = is_sunday($p->date);
        $isLibur = is_libur_nasional($p->date);
        $isFriday = is_friday($p->date);
        $isSaturday = is_saturday($p->date);

        // Default tidak ada perubahan
        $p->total_hari_kerja = $p->total_hari_kerja ?? 0;
        $p->total_jam_kerja_libur = $p->total_jam_kerja_libur ?? 0;

        // Jika hari Minggu
        if ($isSunday) {
          $p->total_hari_kerja = 2;
          $p->total_jam_kerja_libur = 16;
        }

        // Jika hari libur nasional
        if ($isLibur) {
          if ($isFriday) {
            $p->total_jam_kerja_libur = 15;
          } elseif ($isSaturday) {
            $p->total_jam_kerja_libur = 12;
          }
        }

        // Simpan hanya sekali
        $p->save();
      }
    }

    dd('done21');
    // end untuk ganti hari kerja jadi 0 jika hari minggu atau libur nasional untuk status perbulan

    $payrolls = Payroll::whereYear('date', $year)
      ->whereMonth('date', $month)
      ->get();

    $liburDates = Liburnasional::whereYear('tanggal_mulai_hari_libur', $year)
      ->whereMonth('tanggal_mulai_hari_libur', $month)
      ->pluck('tanggal_mulai_hari_libur')
      ->toArray();

    $beda = [];
    $jumlahSama = 0;

    foreach ($payrolls as $payroll) {
      // ambil semua presensi milik karyawan
      $presensis = Yfrekappresensi::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->where('user_id', $payroll->id_karyawan)
        ->get();

      if ($presensis->isEmpty()) {
        continue;
      }

      // hitung total hari kerja (exclude Minggu & tanggal libur)
      $totalHariKerja = $presensis->reject(function ($p) use ($liburDates) {
        return is_sunday($p->date) || in_array($p->date, $liburDates);
      })->count();

      // hitung total jam kerja dan lembur
      $totalJamKerja = $presensis->sum('total_jam_kerja');
      $totalJamLembur = $presensis->sum('total_jam_lembur');

      // cek perbedaan data
      if (
        $payroll->hari_kerja != $totalHariKerja ||
        $payroll->jam_kerja != $totalJamKerja ||
        $payroll->jam_lembur != $totalJamLembur
      ) {
        $beda[] = [
          'id_karyawan' => $payroll->id_karyawan,
          'hari_kerja_payroll' => $payroll->hari_kerja,
          'hari_kerja_presensi' => $totalHariKerja,
          'jam_kerja_payroll' => $payroll->jam_kerja,
          'jam_kerja_presensi' => $totalJamKerja,
          'jam_lembur_payroll' => $payroll->jam_lembur,
          'jam_lembur_presensi' => $totalJamLembur,
        ];
      } else {
        $jumlahSama++;
      }
    }

    return view('livewire.test', [
      'beda' => $beda,
      'jumlah_sama' => $jumlahSama,
    ]);
  }
}
