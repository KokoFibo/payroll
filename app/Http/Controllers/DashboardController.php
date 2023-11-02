<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index () {
        $jumlah_total_karyawan = Karyawan::count();
        $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->count();
        $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->count();
        return view('dashboard',compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']) );
    }
}

