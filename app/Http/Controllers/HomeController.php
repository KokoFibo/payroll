<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\Redirect;

$agent = new Agent();

class HomeController extends Controller {
    /**
    * Create a new controller instance.
    *
    * @return void
    */

    public function __construct() {
        $this->middleware( 'auth' );
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */



    public function index() {

        // $data = Yfrekappresensi::where('user_id', 5222 )->where('no_scan',null)->get();
        // return view( 'dashboardMobile', compact(['data']) );
        $jumlah_total_karyawan = Karyawan::count();
        $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->count();
        $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->count();

        $agent = new Agent();
        $desktop = $agent->isDesktop();
        $user = User::find( auth()->user()->id );
        if ( $desktop ) {
            $user->device = 1;
            $user->save();
            if(auth()->user()->role >1) {

                return view( 'dashboard', compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']) );
            }else {

                return view( 'user_dashboard', compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']) );
            }

        } else {
            $user->device = 1;
            $user->save();
            if ( auth()->user()->role == 4 ) {
                $user->device = 1;
                $user->save();

                return view( 'dashboard', compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']) );
            }
            $user->device = 0;
            $user->save();
            // return view( 'dashboardMobile1' );
            return Redirect()->to('/mobile');

        }
    }
    }
