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

    //   ini beneran di user mobile
        $jumlah_total_karyawan = Karyawan::count();
        $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->count();
        $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->count();

        $agent = new Agent();
        $desktop = $agent->isDesktop();
        $user = User::find( auth()->user()->id );
        // $user = 258;
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
            $user_id = auth()->user()->username;

            // $user_id = 258;
        $month = 11;
        // $total_hari_kerja = Yfrekappresensi::whereMonth('date', '=', 11)
        //     ->distinct('date')
        //     ->count();

        $total_hari_kerja = 0;


        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0 ;

        $dataArr = [];
        $data = Yfrekappresensi::where('user_id', $user_id)
            ->orderBy('date', 'desc')
            ->get();

        foreach ($data as $d) {
            if ($d->no_scan == null) {
                $tgl = tgl_doang($d->date);
                $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);
                // if($d->shift == 'Malam' || is_jabatan_khusus($d->user_id)) {
                    $langsungLembur = langsungLembur( $d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);
                // }
                $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                $total_jam_lembur = $total_jam_lembur + $jam_lembur;
                $total_keterlambatan = $total_keterlambatan + $terlambat;

                $dataArr[] = [
                    'tgl' => $tgl,
                    'jam_kerja' => $jam_kerja,
                    'terlambat' => $terlambat,
                    'jam_lembur' => $jam_lembur,
                ];
                $total_hari_kerja++;
            }
        }

        return view('mobile')->with([
            'dataArr' => $dataArr,
            'total_hari_kerja' => $total_hari_kerja,
            'total_jam_kerja' => $total_jam_kerja,
            'total_jam_lembur' => $total_jam_lembur,
            'total_keterlambatan' => $total_keterlambatan,
        ]);


        }
    }
    }
