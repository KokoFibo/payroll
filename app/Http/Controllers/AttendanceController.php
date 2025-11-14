<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Yfrekappresensi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class AttendanceController extends Controller
{
    /**
     * Get attendance data for specific user and month
     */

    public function index($user_id, $month, $year)
    {
        // Ambil data attendance
        $attendanceData = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'DESC')
            ->get();

        // Jika tidak ada data
        if ($attendanceData->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'summary' => [
                    'total_jam_kerja' => 0,
                    'total_hari_kerja' => 0,
                    'total_jam_lembur' => 0,
                    'total_jam_kerja_libur' => 0,
                    'total_hari_kerja_libur' => 0,
                    'total_shift_malam' => 0,
                    'total_hari_kerja_keseluruhan' => 0, // total keseluruhan
                ],
                'message' => 'No attendance data found for this user and month'
            ]);
        }

        // Transform data sesuai field tabel
        $transformedData = $attendanceData->map(function ($item) {
            return [
                'date' => $item->date,
                'first_in' => $item->first_in,
                'first_out' => $item->first_out,
                'second_in' => $item->second_in,
                'second_out' => $item->second_out,
                'overtime_in' => $item->overtime_in,
                'overtime_out' => $item->overtime_out,
                'total_jam_kerja' => (float)$item->total_jam_kerja,
                'total_hari_kerja' => (float)$item->total_hari_kerja,
                'total_jam_lembur' => (float)$item->total_jam_lembur,
                'total_jam_kerja_libur' => (float)$item->total_jam_kerja_libur,
                'total_hari_kerja_libur' => (float)$item->total_hari_kerja_libur,
                'total_jam_lembur_libur' => (float)$item->total_jam_lembur_libur,
                'late' => $item->late,
                'no_scan' => $item->no_scan,
                'shift' => $item->shift,
                'shift_malam' => $item->shift_malam,
            ];
        });

        // Hitung summary
        $totalHariKerja = $attendanceData->sum('total_hari_kerja');
        $totalHariKerjaLibur = $attendanceData->sum('total_hari_kerja_libur');

        $summary = [
            'total_jam_kerja' => $attendanceData->sum('total_jam_kerja'),
            'total_hari_kerja' => $totalHariKerja,
            'total_jam_lembur' => $attendanceData->sum('total_jam_lembur'),
            'total_jam_kerja_libur' => $attendanceData->sum('total_jam_kerja_libur'),
            'total_hari_kerja_libur' => $totalHariKerjaLibur,
            'total_jam_lembur_libur' => $attendanceData->sum('total_jam_lembur_libur'),
            'total_shift_malam' => $attendanceData->sum('shift_malam'),
            'total_hari_kerja_keseluruhan' => $totalHariKerja + $totalHariKerjaLibur, // total keseluruhan
        ];

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'summary' => $summary,
            'message' => 'Attendance data retrieved successfully'
        ]);
    }


    public function index1($user_id, $month, $year)
    {
        // Ambil data attendance
        $attendanceData = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'DESC')
            ->get();

        // Jika tidak ada data
        if ($attendanceData->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'summary' => [
                    'total_jam_kerja' => 0,
                    'total_hari_kerja' => 0,
                    'total_jam_lembur' => 0,
                    'total_jam_kerja_libur' => 0,
                    'total_hari_kerja_libur' => 0,
                    'total_jam_lembur_libur' => 0,
                    'total_shift_malam' => 0,
                ],
                'message' => 'No attendance data found for this user and month'
            ]);
        }

        // Transform data sesuai field tabel
        $transformedData = $attendanceData->map(function ($item) {
            return [
                'date' => $item->date,
                'first_in' => $item->first_in,
                'first_out' => $item->first_out,
                'second_in' => $item->second_in,
                'second_out' => $item->second_out,
                'overtime_in' => $item->overtime_in,
                'overtime_out' => $item->overtime_out,
                'total_jam_kerja' => (float)$item->total_jam_kerja,
                'total_hari_kerja' => (float)$item->total_hari_kerja,
                'total_jam_lembur' => (float)$item->total_jam_lembur,
                'total_jam_kerja_libur' => (float)$item->total_jam_kerja_libur,
                'total_hari_kerja_libur' => (float)$item->total_hari_kerja_libur,
                'total_jam_lembur_libur' => (float)$item->total_jam_lembur_libur,
                'late' => $item->late,
                'no_scan' => $item->no_scan,
                'shift' => $item->shift,
                'shift_malam' => $item->shift_malam,
            ];
        });

        // Hitung summary
        $summary = [
            'total_jam_kerja' => $attendanceData->sum('total_jam_kerja'),
            'total_hari_kerja' => $attendanceData->sum('total_hari_kerja'),
            'total_jam_lembur' => $attendanceData->sum('total_jam_lembur'),
            'total_jam_kerja_libur' => $attendanceData->sum('total_jam_kerja_libur'),
            'total_hari_kerja_libur' => $attendanceData->sum('total_hari_kerja_libur'),
            'total_jam_lembur_libur' => $attendanceData->sum('total_jam_lembur_libur'),
            'total_shift_malam' => $attendanceData->sum('shift_malam'),
        ];

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'summary' => $summary,
            'message' => 'Attendance data retrieved successfully'
        ]);
    }
}
