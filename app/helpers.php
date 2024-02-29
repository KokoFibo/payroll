<?php

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Payroll;
use App\Models\Karyawan;
use App\Models\Tambahan;
use Illuminate\Support\Str;
use App\Models\Dashboarddata;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\Hash;

function is_puasa($tgl)
{
    // Start date dan end date = tanggal mulai dan akhir puasa
    $start_date = '2022-02-20';
    $end_date = '2022-02-25';
    if ($tgl >= $start_date && $tgl <= $end_date) return true;
    return false;
}

function get_data_karyawan()
{
    $year = now()->year;
    $month = now()->month;

    $jumlah_total_karyawan = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

    $jumlah_karyawan_baru_hari_ini = Karyawan::where('tanggal_bergabung', today())->count();
    $jumlah_karyawan_Resigned_hari_ini = Karyawan::where('tanggal_resigned', today())->count();
    $jumlah_karyawan_blacklist_hari_ini = Karyawan::where('tanggal_blacklist', today())->count();

    $karyawan_baru_mtd = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereMonth('tanggal_bergabung', $month)
        ->whereYear('tanggal_bergabung', $year)
        ->count();

    $karyawan_resigned_mtd = Karyawan::where('status_karyawan', 'Resigned')
        ->whereMonth('tanggal_resigned', $month)
        ->whereYear('tanggal_resigned', $year)
        ->count();

    $karyawan_blacklist_mtd = Karyawan::where('status_karyawan', 'Blacklist')
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
    $jumlah_YAM = Karyawan::where('company', 'YAM')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_GAMA = Karyawan::where('company', 'GAMA')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_WAS = Karyawan::where('company', 'WAS')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Pabrik_1 = Karyawan::where('placement', 'YCME')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Pabrik_2 = Karyawan::where('placement', 'YEV')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_Kantor = Karyawan::whereIn('placement', ['YSM', 'YIG'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
    $jumlah_placement =  $jumlah_Pabrik_1 + $jumlah_Pabrik_2 + $jumlah_Kantor;
    $jumlah_company =  $jumlah_ASB + $jumlah_DPA + $jumlah_YCME + $jumlah_YEV + $jumlah_YIG +  $jumlah_YSM + $jumlah_YAM;


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

    $countLatestHadir = Yfrekappresensi::where('date', Yfrekappresensi::max('date'))->count();
    // $latestDate = Yfrekappresensi::where('date', Yfrekappresensi::max('date'))->first();

    // $dataCountLatestHadir = [$countLatestHadir, $jumlah_total_karyawan - $countLatestHadir];

    //  $average7Hari = ratarata (7);
    // $average7Hari = [ratarata(7), $jumlah_total_karyawan - ratarata(7)];

    //  rata-rata 30 hari
    // $average30Hari = [ratarata(30), $jumlah_total_karyawan - ratarata(30)];
    //  Presensi by Depertemen
    $bd = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'BD')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $engineering = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'Engineering')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $exim = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'EXIM')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $finance_accounting = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'Finance Accounting')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $ga = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'GA')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $gudang = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'Gudang')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $hr = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'HR')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $legal = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'Legal')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $procurement = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'Procurement')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $produksi = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'Produksi')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $quality_control = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
        ->select('karyawans.*', 'yfrekappresensis.*')
        ->where('departemen', 'Quality Control')
        ->where('date', Yfrekappresensi::max('date'))->count();

    $total_presensi_by_departemen = $bd + $engineering + $exim + $finance_accounting + $ga + $gudang + $hr + $legal +
        $procurement + $produksi + $quality_control;

    $shift_pagi = Yfrekappresensi::whereMonth('date', now()->month)->whereYear('date', now()->year)->where('shift', 'Pagi')->count();
    $shift_malam = Yfrekappresensi::whereMonth('date', now()->month)->whereYear('date', now()->year)->where('shift', 'Malam')->count();
    $total = $shift_pagi + $shift_malam;

    Dashboarddata::query()->truncate();
    $data = new Dashboarddata;

    $data->jumlah_total_karyawan = $jumlah_total_karyawan;
    $data->jumlah_karyawan_pria = $jumlah_karyawan_pria;
    $data->jumlah_karyawan_wanita = $jumlah_karyawan_wanita;
    $data->karyawan_baru_mtd = $karyawan_baru_mtd;
    $data->karyawan_resigned_mtd = $karyawan_resigned_mtd;
    $data->karyawan_blacklist_mtd = $karyawan_blacklist_mtd;
    $data->karyawan_aktif_mtd = $karyawan_aktif_mtd;
    $data->jumlah_karyawan_baru_hari_ini = $jumlah_karyawan_baru_hari_ini;
    $data->jumlah_karyawan_Resigned_hari_ini = $jumlah_karyawan_Resigned_hari_ini;
    $data->jumlah_karyawan_blacklist_hari_ini = $jumlah_karyawan_blacklist_hari_ini;
    $data->jumlah_ASB = $jumlah_ASB;
    $data->jumlah_DPA = $jumlah_DPA;
    $data->jumlah_YCME = $jumlah_YCME;
    $data->jumlah_YEV = $jumlah_YEV;
    $data->jumlah_YIG = $jumlah_YIG;
    $data->jumlah_YSM = $jumlah_YSM;
    $data->jumlah_YAM = $jumlah_YAM;
    $data->jumlah_Pabrik_1 = $jumlah_Pabrik_1;
    $data->jumlah_Pabrik_2 = $jumlah_Pabrik_2;
    $data->jumlah_Kantor = $jumlah_Kantor;
    $data->jumlah_placement = $jumlah_placement;
    $data->jumlah_company = $jumlah_company;
    $data->department_BD = $department_BD;
    $data->department_Engineering = $department_Engineering;
    $data->department_EXIM = $department_EXIM;
    $data->department_Finance_Accounting = $department_Finance_Accounting;
    $data->department_GA = $department_GA;
    $data->department_Gudang = $department_Gudang;
    $data->department_HR = $department_HR;
    $data->department_Legal = $department_Legal;
    $data->department_Procurement = $department_Procurement;
    $data->department_Produksi = $department_Produksi;
    $data->department_Quality_Control = $department_Quality_Control;
    $data->department_Board_of_Director = $department_Board_of_Director;
    $data->jabatan_Admin = $jabatan_Admin;
    $data->jabatan_Asisten_Direktur = $jabatan_Asisten_Direktur;
    $data->jabatan_Asisten_Kepala = $jabatan_Asisten_Kepala;
    $data->jabatan_Asisten_Manager = $jabatan_Asisten_Manager;
    $data->jabatan_Asisten_Pengawas = $jabatan_Asisten_Pengawas;
    $data->jabatan_Asisten_Wakil_Presiden = $jabatan_Asisten_Wakil_Presiden;
    $data->jabatan_Design_grafis = $jabatan_Design_grafis;
    $data->jabatan_Director = $jabatan_Director;
    $data->jabatan_Kepala = $jabatan_Kepala;
    $data->jabatan_Manager = $jabatan_Manager;
    $data->jabatan_Pengawas = $jabatan_Pengawas;
    $data->jabatan_President = $jabatan_President;
    $data->jabatan_Senior_staff = $jabatan_Senior_staff;
    $data->jabatan_Staff = $jabatan_Staff;
    $data->jabatan_Supervisor = $jabatan_Supervisor;
    $data->jabatan_Vice_President = $jabatan_Vice_President;
    $data->jabatan_Satpam = $jabatan_Satpam;
    $data->jabatan_Koki = $jabatan_Koki;
    $data->jabatan_Dapur_Kantor = $jabatan_Dapur_Kantor;
    $data->jabatan_Dapur_Pabrik = $jabatan_Dapur_Pabrik;
    $data->jabatan_QC_Aging = $jabatan_QC_Aging;
    $data->jabatan_Driver = $jabatan_Driver;
    $data->countLatestHadir = $countLatestHadir;
    // $data->latestDate = $latestDate;
    // $data->dataCountLatestHadir = $dataCountLatestHadir;
    // $data->average7Hari = $average7Hari;
    // $data->average30Hari = $average30Hari;
    $data->bd = $bd;
    $data->engineering = $engineering;
    $data->exim = $exim;
    $data->finance_accounting = $finance_accounting;
    $data->ga = $ga;
    $data->gudang = $gudang;
    $data->hr = $hr;
    $data->legal = $legal;
    $data->procurement = $procurement;
    $data->produksi = $produksi;
    $data->quality_control = $quality_control;
    $data->total_presensi_by_departemen = $total_presensi_by_departemen;
    $data->shift_pagi = $shift_pagi;
    $data->shift_malam = $shift_malam;
    $data->save();
}

