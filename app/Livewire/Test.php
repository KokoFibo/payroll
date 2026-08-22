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
use Illuminate\Support\Str;

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



  public $search = '';

  public function cleanEmail()
  {
    $data = Karyawan::where(function ($q) {
      $q->where('email', 'like', '%resigned_%')
        ->orWhere('email', 'like', '%blacklist_%');
    })->get();

    foreach ($data as $item) {

      // 🔥 hapus semua "resigned_" & "blacklist_" di mana saja
      $newEmail = preg_replace('/(resigned_|blacklist_)/', '', $item->email);

      // bersihkan spasi (kalau ada)
      $newEmail = trim($newEmail);

      // 🔴 kalau kosong → generate email dummy unik
      if (empty($newEmail)) {
        do {
          $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
          $newEmail = "email_kosong_{$random}@email.com";
        } while (Karyawan::where('email', $newEmail)->exists());
      }

      // 🔐 cek duplicate (hindari update kalau sudah ada)
      if (Karyawan::where('email', $newEmail)->exists()) {
        continue;
      }

      // ✅ update
      $item->update([
        'email' => $newEmail
      ]);
    }

    return "Selesai bersihkan email";
  }


  public function generate()
  {


    $tgl = '2026-06-27';

    $karyawanTidakHadir = Karyawan::where('placement_id', 8)
      ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
      ->where(function ($q) use ($tgl) {
        $q->whereNull('tanggal_resigned')
          ->orWhereDate('tanggal_resigned', '>=', $tgl);
      })
      ->where(function ($q) use ($tgl) {
        $q->whereNull('tanggal_blacklist')
          ->orWhereDate('tanggal_blacklist', '>=', $tgl);
      })
      ->whereNotExists(function ($q) use ($tgl) {
        $q->selectRaw(1)
          ->from('yfrekappresensis')
          ->whereColumn('yfrekappresensis.user_id', 'karyawans.id_karyawan')
          ->whereDate('yfrekappresensis.date', $tgl);
      })
      ->get();

    foreach ($karyawanTidakHadir as $kh) {

      $placement_id = $kh->placement_id;

      $first_in = '08:00';
      $first_out = null;
      $second_in = null;
      $second_out = '15:00';
      $overtime_in = null;
      $overtime_out = null;
      $late = null;
      $no_scan = null;
      $shift = '';


      $gagal_scan = 0;


      Yfrekappresensi::create([
        'shift_malam' => $tambahan_shift_malam ?? 0,
        'user_id' => $kh->id_karyawan,
        'karyawan_id' => $kh->id,
        // 'name' => $name,
        'date' => $tgl,
        'first_in' => $first_in,
        'first_out' => $first_out,
        'second_in' => $second_in,
        'second_out' => $second_out,
        'overtime_in' => $overtime_in,
        'overtime_out' => $overtime_out,
        'total_jam_kerja' => 6,
        'total_hari_kerja' => 1,
        'total_jam_lembur' => null,
        'total_jam_kerja_libur' => null,

        'total_hari_kerja_libur' => null,
        'total_jam_lembur_libur' => null,

        'shift' => 'Pagi',
        'late' => null,
        'no_scan' => null,
        'no_scan_history' => null,
        'late_history' => null,
      ]);
    }
  }






  public function render()
  {
    $month = 8;
    $year = 2026;

    // Ambil karyawan yang memiliki tanggal_update pada bulan/tahun yang ditentukan
    $data = Karyawan::whereNotNull('tanggal_update')
      ->whereMonth('tanggal_update', $month)
      ->whereYear('tanggal_update', $year)
      ->get();

    // Payroll bulan sebelumnya
    $previousMonth = $month - 1;
    $previousYear = $year;

    // Jika bulan = Januari, payroll sebelumnya adalah Desember tahun sebelumnya
    if ($previousMonth == 0) {
      $previousMonth = 12;
      $previousYear = $year - 1;
    }

    $payroll = Payroll::whereMonth('date', $previousMonth)
      ->whereYear('date', $previousYear)
      ->get()
      ->keyBy('id_karyawan');

    $changes = collect();

    foreach ($data as $karyawan) {

      // Cari payroll karyawan berdasarkan id_karyawan
      $previousPayroll = $payroll->get($karyawan->id_karyawan);

      // Kalau tidak ada payroll bulan sebelumnya, skip
      if (!$previousPayroll) {
        continue;
      }

      // Nilai sekarang dari tabel karyawan
      $gajiSekarang = (float) ($karyawan->gaji_tetap ?? 0);
      $lemburSekarang = (float) ($karyawan->gaji_overtime ?? 0);
      $bonusSekarang = (float) ($karyawan->bonus ?? 0);

      // Nilai payroll bulan sebelumnya
      $gajiSebelumnya = (float) ($previousPayroll->gaji_pokok ?? 0);
      $lemburSebelumnya = (float) ($previousPayroll->gaji_lembur ?? 0);
      $bonusSebelumnya = (float) ($previousPayroll->bonus1x ?? 0);

      // Cek perubahan
      $gajiChanged = $gajiSekarang != $gajiSebelumnya;
      $lemburChanged = $lemburSekarang != $lemburSebelumnya;
      $bonusChanged = $bonusSekarang != $bonusSebelumnya;

      // Hanya tampilkan jika ada perubahan
      if ($gajiChanged || $lemburChanged || $bonusChanged) {

        $changes->push([
          'karyawan' => $karyawan,
          'payroll' => $previousPayroll,

          'gaji_sekarang' => $gajiSekarang,
          'gaji_sebelumnya' => $gajiSebelumnya,

          'lembur_sekarang' => $lemburSekarang,
          'lembur_sebelumnya' => $lemburSebelumnya,

          'bonus_sekarang' => $bonusSekarang,
          'bonus_sebelumnya' => $bonusSebelumnya,

          'gaji_changed' => $gajiChanged,
          'lembur_changed' => $lemburChanged,
          'bonus_changed' => $bonusChanged,

          'total_sekarang' =>
          $gajiSekarang +
            $lemburSekarang +
            $bonusSekarang,

          'total_sebelumnya' =>
          $gajiSebelumnya +
            $lemburSebelumnya +
            $bonusSebelumnya,
        ]);
      }
    }

    return view('livewire.test', [
      'data' => $changes,
      'month' => $month,
      'year' => $year,
      'previousMonth' => $previousMonth,
      'previousYear' => $previousYear,
    ]);
  }
}
