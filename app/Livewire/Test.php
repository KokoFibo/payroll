<?php

namespace App\Livewire;

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



  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
  }

  public $department_id, $placement_id;
  public $passBaru;
  // public $karyawan;

  public function proses()
  {
    $this->dispatch(
      'message',
      type: 'success',
      title: 'Gak ngapa ngapain',
    );
  }

  public function getDataKaryawanApi($apiUrl)
  {
    try {
      // Make the GET request
      $response = Http::get($apiUrl);

      // Check if the request was successful
      if ($response->successful()) {
        // Get the response data
        $data = $response->json();

        // Return or process the data
        return $data;
      } else {
        // Handle request errors
        return [
          'status' => 'error',
          'message' => 'Failed to fetch data from the API',
          'error' => $response->body()
        ];
      }
    } catch (\Exception $e) {
      // Handle exceptions
      return [
        'status' => 'error',
        'message' => 'An error occurred',
        'error' => $e->getMessage()
      ];
    }
  }

  public function getJabatan_id($jabatan)
  {
    if ($jabatan) {
      $data = Jabatan::where('nama_jabatan', $jabatan)->first();
      // dd($data->id);
      return $data->id;
    }
    return 0;
  }
  public function getCompany_id($company)
  {
    if ($company) {
      $data = Company::where('company_name', $company)->first();
      return $data->id;
    }
    return 0;
  }
  public function getPlacement_id($placement)
  {
    if ($placement) {
      if ($placement == 'YCME') return 102;
      if ($placement == 'YEV SMOOT') return 10;
      if ($placement == 'YEV OFFERO') return 9;
      if ($placement == 'YEV ELEKTRONIK') return 101;
      $data = Placement::where('placement_name', $placement)->first();
      return $data->id;
    }
    return 0;
  }

  public function getDepartment_id($department)
  {
    if ($department) {
      $data = Department::where('nama_department', $department)->first();
      return $data->id;
    }
    return 0;
  }







  public function render()
  {
    $filename = 'Applicants/Eum_qui_blanditiis_d_1994_03_13/ktp.png';
    $url = Storage::url($filename);
    // dd($url);
    // $data = Rekapbackup::whereBetween('date', ['2024-01-01', '2024-06-30'])->delete();
    // $data = Rekapbackup::whereBetween('date', ['2024-01-01', '2024-06-30'])->get();
    // $data = Rekapbackup::all();
    // $filename = "Applicants/Itaque_aut_minus_qui_1979_05_10/ktp-01.png";
    // dd(get_filename($filename));



    // $data = Rekapbackup::where('date', '>', '2023-12-31')->get();

    // dd($data);
    // C:\Users\kokon\OneDrive\Desktop\payroll\storage\app\public\Applicants\Ad_quibusdam_pariatu_1989_09_17
    // $folderPath = 'public/Applicants/Ad_quibusdam_pariatu_1989_09_17';
    // $zipFileName = 'testzip.zip';
    // return $this->downloadFolderAsZip($folderPath, $zipFileName);

    // $folder = 'public/Applicants/Ad_quibusdam_pariatu_1989_09_17';
    // $this->dispatchBrowserEvent('redirect', ['url' => route('download.zip', ['folder' => $folder])]);


    // dd('done');
    return view('livewire.test', [
      'url' => $url
    ]);
  }
}