function is_libur_nasional($tanggal)
{
    $data = Liburnasional::where('tanggal_mulai_hari_libur', $tanggal)->first();
    if ($data != null) return true;
    return false;
}

function is_halfday($first_in, $first_out, $second_in, $second_out)
{
    if ($first_in != null  && $first_out != null && $second_in == null && $second_out == null) {
        return 1;
    } else if ($first_in == null  && $first_out == null && $second_in != null && $second_out != null) {
        return 2;
    } else {
        return 0;
    }
}

function jumlah_libur_nasional($month, $year)
{
    return Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->sum('jumlah_hari_libur');
}

function countWorkingDays($month, $year, $ignore)
{
    // $ignore = 0-6 = sunday - saturday, ex. countDays(2024, 1, array(0,6))-> exclude saturday and sunday
    $count = 0;
    $counter = mktime(0, 0, 0, $month, 1, $year);
    while (date("n", $counter) == $month) {
        if (in_array(date("w", $counter), $ignore) == false) {
            $count++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return $count;
}


function acakPassword($nama)
{
    $arrNama = explode(' ', $nama);
    return Hash::make($arrNama[0] . '_out_' . $arrNama[0]);
}
function acakEmail($nama, $id)
{
    $arrNama = explode(' ', $nama);

    return $arrNama[0]  . '_' . $arrNama[0] . '_' . $id . '@' . $arrNama[0] . '.'
        .     'out';
}
function is_data_locked()
{
    $data = Lock::find(1);
    if ($data->data == 1) {
        return true;
    } else {
        return false;
    }
}

function adjustSalary()
{
    $ninetyDaysAgo = Carbon::now()->subDays(90);
    $hundredTwentyDaysAgo = Carbon::now()->subDays(120);
    $hundredFiftyDaysAgo = Carbon::now()->subDays(150);
    $hundredEigtyDaysAgo = Carbon::now()->subDays(180);
    $twoHundredTenDaysAgo = Carbon::now()->subDays(210);
    $twoHundredFortyDaysAgo = Carbon::now()->subDays(240);

    // 90 <= 119
    $data = Karyawan::where('tanggal_bergabung', '<=', $ninetyDaysAgo)->where('tanggal_bergabung', '>', $hundredTwentyDaysAgo)
        ->where('gaji_pokok', '<', 2100000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('departemen', ['EXIM', 'GA'])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2100000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }



    // 120 < 149
    $data = Karyawan::where('tanggal_bergabung', '<=', $hundredTwentyDaysAgo)->where('tanggal_bergabung', '>', $hundredFiftyDaysAgo)
        ->where('gaji_pokok', '<', 2200000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('departemen', ['EXIM', 'GA'])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2200000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }


    // 150 < 179
    $data = Karyawan::where('tanggal_bergabung', '<=', $hundredFiftyDaysAgo)->where('tanggal_bergabung', '>', $hundredEigtyDaysAgo)
        ->where('gaji_pokok', '<', 2300000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('departemen', ['EXIM', 'GA'])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2300000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }

    // 180 < 209
    $data = Karyawan::where('tanggal_bergabung', '<=', $hundredEigtyDaysAgo)->where('tanggal_bergabung', '>', $twoHundredTenDaysAgo)
        ->where('gaji_pokok', '<', 2400000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('departemen', ['EXIM', 'GA'])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2400000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }

    // 210 < 240
    $data = Karyawan::where('tanggal_bergabung', '<=', $twoHundredTenDaysAgo)
        // ->where('tanggal_bergabung', '>', $twoHundredFortyDaysAgo)
        ->where('gaji_pokok', '<', 2500000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('departemen', ['EXIM', 'GA'])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2500000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }
}

function role_name($role)
{
    switch ($role) {
        case 0:
            $roleName = "Board of Director";
            break;
        case 1:
            $roleName = "User";
            break;
        case 2:
            $roleName = "Admin";
            break;
        case 3:
            $roleName = "Senior Admin";
            break;
        case 4:
            $roleName = "Super Admin";
            break;
    }
    return $roleName;
}

function ratarata($ndays)
{
    $events = 0;
    $avg = 0;
    $i = 0;
    $cx = 0;
    while ($cx < $ndays) {
        $today = Carbon::today();
        $events = Yfrekappresensi::whereDate('date',  $today->subDays($i + 1))->count();
        if ($events != 0) {
            $avg += $events;
            $data[] = $events;
            $cx++;
        }
        $i++;
    }
    return round($avg / $ndays);
}

function is_40_days($month, $year)
{
    $tgl = $year . '-' . $month . '-01';

    $fortyDaysAgo = Carbon::now()->subDays(40);
    $yourDate = Carbon::parse($tgl);
    if ($yourDate->lessThan($fortyDaysAgo)) {
        // Date is more than 35 days ago
        // Your logic here
        // echo 'Date is more than 35 days ago.';
        return true;
    } else {
        // Date is 35 days ago or less
        // Your logic here
        // echo 'Date is 35 days ago or less.';
        return false;
    }
}

function lama_bekerja($tgl_mulai_kerja, $tgl_resigned)
{
    $tgl_mulai_kerja = Carbon::parse($tgl_mulai_kerja);
    $tgl_resigned = Carbon::parse($tgl_resigned);
    $days = $tgl_resigned->diffinDays($tgl_mulai_kerja);
    return $days;
}

function JumlahHariCuti($user_id, $tanggal_resigned, $month, $year)
{
    $dataResignedArr = [];
    $cutiArr = [];
    $data = Karyawan::where('id_karyawan', $user_id)
        ->where('tanggal_resigned', '!=', null)
        ->whereMonth('tanggal_resigned', $month)
        ->whereYear('tanggal_resigned', $year)
        ->first();

    $haricuti = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->orderBy('tanggal_mulai_hari_libur', 'asc')
        ->get();

    foreach ($haricuti as $h) {
        if ($h->jumlah_hari_libur == 1) {
            $cutiArr[] = [
                'tgl' => $h->tanggal_mulai_hari_libur,
            ];
        } else {
            $i = 0;
            for ($i = 0; $i < $h->jumlah_hari_libur; $i++) {
                $cutiArr[] = [
                    'tgl' => Carbon::parse($h->tanggal_mulai_hari_libur)
                        ->addDay($i)
                        ->format('Y-m-d'),
                ];
            }
        }
    }

    // foreach ($data as $d) {
    $startDate = Carbon::parse($data->tanggal_bergabung);
    $endDate = Carbon::parse($tanggal_resigned);
    $days = $endDate->diffinDays($startDate);
    $cuti = 0;

    foreach ($cutiArr as $c) {
        $resigned = Carbon::createFromFormat('Y-m-d', $tanggal_resigned);
        $libur = Carbon::createFromFormat('Y-m-d', $c['tgl']);
        if ($resigned->gt($libur)) {
            $cuti++;
        }
    }

    return $cuti;
}

function jumlah_hari_resign($tanggal_bergabung, $tanggal_resigned)
{
    $bergabung = Carbon::parse($tanggal_bergabung);
    $resigned = Carbon::parse($tanggal_resigned);
    if ($tanggal_resigned == null) {
        return null;
    } else {
        return $resigned->diffInDays($tanggal_bergabung);
    }
}

function lama_resign($tanggal_bergabung, $tanggal_resigned, $tanggal_blacklist)
{
    // dd($tanggal_bergabung, $tanggal_resigned, $tanggal_blacklist);
    $bergabung = Carbon::parse($tanggal_bergabung);
    $resigned = Carbon::parse($tanggal_resigned);
    $blacklist = Carbon::parse($tanggal_blacklist);

    if ($tanggal_resigned == null && $tanggal_blacklist != null) {
        return $blacklist->diffInDays($tanggal_bergabung);
    } elseif ($tanggal_resigned != null && $tanggal_blacklist == null) {
        return $resigned->diffInDays($tanggal_bergabung);
    } else {
        return null;
    }
}

function convert_numeric($number)
{
    $number = trim($number, "Rp\u{A0}");
    $arrNumber = explode('.', $number);
    $numberString = '';
    for ($i = 0; $i < count($arrNumber); $i++) {
        $numberString = $numberString . $arrNumber[$i];
    }
    return (int) $numberString;
}

function month_year($tgl)
{
    $date = Carbon::createFromFormat('Y-m-d', $tgl);
    $monthName = $date->format('F');
    $year = $date->format('Y');
    return $monthName . ' ' . $year;
}

function check_bulan($tgl, $bulan, $tahun)
{
    $arrTgl = explode('-', $tgl);
    if ($arrTgl[0] == $tahun && $arrTgl[1] == $bulan) {
        return true;
    } else {
        return false;
    }
}

function nama_file_excel($nama_file, $month, $year)
{
    $arrNamaFile = explode('.', $nama_file);
    return $arrNamaFile[0] . '_' . monthName($month) . '_' . $year . '.' . $arrNamaFile[1];
}

function ada_tambahan($id)
{
    $data = Tambahan::where('user_id', $id)->first();
    if ($data == null) {
        return false;
    } else {
        return true;
    }
}

function monthName($tgl)
{
    if ($tgl < 1 || $tgl > 12) {
        $tgl = now()->month;
    }
    switch ($tgl) {
        case 1:
            $monthNama = 'Januari';
            break;
        case 2:
            $monthNama = 'Februari';
            break;
        case 3:
            $monthNama = 'Maret';
            break;
        case 4:
            $monthNama = 'April';
            break;
        case 5:
            $monthNama = 'Mei';
            break;
        case 6:
            $monthNama = 'Juni';
            break;
        case 7:
            $monthNama = 'Juli';
            break;
        case 8:
            $monthNama = 'Agustus';
            break;
        case 9:
            $monthNama = 'September';
            break;
        case 10:
            $monthNama = 'Oktober';
            break;
        case 11:
            $monthNama = 'November';
            break;
        case 12:
            $monthNama = 'Desember';
            break;
    }
    return $monthNama;
}

function absen_kosong($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out)
{
    if ($first_in == '' && $first_out == '' && $second_in == '' && $second_out == '' && $overtime_in == '' && $overtime_out == '') {
        // if($first_in == null && $first_out ==  null && $second_in ==  null && $second_out ==  null && $overtime_in ==  null && $overtime_out == null) {
        return true;
    } else {
        return false;
    }
}

function is_sunday($tgl)
{
    if ($tgl) {
        return Carbon::parse($tgl)->isSunday();
    }
}

function clear_locks()
{
    $lock = Lock::find(1);
    $lock->upload = 0;
    $lock->build = 0;
    $lock->payroll = 0;
    $lock->save();
}
function langsungLembur($second_out, $tgl, $shift, $jabatan)
{
    if ($second_out != null) {


        // if(is_sunday($tgl)){
        //     return $lembur = 0;
        // }
        $lembur = 0;

        $t2 = strtotime($second_out);

        if ($jabatan == 'Satpam') {
            if ($shift == 'Pagi') {
                if (is_saturday($tgl)) {
                    // rubah disini utk perubahan jam lembur satpam
                    if ($t2 < strtotime('17:30:00')) {
                        // dd($t2, 'bukan sabtu');

                        return $lembur = 0;
                    } else {
                        // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                        return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                    }
                } else {
                    if ($t2 < strtotime('20:30:00') && $t2 > strtotime('12:00:00')) {
                        // dd($t2, 'bukan sabtu');
                        return $lembur = 0;
                    } else {
                        if ($t2 <= strtotime('23:59:00') && $t2 >= strtotime('20:30:00')) {


                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;
                        } else {

                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                        }
                    }
                    // kl
                }
            } else {
                if (is_saturday($tgl)) {
                    // rubah disini utk perubahan jam lembur satpam malam
                    if ($t2 < strtotime('05:30:00')) {
                        return $lembur = 0;
                    } else {
                        // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                        return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                } else {
                    if ($t2 < strtotime('08:30:00')) {
                        return $lembur = 0;
                    } else {
                        // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                        return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00')) / 60;
                    }
                }
            }
        } else {
            if ($shift == 'Pagi') {
                // Shift Pagi
                if (is_saturday($tgl)) {
                    if ($t2 < strtotime('15:30:00')) {
                        return $lembur = 0;
                    }
                    $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:00:00')) / 60;
                } else {
                    if ($t2 < strtotime('17:30:00')) {
                        return $lembur = 0;
                    }
                    $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                }

                // if($jabatan == 'Satpam') {
                //     if($shift == 'Pagi') {
                //         if(is_saturday($tgl)) {
                //             // rubah disini utk perubahan jam lembur satpam
                //             if($t2<(strtotime('17:30:00'))) {
                //                 // dd($t2, 'bukan sabtu');

                //                 return $lembur = 0;
                //             } else {

                //                 $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                //             }
                //         } else {
                //             if($t2<(strtotime('20:30:00'))) {
                //                 // dd($t2, 'bukan sabtu');
                //                 return $lembur = 0;
                //             } else  {

                //                 $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00'))/60;
                //             }
                //         }
                //     } else {
                //         if(is_saturday($tgl)) {
                //             // rubah disini utk perubahan jam lembur satpam malam
                //             if($t2<(strtotime('05:30:00'))) {
                //                 return $lembur = 0;
                //             } else {

                //                 $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                //             }
                //         } else {
                //             if($t2<(strtotime('08:30:00'))) {
                //                 return $lembur = 0;
                //             } else {

                //                 $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                //             }
                //         }

                //     }
                // }
            } else {
                //Shift Malam
                if (is_saturday($tgl)) {
                    if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                        return $lembur = 0;
                    }
                    $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
                } else {
                    if ($t2 < strtotime('05:30:00') && $t2 <= strtotime('23:59:00')) {
                        return $lembur = 0;
                    }
                    $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                }
            }
        }

        return $diff;
    } else {
        return $lembur = 0;
    }
}

function tgl_doang($tgl)
{
    $dt = Carbon::parse($tgl);
    return $dt->day;
}
function hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $tgl, $jabatan)
{
    $perJam = 60;
    if ($late == null) {
        if ($shift == 'Pagi') {
            if (is_saturday($tgl)) {
                $jam_kerja = 6;
            } elseif (is_friday($tgl)) {
                $jam_kerja = 7.5;
            } else {
                $jam_kerja = 8;
            }
        } else {
            $jam_kerja = 8;
            if (is_saturday($tgl)) {
                $jam_kerja = 6;
            } else {
                $jam_kerja = 8;
            }
        }
    } else {
        // check late kkk
        $total_late = late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan);
        //    dd($first_in, $first_out, $second_in, $second_out);
        //jok
        if ($second_in === null && $second_out === null && ($first_in === null && $first_out === null)) {
            $jam_kerja = 0;
        } elseif (($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)) {
            if (is_saturday($tgl)) {
                if ($first_in === null && $first_out === null) {
                    $jam_kerja = 2 - $total_late;
                    // $jam_kerja = 2 ;
                } else {
                    $jam_kerja = 4 - $total_late;
                    // $jam_kerja = 4 ;
                }
            } else {
                $jam_kerja = 4 - $total_late;
                // $jam_kerja = 4 ;
            }
        } else {
            if ($shift == 'Pagi') {
                if (is_saturday($tgl)) {
                    $jam_kerja = 6 - $total_late;
                } elseif (is_friday($tgl)) {
                    $jam_kerja = 7.5 - $total_late;
                } else {
                    $jam_kerja = 8 - $total_late;
                }
            } else {
                if (is_saturday($tgl)) {
                    $jam_kerja = 6 - $total_late;
                } else {
                    $jam_kerja = 8 - $total_late;
                }
            }
        }
    }
    if ($jabatan == 'Satpam' && is_sunday($tgl) == false) {
        $jam_kerja = 12;
        // $jam_kerja = $jam_kerja - $total_late;
    }

    // lolo
    if (is_sunday($tgl)) {
        // $t1 = strtotime(pembulatanJamOvertimeIn($first_in));
        // $t2 = strtotime(pembulatanJamOvertimeOut($second_out));

        $t1 = strtotime($first_in);
        $t2 = strtotime($second_out);

        $diff = gmdate('H:i:s', $t2 - $t1);

        $diff = explode(':', $diff);
        $jam = (int) $diff[0];
        $menit = (int) $diff[1];

        if ($menit >= 45) {
            $jam = $jam + 1;
        } elseif ($menit < 45 && $menit > 15) {
            $jam = $jam + 0.5;
        } else {
            $jam;
        }
        $jam_kerja = $jam * 2;
    }

    return $jam_kerja;
}

function karyawan_allow_edit($id, $role)
{
    $data = Karyawan::find($id);
    if ($role < 3 && $data->gaji_pokok > 4500000) {
        return 0;
    } else {
        return 1;
    }
}

function checkNonRegisterUser()
{
    $rekap = Yfrekappresensi::distinct('user_id')->get('user_id');
    $array = [];
    foreach ($rekap as $r) {
        $karyawan = Karyawan::where('id_karyawan', $r->user_id)->first();
        if ($karyawan === null) {
            $array[] = [
                'Karyawan_id' => $r->user_id,
            ];
        }
    }
    return $array;
}

function lamaBekerja($tgl)
{
    $date = Carbon::parse($tgl);
    $now = Carbon::now();
    $diff = $date->diffIndays($now);
    $tahun = floor($diff / 365);
    if ($diff < 30) {
        return $diff . ' Hari';
    }
    if ($tahun < 1) {
        $month = floor($diff / 30);
        return (int) $month . ' Bulan';
    }
    $month = floor(($diff % ($tahun * 365)) / 30);
    return (int) $tahun . ' Tahun ' . (int) $month . ' Bulan';
}

function isDesktop()
{
    if (auth()->user()->device == 1) {
        return 1;
    } else {
        return 0;
    }
}

function namaDiAside($nama)
{
    if ($nama != null) {
        $arrJam = explode(' ', $nama);
        if (count($arrJam) == 1) {
            return $arrJam[0];
        } else {
            return $arrJam[0] . ' ' . $arrJam[1];
        }
    } else {
        return 'No Name';
    }
}

function generatePassword($tgl)
{
    if ($tgl != null) {
        $arrJam = explode('-', fixTanggal($tgl));
        $year = substr($arrJam[0], 2);
        return $arrJam[2] . $arrJam[1] . $year;
    }
}

function fixTanggal($tgl)
{
    if ($tgl != null) {
        $arrJam = explode('-', $tgl);
        if ((int) $arrJam[1] < 10) {
            $month = '0' . (int) $arrJam[1];
        } else {
            $month = $arrJam[1];
        }
        if ((int) $arrJam[2] < 10) {
            $date = '0' . (int) $arrJam[2];
        } else {
            $date = $arrJam[2];
        }

        return $arrJam[0] . '-' . $month . '-' . $date;
    }
}

function monthYear($tgl)
{
    $month = Carbon::parse($tgl)->format('F');
    $year = Carbon::parse($tgl)->format('Y');
    return $month . ' ' . $year;
}

function getBulan($tgl)
{
    $arrJam = explode('-', $tgl);
    return $arrJam[1];
}

function addZeroToMonth($tgl)
{
    if ($tgl != null) {
        if ($tgl < 10) {
            return '0' . $tgl;
        } else {
            return $tgl;
        }
    }
}

function getTahun($tgl)
{
    $arrJam = explode('-', $tgl);
    return $arrJam[0];
}

function buatTanggal($tgl)
{
    $arrJam = explode('-', $tgl);
    return $arrJam[0] . '-' . $arrJam[1] . '-01';
}

function pembulatanJamOvertimeIn($jam)
{
    $arrJam = explode(':', $jam);
    if ((int) $arrJam[1] <= 3) {
        $tambahJam = (int) $arrJam[0];
        if ($tambahJam < 10) {
            $strJam = '0' . strval($tambahJam) . ':';
        } else {
            $strJam = strval($tambahJam) . ':';
        }
        return $strJam . '00:00';
    } elseif ((int) $arrJam[1] <= 33) {
        if ((int) $arrJam[0] < 10) {
            return $menit = '0' . $arrJam[0] . ':30:00';
        } else {
            return $menit = $arrJam[0] . ':30:00';
        }
    } else {
        $tambahJam = (int) $arrJam[0] + 1;
        if ($tambahJam < 10) {
            $strJam = '0' . strval($tambahJam) . ':';
        } else {
            $strJam = strval($tambahJam) . ':';
        }
        return $strJam . '00:00';
    }
}

function pembulatanJamOvertimeOut($jam)
{
    $arrJam = explode(':', $jam);
    try {
        if ((int) $arrJam[1] >= 30) {
            if ((int) $arrJam[0] < 10) {
                return $menit = '0' . (int) $arrJam[0] . ':30:00';
            } else {
                return $menit = $arrJam[0] . ':30:00';
            }
        } else {
            if ((int) $arrJam[0] < 10) {
                return $menit = '0' . (int) $arrJam[0] . ':00:00';
            } else {
                return $menit = $arrJam[0] . ':00:00';
            }
        }
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}

function hitungLembur($overtime_in, $overtime_out)
{
    if ($overtime_in != '' || $overtime_out != '') {
        $t1 = strtotime(pembulatanJamOvertimeIn($overtime_in));
        $t2 = strtotime(pembulatanJamOvertimeOut($overtime_out));

        $diff = gmdate('H:i:s', $t2 - $t1);
        $diff = explode(':', $diff);
        $jam = (int) $diff[0];
        $menit = (int) $diff[1];
        // if ( $menit<30 ) {
        //     $menit = 0;
        // } else {
        //     $menit = 30;
        // }
        $totalMenit = $jam * 60 + $menit;

        return $totalMenit;
    } else {
        return 0;
    }
}

function fixTrimTime($data)
{
    return $data . ':00';
}

function trimTime($data)
{
    return Str::substr($data, 0, 5);
}

function late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan)
{
    $late_1 = 0;
    $late_2 = 0;
    $late_3 = 0;
    $late_4 = 0;
    $late1 = checkFirstInLate($first_in, $shift, $tgl);
    $late2 = checkFirstOutLate($first_out, $shift, $tgl, $jabatan);
    $late3 = checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan);
    $late4 = checkSecondOutLate($second_out, $shift, $tgl, $jabatan);

    if (is_sunday($tgl)) {
        return 0;
    } else {
        return $late1 + $late2 + $late3 + $late4;
    }
}

