<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Ter;
use App\Models\User;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use App\Models\Liburnasional;
use App\Models\Personnelrequestform;
use App\Models\Requester;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
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

  public function build()
  {
    build_payroll1('03', '2024');
  }
  public function shortJam($jam)
  {
    if ($jam != null) {
      $arrJam = explode(':', $jam);
      return $arrJam[0] . ':' . $arrJam[1];
    }
  }

  public function like()
  {
    $this->dispatch(
      'message',
      type: 'success',
      title: 'Data Karyawan Sudah di Save',
    );
  }


  public function delete_karyawan_company()
  {
    $users_count = User::count();
    // dd($users_count);
    $datas_count = Karyawan::whereIn('company_id', [7, 5, 3, 6, 4])->count();
    $data = Karyawan::whereIn('company_id', [7, 5, 3, 6, 4])->get();
    // delete user
    $cx = 0;
    foreach ($data as $d) {
      $user = User::where('username', $d->id_karyawan)->first();
      if ($user) {
        if ($user->role == 1) {

          $user->delete();
          $cx++;
        }
      }
    }
    // dd($cx);
    // delete data
    $delete_data = Karyawan::whereIn('company_id', [7, 5, 3, 6, 4])->delete();

    $this->dispatch(
      'message',
      type: 'success',
      title: 'Data Berhasil di delete : ' . $datas_count . ' data, ' . $cx . ' users',
    );
  }

  public function delete_diatas_4jt()
  {
    $users_count = User::count();
    // dd($users_count);
    $datas_count = Karyawan::where('gaji_pokok', '>=', 4000000)->count();
    $data = Karyawan::where('gaji_pokok', '>=', 4000000)->get();
    // delete user
    $cx = 0;
    foreach ($data as $d) {
      $user = User::where('username', $d->id_karyawan)->first();
      if ($user) {
        if ($user->role == 1) {

          $user->delete();
          $cx++;
        }
      }
    }
    // dd($cx);
    // delete data
    $delete_data = Karyawan::where('gaji_pokok', '>=', 4000000)->delete();

    $this->dispatch(
      'message',
      type: 'success',
      title: 'Data Berhasil di delete : ' . $datas_count . ' data, ' . $cx . ' users',
    );
  }
  public function delete_dibawah_4jt()
  {
    $users_count = User::count();
    // dd($users_count);
    $datas_count = Karyawan::where('gaji_pokok', '<', 4000000)
      // ['YAM', 'YIG', 'YCME', 'YSM', 'YEV']
      ->whereNotIn('company_id', [7, 5, 3, 6, 4])
      ->count();
    $data = Karyawan::where('gaji_pokok', '<', 4000000)->whereNotIn('company_id', [7, 5, 3, 6, 4])->get();
    // delete user
    $cx = 0;
    foreach ($data as $d) {
      $user = User::where('username', $d->id_karyawan)->first();
      if ($user) {
        if ($user->role == 1) {
          $user->delete();
          $cx++;
        }
      }
    }
    // dd($cx);

    // delete data
    // $delete_data = Karyawan::where('gaji_pokok', '<', 4000000)->delete();
    Karyawan::where('gaji_pokok', '<', 4000000)->whereNotIn('company_id', [7, 5, 3, 6, 4])->delete();
    $this->dispatch(
      'message',
      type: 'success',
      title: 'Data Berhasil di delete : ' . $datas_count . ' data, ' . $cx . ' users',
    );
  }
  public function getDataUser($id)
  {
    // Find the user by ID
    $user = User::where('username', $id)->first();

    // Check if the user exists
    if (!$user) {
      return response()->json([
        'message' => 'User not found'
      ], 404);
    }

    // Return user data
    return response()->json($user, 200);
  }

  public function render()
  {

    // $datas = Karyawan::whereNot('gaji_pokok', '<', 4000000)->count();
    // $datas = Karyawan::whereNot('gaji_pokok', '>=', 4000000)->count();
    // $datas = Karyawan::count();
    // dd($datas);
    // dd($this->getDataUser(8194));
    $id = 8192;
    $respKaryawan = Http::get('https://payroll.accel365.id/api/getkaryawan/' . $id);
    $dataKaryawan = $respKaryawan->json();

    $respUser = Http::get('https://payroll.accel365.id/api/getuser/' . $id);
    $dataUser = $respUser->json();

    if ($respKaryawan->successful() && $respUser->successful()) {

      // dd('berhasil');
      $karyawan = Karyawan::create($dataKaryawan);
      $user = User::create($dataUser);
      return response()->json([
        'message' => 'Karyawan created successfully!'

      ], 201);
    } else {
      return response()->json(['error' => 'Data karyawan ini tidak dalam database'], 500);
    }



    // $data = Karyawan::where('id_karyawan', 8195)->first();
    // $new_data = $data->replicate();
    // $new_data->id_karyawan = 9999;
    // $new_data->save();

    return view('livewire.test');
  }
}
