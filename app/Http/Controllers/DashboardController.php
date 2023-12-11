<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jamkerjaid;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index()
    {
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


        


        
       

        // return view('dashboard', compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']));
        return view( 'dashboard', compact([ 'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita', 'jumlah_all', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV', 
        'jumlah_YIG', 'jumlah_YSM','jumlah_Kantor', 'jumlah_Pabrik_1', 'jumlah_Pabrik_2',
        'department_BD', 'department_Engineering', 'department_EXIM','department_Finance_Accounting', 'department_GA','department_Gudang',
        'department_HR', 'department_Legal', 'department_Procurement','department_Produksi', 'department_Quality_Control','department_Board_of_Director',
        'jabatan_Admin','jabatan_Asisten_Direktur','jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager','jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
        'jabatan_Design_grafis', 'jabatan_Director','jabatan_Kepala','jabatan_Manager','jabatan_Pengawas','jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
        'jabatan_Supervisor','jabatan_Vice_President','jabatan_Satpam','jabatan_Koki','jabatan_Dapur_Kantor', 
        'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver'
        ]) );

    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function makan () {

        return 'Makan';
    }


    public function mobile()
    {
        // ini hanya bisa di test di desktop tidak berlaku di user mobile
        $user_id = 1112;
        $month = 11;

        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0;

        $data = Yfrekappresensi::where('user_id', $user_id)->orderBy('date', 'desc')->simplePaginate(5);
        $data1 = Yfrekappresensi::where('user_id', $user_id)->get();

        foreach ($data1 as $d) {
            if ($d->no_scan == null) {
                $tgl = tgl_doang($d->date);
                $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);
                    $langsungLembur = langsungLembur( $d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);
                $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                $total_jam_lembur = $total_jam_lembur + $jam_lembur ;
                $total_keterlambatan = $total_keterlambatan + $terlambat;

                $total_hari_kerja++;
            }
        }

        return  redirect('/usermobile');
        
    }
}