function late_check_jam_lembur_only($overtime_in, $shift, $date)
{
    return checkOvertimeInLate($overtime_in, $shift, $date);
}

function is_jabatan_khusus($jabatan)
{
    // $jabatan = Karyawan::where('id_karyawan', $id)->first();
    switch ($jabatan) {
        case 'Satpam':
            $jabatan_khusus = 1;
            break;
        case 'Koki':
            $jabatan_khusus = 1;
            break;
        case 'Dapur Kantor':
            $jabatan_khusus = 1;
            break;
        case 'Dapur Pabrik':
            $jabatan_khusus = 1;
            break;
        case 'QC Aging':
            $jabatan_khusus = 1;
            break;
        case 'Driver':
            $jabatan_khusus = 1;
            break;

        default:
            $jabatan_khusus = 0;
    }
    return $jabatan_khusus;
}

function late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl, $id)
{
    // koko
    // $late = null;
    // $late1 = null;
    // $late2 = null;
    // $late3 = null;
    // $late4 = null;
    // ffff

    try {
        $data_jabatan = Karyawan::where('id_karyawan', $id)->first();
        $jabatan = $data_jabatan->jabatan;
        $jabatan_khusus = is_jabatan_khusus($jabatan);
    } catch (\Exception $e) {
        dd('ID karyawan tidak ada dalam database = ', $id);
        return $e->getMessage();
    }

    $late5 = null;

    // if(($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)){
    if (($second_in === '' && $second_out === '') || ($first_in === '' && $first_out === '')) {
        // $data->late = 1;
        // dd($data->late, $data->user_id);
        return $late = 1;
    }

    if (checkFirstInLate($first_in, $shift, $tgl)) {
        //  return $late = $late + 1;
        return $late = 1;
        // $late1 = 1;
    }
    if (checkFirstOutLate($first_out, $shift, $tgl, $jabatan_khusus)) {
        //  return $late = $late + 1;
        if ($jabatan_khusus == '') {
            return $late = 1;
        }
        // return $late = 1;
        // $late2 = 1;
    }
    if (checkSecondOutLate($second_out, $shift, $tgl, $jabatan)) {
        //  return $late = $late + 1;
        // if ($jabatan_khusus != '1') {
        //     return $late = 1;
        // }
        return $late = 1;

        // return $late = 1;
        // $late3 = 1;
    }

    // if ( checkOvertimeInLate( $overtime_in, $shift, $tgl ) ) {
    //     return $late = 1;
    // }

    if (checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan_khusus)) {
        // return $late = $late + 1 ;

        if ($jabatan_khusus == '') {
            return $late = 1;
        }
        // $late5 = 1;
    }

    if ($second_in == null && $second_out == null) {
        return $late = 1;
    }
    if ($first_in == null && $first_out == null) {
        return $late = 1;
    }
    // $late = $late1 + $late2 + $late3+ $late4 + $late5 ;
    // return $late;
}

