<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\Redirect;
use Spatie\Activitylog\Contracts\Activity;

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

    $jumlah_total_karyawan = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->count();
    $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->count();

    // Jumlah Karyawan
    $jumlah_all =  $jumlah_total_karyawan;
    $jumlah_ASB = Karyawan::where('company', 'ASB')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_DPA = Karyawan::where('company', 'DPA')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YCME = Karyawan::where('company', 'YCME')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YEV = Karyawan::where('company', 'YEV')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YIG = Karyawan::where('company', 'YIG')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YSM = Karyawan::where('company', 'YSM')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Pabrik_1 = Karyawan::where('placement', 'YCME')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Pabrik_2 = Karyawan::where('placement', 'YEV')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Kantor = Karyawan::whereIn('placement', ['YSM','YIG'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

    // Department
    $department_BD = Karyawan::where('departemen', 'BD')->count();
    $department_Engineering = Karyawan::where('departemen', 'Engineering')->count();
    $department_EXIM = Karyawan::where('departemen', 'EXIM')->count();
    $department_Finance_Accounting = Karyawan::where('departemen', 'Finance Accounting')->count();
    $department_GA = Karyawan::where('departemen', 'GA')->count();
    $department_Gudang = Karyawan::where('departemen', 'Gudang')->count();
    $department_HR = Karyawan::where('departemen', 'HR')->count();
    $department_Legal = Karyawan::where('departemen', 'Legal')->count();
    $department_Procurement = Karyawan::where('departemen', 'Procurement')->count();
    $department_Produksi = Karyawan::where('departemen', 'Produksi')->count();
    $department_Quality_Control = Karyawan::where('departemen', 'Quality Control')->count();
    $department_Board_of_Director = Karyawan::where('departemen', 'Board of Director')->count();

    // Jabatan
    $jabatan_Admin = Karyawan::where('jabatan', 'Admin')->count();
    $jabatan_Asisten_Direktur = Karyawan::where('jabatan', 'Asisten Direktur')->count();
    $jabatan_Asisten_Kepala = Karyawan::where('jabatan', 'Asisten Kepala')->count();
    $jabatan_Asisten_Manager = Karyawan::where('jabatan', 'Asisten Manager')->count();
    $jabatan_Asisten_Pengawas = Karyawan::where('jabatan', 'Asisten Pengawas')->count();
    $jabatan_Asisten_Wakil_Presiden = Karyawan::where('jabatan', 'Asisten Wakil Presiden')->count();
    $jabatan_Design_grafis = Karyawan::where('jabatan', 'Design grafis')->count();
    $jabatan_Director = Karyawan::where('jabatan', 'Director')->count();
    $jabatan_Kepala = Karyawan::where('jabatan', 'Kepala')->count();
    $jabatan_Manager = Karyawan::where('jabatan', 'Manager')->count();
    $jabatan_Pengawas = Karyawan::where('jabatan', 'Pengawas')->count();
    $jabatan_President = Karyawan::where('jabatan', 'President')->count();
    $jabatan_Senior_staff = Karyawan::where('jabatan', 'Senior staff')->count();
    $jabatan_Staff = Karyawan::where('jabatan', 'Staff')->count();
    $jabatan_Supervisor = Karyawan::where('jabatan', 'Supervisor')->count();
    $jabatan_Vice_President = Karyawan::where('jabatan', 'Vice President')->count();
    $jabatan_Satpam = Karyawan::where('jabatan', 'Satpam')->count();
    $jabatan_Koki = Karyawan::where('jabatan', 'Koki')->count();
    $jabatan_Dapur_Kantor = Karyawan::where('jabatan', 'Dapur Kantor')->count();
    $jabatan_Dapur_Pabrik = Karyawan::where('jabatan', 'Dapur Pabrik')->count();
    $jabatan_QC_Aging = Karyawan::where('jabatan', 'QC Aging')->count();
    $jabatan_Driver = Karyawan::where('jabatan', 'Driver')->count();

       
       


        switch(auth()->user()->role) {
           case 1 : $role_name = 'User'; break;
           case 2 : $role_name = 'Admin'; break;
           case 3 : $role_name = 'Senior Admin'; break;
           case 4 : $role_name = 'Super Admin'; break;
           case 5 : $role_name = 'Developer'; break;
        }
        activity()->log( auth()->user()->name .', '.$role_name.', ID : ' . auth()->user()->username . ' Login');


        $agent = new Agent();
        $desktop = $agent->isDesktop();
        $user = User::find( auth()->user()->id );
        // $user = 1112;
        if ( $desktop ) {
            $user->device = 1;
            $user->save();
            if(auth()->user()->role >1) {
                return view( 'dashboard', compact([ 'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita', 'jumlah_all', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV', 
        'jumlah_YIG', 'jumlah_YSM','jumlah_Kantor', 'jumlah_Pabrik_1', 'jumlah_Pabrik_2',
        'department_BD', 'department_Engineering', 'department_EXIM','department_Finance_Accounting', 'department_GA','department_Gudang',
        'department_HR', 'department_Legal', 'department_Procurement','department_Produksi', 'department_Quality_Control','department_Board_of_Director',
        'jabatan_Admin','jabatan_Asisten_Direktur','jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager','jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
        'jabatan_Design_grafis', 'jabatan_Director','jabatan_Kepala','jabatan_Manager','jabatan_Pengawas','jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
        'jabatan_Supervisor','jabatan_Vice_President','jabatan_Satpam','jabatan_Koki','jabatan_Dapur_Kantor',
        'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver'

        ]) );
            }else {
                return view( 'user_dashboard' );
            }

        } else {
            $user->device = 1;
            $user->save();
            if ( auth()->user()->role == 5 ) {
                $user->device = 1;
                $user->save();

                return view( 'dashboard', compact([ 'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita', 'jumlah_all', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV', 
        'jumlah_YIG', 'jumlah_YSM','jumlah_Kantor', 'jumlah_Pabrik_1', 'jumlah_Pabrik_2',
        'department_BD', 'department_Engineering', 'department_EXIM','department_Finance_Accounting', 'department_GA','department_Gudang',
        'department_HR', 'department_Legal', 'department_Procurement','department_Produksi', 'department_Quality_Control','department_Yifang',
        'jabatan_Admin','jabatan_Asisten_Direktur','jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager','jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
        'jabatan_Design_grafis', 'jabatan_Director','jabatan_Kepala','jabatan_Manager','jabatan_Pengawas','jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
        'jabatan_Supervisor','jabatan_Vice_President','jabatan_Satpam','jabatan_Koki','jabatan_Dapur_Kantor',
        'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver'

        ]) );
            }
            $user->device = 0;
            $user->save();
            // return view( 'dashboardMobile1' );
            $user_id = auth()->user()->username;

            // $user_id = 1112;
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
        return  redirect('/usermobile');


        // return view('mobile')->with([
        //     'dataArr' => $dataArr,
        //     'total_hari_kerja' => $total_hari_kerja,
        //     'total_jam_kerja' => $total_jam_kerja,
        //     'total_jam_lembur' => $total_jam_lembur,
        //     'total_keterlambatan' => $total_keterlambatan,
        // ]);


        }
    }
    }
