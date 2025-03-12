<?php

namespace App\Livewire;

use App\Models\Applicantfile;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Http\Response;

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
use App\Models\Bonuspotongan;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use App\Models\Personnelrequestform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
  public $fileCount = 0;


  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
    $this->countFiles();
  }

  public function countFiles()
  {
    $files = Storage::disk('s3')->allFiles('Applicants/Eiusmod_sint_exercit_1987_07_13/'); // Ambil semua file di folder "applicants"
    $this->fileCount = count($files);
  }

  public function render()
  {
    $allDataCount = Karyawan::count();
    $dataCount = Karyawan::whereNotNull('id_file_karyawan')->count();
    // dd($allDataCount, $dataCount);

    $data = Karyawan::whereNotNull('id_file_karyawan')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->get();
    $data_berisi = 0;
    foreach ($data as $d) {
      $applicanteFiles = Applicantfile::where('id_karyawan', $d->id_file_karyawan)->count();
      if ($applicanteFiles > 0) $data_berisi++;
    }
    // $data_berisi = Karyawan::whereNotNull('id_file_karyawan')
    //   ->whereHas('applicantFiles') // Langsung filter yang memiliki file
    //   ->count();
    dd($allDataCount, $dataCount, $data_berisi);

    return view('livewire.test', [
      'fileCount' => $this->fileCount,
    ]);
  }
}