// ook

function hoursToMinutes($jam)
{
    $arrJam = explode(':', $jam);
    $minJam = (int) $arrJam[0] * 60;
    $min = (int) $arrJam[1];
    return $minJam + $min;
}

function checkFirstInLate($check_in, $shift, $tgl)
{
    // rubah angka ini utk bulan puasa

    if (is_puasa($tgl)) {
        $jam_mulai_pagi = '07:03';
        $strtime_pagi = '07:03:00';
    } else {
        $jam_mulai_pagi = '08:03';
        $strtime_pagi = '08:03:00';
    }
    $perJam = 60;
    $late = null;
    if ($check_in != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($check_in)->betweenIncluded('05:30', $jam_mulai_pagi)) {
                $late = null;
            } else {
                $t1 = strtotime($strtime_pagi);
                $t2 = strtotime($check_in);
                $diff = gmdate('H:i:s', $t2 - $t1);
                $late = ceil(hoursToMinutes($diff) / $perJam);
                if ($late <= 5 && $late > 3.5) {
                    if (is_friday($tgl)) {
                        $late = 3.5;
                    } else {
                        $late = 4;
                    }
                } elseif ($late > 5) {
                    if (is_friday($tgl)) {
                        $late = $late - 1.5;
                    } else {
                        $late = $late - 1;
                    }
                }
            }
        } else {
            if (is_saturday($tgl)) {
                if (Carbon::parse($check_in)->betweenIncluded('14:00', '17:03')) {
                    $late = null;
                } else {
                    $t1 = strtotime('17:03:00');
                    $t2 = strtotime($check_in);

                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                }
            } else {
                if (Carbon::parse($check_in)->betweenIncluded('16:00', '20:03')) {
                    $late = null;
                } else {
                    $t1 = strtotime('20:03:00');
                    $t2 = strtotime($check_in);

                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                }
            }
        }
    }
    return $late;
}

