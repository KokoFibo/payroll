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




  public function render()
  {

    dd('aman');
    // $result = deleteUserByKaryawanAPI(13553);
    // if ($result['status']) {
    //   dd('berhasil');
    // } else {
    //   dd('gagal');
    // }
    // $this->cleanEmail();
    $data = Karyawan::where(function ($q) {
      $q->where('email', 'like', '%resigned_%')
        ->orWhere('email', 'like', '%email_kosong_%')
        ->orWhere('email', 'like', '%blacklist_%');
    })
      ->when($this->search, function ($q) {
        $q->where('email', 'like', '%' . $this->search . '%')
          ->orWhere('nama', 'like', '%' . $this->search . '%');
      })
      ->paginate(10);


    return view('livewire.test', [
      'data' => $data
    ]);
  }
}
