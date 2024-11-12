<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Ter;
use App\Models\User;
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

  public function render()
  {
    // $apiUrl = "https://payroll.yifang.co.id/api/getkaryawan/9427";
    $id = 19425;
    dd($this->getDataKaryawanApi("https://payroll.yifang.co.id/api/getkaryawan/" . $id));



    return view('livewire.test');
  }
}