function checkSecondOutLate($second_out, $shift, $tgl, $jabatan)
{
    $perJam = 60;
    $late = null;
    if ($second_out != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (is_saturday($tgl)) {
                if (Carbon::parse($second_out)->betweenIncluded('12:00', '14:59')) {
                    $t1 = strtotime('15:00:00');
                    $t2 = strtotime($second_out);
                    //kkk
                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            } else {
                if (Carbon::parse($second_out)->betweenIncluded('12:00', '16:59')) {
                    $t1 = strtotime('17:00:00');
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            }
        } else {
            if (is_saturday($tgl)) {
                // if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59') ) {
                if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59')) {
                    $t1 = strtotime('00:00:00');
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            } else {
                if (Carbon::parse($second_out)->betweenIncluded('00:00', '04:59')) {
                    $t1 = strtotime('05:00:00');
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);

                    // ook
                } elseif (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59')) {
                    $t1 = strtotime('23:59:00');
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam) + 4;
                } else {
                    $late = null;
                }
            }
        }

        // if($jabatan == 'Satpam') {

        //     if ($shift == 'Pagi') {
        //         if(is_saturday($tgl) ) {
        //             if (Carbon::parse($second_out)->betweenIncluded('08:01', '16:00')) {
        //                 $t1 = strtotime('15:00:00');
        //                 $t2 = strtotime($second_out);

        //                 $diff = gmdate('H:i:s', $t1 - $t2);
        //                 $late = ceil(hoursToMinutes($diff) / $perJam);
        //             } else {
        //                 $late = null;
        //             }
        //         } else {
        //             if (Carbon::parse($second_out)->betweenIncluded('08:01', '19:00')) {
        //                 $t1 = strtotime('17:00:00');
        //                 $t2 = strtotime($second_out);

        //                 $diff = gmdate('H:i:s', $t1 - $t2);
        //                 $late = ceil(hoursToMinutes($diff) / $perJam);
        //             } else {
        //                 $late = null;
        //             }
        //         }

        //     } else {
        //         if(is_saturday($tgl)) {
        //             if (Carbon::parse($second_out)->betweenIncluded('20:01', '23:59')) {
        //                 $t1 = strtotime('23:59:00');
        //                 $t2 = strtotime($second_out);

        //                 $diff = gmdate('H:i:s', $t1 - $t2);
        //                 $late = ceil(hoursToMinutes($diff) / $perJam);
        //             } else {
        //                 $late = null;
        //             }
        //         } else {
        //             if (Carbon::parse($second_out)->betweenIncluded('20:00', '23:59') ||
        //             Carbon::parse($second_out)->betweenIncluded('00:00', '07:00')) {
        //                 $t1 = strtotime('08:00:00');
        //                 $t2 = strtotime($second_out);

        //                 $diff = gmdate('H:i:s', $t1 - $t2);
        //                 $late = ceil(hoursToMinutes($diff) / $perJam);
        //             } else {
        //                 $late = null;
        //             }
        //         }

        //     }

        // }
    }
    if (is_sunday($tgl)) {
        return 0;
    } else {
        return $late;
    }
}

