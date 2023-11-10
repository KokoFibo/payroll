<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;

class DashboardController extends Controller
{
    public function index () {
        $jumlah_total_karyawan = Karyawan::count();
        $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->count();
        $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->count();
        return view('dashboard',compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']) );
    }
    public function mobile () {
        $user_id = 5221;
        $data = Yfrekappresensi::where('user_id', $user_id)->orderBy('date', 'desc')->get();
        return view('mobile')->with([
            'data' => $data
        ]);
    }
}

