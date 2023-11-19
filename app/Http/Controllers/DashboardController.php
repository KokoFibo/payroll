<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jamkerjaid;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlah_total_karyawan = Karyawan::count();
        $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->count();
        $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->count();
        return view('dashboard', compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']));
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
        $user_id = 4;
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
        // return  redirect('/karyawanindex');

        // return view('mobile')->with([

        //     'data' => $data,
        //     'total_hari_kerja' => $total_hari_kerja,
        //     'total_jam_kerja' => $total_jam_kerja,
        //     'total_jam_lembur' => $total_jam_lembur,
        //     'total_keterlambatan' => $total_keterlambatan,

        // ]);
    }
}