function checkOvertimeInLate($overtime_in, $shift, $tgl)
{
    $persetengahJam = 30;
    $late = null;
    if ($overtime_in != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($overtime_in)->betweenIncluded('12:00', '18:33')) {
                $late = null;
            } else {
                $t1 = strtotime('18:33:00');
                $t2 = strtotime($overtime_in);

                $diff = gmdate('H:i:s', $t2 - $t1);
                $late = ceil(hoursToMinutes($diff) / $persetengahJam);
            }
        }
    }
    return $late;
}

function checkFirstOutLate($first_out, $shift, $tgl, $jabatan)
{
    //ok
    $perJam = 60;
    $late = null;
    if (is_jabatan_khusus($jabatan) == 1) {
        $late = null;
    } else {
        if ($first_out != null) {
            if ($shift == 'Pagi') {
                // Shift Pagi
                if (Carbon::parse($first_out)->betweenIncluded('08:00', '11:29')) {
                    $t1 = strtotime('11:30:00');
                    $t2 = strtotime($first_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            } else {
                if (is_saturday($tgl)) {
                    if (Carbon::parse($first_out)->betweenIncluded('17:01', '20:59')) {
                        $t1 = strtotime('21:00:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {
                    if (Carbon::parse($first_out)->betweenIncluded('20:00', '23:59')) {
                        $t1 = strtotime('00:00:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                }
            }
        }
    }
    return $late;
}

function checkSecondInLate($second_in, $shift, $firstOut, $tgl, $jabatan)
{
    $perJam = 60;
    $late = null;
    if (is_jabatan_khusus($jabatan) == 1) {
        $late = null;
    } else {
        // jangan remark ini kalau ada error
        // $groupIstirahat;

        if ($second_in != null) {
            if ($shift == 'Pagi') {
                if ($firstOut != null) {
                    if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                        $groupIstirahat = 1;
                    } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                        $groupIstirahat = 2;
                    } else {
                        $groupIstirahat = 0;
                    }

                    // Shift Pagi ggg
                    if (is_friday($tgl)) {
                        if (Carbon::parse($second_in)->betweenIncluded('11:30', '13:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('13:03:00');
                            $t2 = strtotime($second_in);
                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                    } else {
                        if ($groupIstirahat == 1) {
                            if (Carbon::parse($second_in)->betweenIncluded('08:00', '12:33')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('12:33:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } elseif ($groupIstirahat == 2) {
                            if (Carbon::parse($second_in)->betweenIncluded('11:00', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            $late = null;
                        }
                    }
                } else {
                    //jika first out null
                    if ($shift == 'Pagi') {
                        if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('13:03:00');
                            $t2 = strtotime($second_in);

                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                    } else {
                        if (is_saturday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('22:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('01:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        }
                    }
                }
            } else {
                if (is_saturday($tgl)) {
                    if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                        $late = null;
                    } else {
                        $t1 = strtotime('22:03:00');
                        $t2 = strtotime($second_in);

                        $diff = gmdate('H:i:s', $t2 - $t1);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    }
                } else {
                    if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                        $late = null;
                    } else {
                        $t1 = strtotime('01:03:00');
                        $t2 = strtotime($second_in);

                        $diff = gmdate('H:i:s', $t2 - $t1);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    }
                }
            }
        }
    }
    return $late;
}

function noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out)
{
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null && (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null))) {
        return 'No Scan';
    }
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null) {
        return null;
    }
    if (($first_in == null) & ($first_out != null) || ($first_in != null) & ($first_out == null)) {
        return 'No Scan';
    }
    if (($second_in == null) & ($second_out != null) || ($second_in != null) & ($second_out == null)) {
        return 'No Scan';
    }
    // if (( $second_in == null ) && ( $second_out == null )) {
    //     return 'No Scan';
    // }
    // if ( ( $first_in == null ) && ( $first_out == null ) ) {
    //     return 'No Scan';
    // }

    if (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null)) {
        return 'No Scan';
    }
}

function titleCase($data)
{
    // $data1 =  Str::of( $data )->trim( '/' );
    return Str::of($data)
        ->trim('/')
        ->title();
}

function getLastIdKaryawan()
{
    return DB::table('karyawans')->max('id_karyawan');
}

function getNextIdKaryawan()
{
    return getLastIdKaryawan() + 1;
}

function format_tgl($tgl)
{
    if ($tgl) {
        return date('d-M-Y', strtotime($tgl));
    }
}

function format_tgl_hari($tgl)
{
    if ($tgl) {
        return date('D, d-M-Y', strtotime($tgl));
    }
}

function format_jam($jam)
{
    if ($jam) {
        return Carbon::createFromFormat('H:i:s', $jam)->format('H:i');
    }
}

function is_friday($tgl)
{
    if ($tgl) {
        return Carbon::parse($tgl)->isFriday();
    }
}

function is_saturday($tgl)
{
    if ($tgl) {
        // if ( Carbon::parse( $tgl )->isSaturday() ) {
        //     return true;
        // } else {
        //     return false;
        // }
        return Carbon::parse($tgl)->isSaturday();
    }
}

function sp_recal_presensi()
{
    if (Schema::hasTable('table_name')) {
        // Do something if exists
    }
}
