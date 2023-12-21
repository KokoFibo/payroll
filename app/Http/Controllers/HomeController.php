<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payroll;
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
    $year = now()->year;
    $month = now()->month;

    $jumlah_total_karyawan = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

    $karyawan_baru_mtd = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereMonth('tanggal_bergabung', $month)
        ->whereYear('tanggal_bergabung', $year)
        ->count();

        $karyawan_resigned_mtd = Karyawan::where('status_karyawan', 'Resigned')
        ->whereMonth('tanggal_resigned', $month)
        ->whereYear('tanggal_resigned', $year)
        ->count();
        
        $karyawan_blacklist_mtd = Karyawan::where('status_karyawan','Blacklist')
        ->whereMonth('tanggal_blacklist', $month)
        ->whereYear('tanggal_blacklist', $year)
        ->count();
        
        $karyawan_aktif_mtd = Payroll::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->count();

    // Jumlah Karyawan
    $jumlah_ASB = Karyawan::where('company', 'ASB')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_DPA = Karyawan::where('company', 'DPA')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YCME = Karyawan::where('company', 'YCME')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YEV = Karyawan::where('company', 'YEV')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YIG = Karyawan::where('company', 'YIG')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_YSM = Karyawan::where('company', 'YSM')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Pabrik_1 = Karyawan::where('placement', 'YCME')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Pabrik_2 = Karyawan::where('placement', 'YEV')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Kantor = Karyawan::whereIn('placement', ['YSM','YIG'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_placement =  $jumlah_Pabrik_1 + $jumlah_Pabrik_2 + $jumlah_Kantor ;
    $jumlah_company =  $jumlah_ASB + $jumlah_DPA + $jumlah_YCME + $jumlah_YEV + $jumlah_YIG+  $jumlah_YSM;

    $jumlah_karyawanArr = [
        $jumlah_karyawan_pria, $jumlah_karyawan_wanita
    ];

    $jumlah_karyawan_labelArr = [
        'Pria 男', 'Wanita 女'
    ];


    $placementArr = [
        $jumlah_Pabrik_1, $jumlah_Pabrik_2,  $jumlah_Kantor
    ];
    $placementLabelArr = [
        'Pabrik 1 工厂1', 'Pabrik 2 工厂2',  'Kantor 办公室'
    ];
    $companyArr = [
        
        $jumlah_ASB,
        $jumlah_YCME,
        $jumlah_YEV,
        $jumlah_YSM,
        $jumlah_DPA,
        $jumlah_YIG,
    ];
    $companyLabelArr = [
        'ASB','YCME', 'YEV',  'YSM', 'DPA', 'YIG'
    ];
    
    // Department
    $department_BD = Karyawan::where('departemen', 'BD')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Engineering = Karyawan::where('departemen', 'Engineering')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_EXIM = Karyawan::where('departemen', 'EXIM')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Finance_Accounting = Karyawan::where('departemen', 'Finance Accounting')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_GA = Karyawan::where('departemen', 'GA')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Gudang = Karyawan::where('departemen', 'Gudang')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_HR = Karyawan::where('departemen', 'HR')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Legal = Karyawan::where('departemen', 'Legal')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Procurement = Karyawan::where('departemen', 'Procurement')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Produksi = Karyawan::where('departemen', 'Produksi')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Quality_Control = Karyawan::where('departemen', 'Quality Control')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $department_Board_of_Director = Karyawan::where('departemen', 'Board of Director')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

    // Jabatan
    $jabatan_Admin = Karyawan::where('jabatan', 'Admin')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Asisten_Direktur = Karyawan::where('jabatan', 'Asisten Direktur')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Asisten_Kepala = Karyawan::where('jabatan', 'Asisten Kepala')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Asisten_Manager = Karyawan::where('jabatan', 'Asisten Manager')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Asisten_Pengawas = Karyawan::where('jabatan', 'Asisten Pengawas')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Asisten_Wakil_Presiden = Karyawan::where('jabatan', 'Asisten Wakil Presiden')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Design_grafis = Karyawan::where('jabatan', 'Design grafis')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Director = Karyawan::where('jabatan', 'Director')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Kepala = Karyawan::where('jabatan', 'Kepala')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Manager = Karyawan::where('jabatan', 'Manager')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Pengawas = Karyawan::where('jabatan', 'Pengawas')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_President = Karyawan::where('jabatan', 'President')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Senior_staff = Karyawan::where('jabatan', 'Senior staff')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Staff = Karyawan::where('jabatan', 'Staff')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Supervisor = Karyawan::where('jabatan', 'Supervisor')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Vice_President = Karyawan::where('jabatan', 'Vice President')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Satpam = Karyawan::where('jabatan', 'Satpam')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Koki = Karyawan::where('jabatan', 'Koki')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Dapur_Kantor = Karyawan::where('jabatan', 'Dapur Kantor')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Dapur_Pabrik = Karyawan::where('jabatan', 'Dapur Pabrik')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_QC_Aging = Karyawan::where('jabatan', 'QC Aging')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jabatan_Driver = Karyawan::where('jabatan', 'Driver')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

       
       


        switch(auth()->user()->role) {
           case 0 : $role_name = 'BOD'; break;
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
                return view( 'dashboard', compact([ 'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita', 'jumlah_placement', 'jumlah_company', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV', 
        'jumlah_YIG', 'jumlah_YSM','jumlah_Kantor', 'jumlah_Pabrik_1', 'jumlah_Pabrik_2',
        'department_BD', 'department_Engineering', 'department_EXIM','department_Finance_Accounting', 'department_GA','department_Gudang',
        'department_HR', 'department_Legal', 'department_Procurement','department_Produksi', 'department_Quality_Control','department_Board_of_Director',
        'jabatan_Admin','jabatan_Asisten_Direktur','jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager','jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
        'jabatan_Design_grafis', 'jabatan_Director','jabatan_Kepala','jabatan_Manager','jabatan_Pengawas','jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
        'jabatan_Supervisor','jabatan_Vice_President','jabatan_Satpam','jabatan_Koki','jabatan_Dapur_Kantor', 
        'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver', 'placementArr', 'placementLabelArr', 'companyLabelArr', 'companyArr', 'jumlah_karyawan_labelArr', 'jumlah_karyawanArr',
        'karyawan_baru_mtd', 'karyawan_resigned_mtd', 'karyawan_blacklist_mtd', 'karyawan_aktif_mtd'

        ]) );
            }else {
                return view( 'user_dashboard' );
            }

        } else {
            $user->device = 1;
            $user->save();
            if ( auth()->user()->role == 5 || auth()->user()->role == 0) {
                $user->device = 1;
                $user->save();

                return view( 'dashboard', compact([ 'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita', 'jumlah_placement', 'jumlah_company', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV', 
        'jumlah_YIG', 'jumlah_YSM','jumlah_Kantor', 'jumlah_Pabrik_1', 'jumlah_Pabrik_2',
        'department_BD', 'department_Engineering', 'department_EXIM','department_Finance_Accounting', 'department_GA','department_Gudang',
        'department_HR', 'department_Legal', 'department_Procurement','department_Produksi', 'department_Quality_Control','department_Board_of_Director',
        'jabatan_Admin','jabatan_Asisten_Direktur','jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager','jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
        'jabatan_Design_grafis', 'jabatan_Director','jabatan_Kepala','jabatan_Manager','jabatan_Pengawas','jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
        'jabatan_Supervisor','jabatan_Vice_President','jabatan_Satpam','jabatan_Koki','jabatan_Dapur_Kantor',
        'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver',  'placementArr', 'placementLabelArr', 'companyLabelArr', 'companyArr',
        'jumlah_karyawan_labelArr', 'jumlah_karyawanArr',
        'karyawan_baru_mtd', 'karyawan_resigned_mtd', 'karyawan_blacklist_mtd', 'karyawan_aktif_mtd'
    ]));
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
