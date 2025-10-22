<?php

namespace App\Http\Controllers;

use App\Http\Middleware\User;
use App\Models\Yfrekappresensi;
use Illuminate\Http\Request;

class ApiPresensiController extends Controller
{

    public function summary($month, $year, $user_id)
    {
        $presensi = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'hari' => $presensi->count(),
                'jamKerja' => $presensi->sum('total_jam_kerja'),
                'lembur' => $presensi->sum('total_jam_lembur'),
                'terlambat' => $presensi->sum('late'),
                'shiftMalam' => $presensi->sum('shift_malam'),
            ]
        ]);
    }

    public function userPresensi($month, $year, $user_id)
    {
        $data = Yfrekappresensi::join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
            ->select(
                'yfrekappresensis.*',
                'karyawans.nama'
            )
            ->where('yfrekappresensis.user_id', $user_id)
            ->whereMonth('yfrekappresensis.date', $month)
            ->whereYear('yfrekappresensis.date', $year)
            ->orderBy('yfrekappresensis.date', 'asc')
            ->paginate(10); // 👈 10 data per halaman

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getPeriods($user_id)
    {
        $periods = Yfrekappresensi::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->where('user_id', 2449)
            ->distinct()
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $periods
        ]);
    }
}
