<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayrollApiController extends Controller
{
    public function summary(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        // =====================
        // SUMMARY UTAMA
        // =====================
        $data = Payroll::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->select(
                'placement_id',
                'company_id',
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('placement_id', 'company_id')
            ->get()
            ->groupBy('placement_id');

        $totalStaff = $data->flatten()->sum('jumlah');
        $totalAmount = $data->flatten()->sum('total');

        // =====================
        // LAPORAN 3 BULAN
        // =====================
        $laporan_bulanan = collect();

        $baseDate = Carbon::create($year, $month, 1);

        for ($i = 0; $i < 3; $i++) {
            $date = $baseDate->copy()->subMonths($i);

            $bulan = $date->month;
            $tahun = $date->year;

            $laporan = DB::table('payrolls')
                ->join('placements', 'payrolls.placement_id', '=', 'placements.id')
                ->selectRaw("
                    placements.placement_name as placement_name,
                    placement_id,
                    SUM(total) as total_gaji,
                    COUNT(DISTINCT id_karyawan) as jumlah_karyawan,
                    SUM(tambahan_shift_malam) as tambahan_shift_malam,
                    SUM(jam_kerja) as jam_kerja,
                    SUM(jam_lembur) as jam_lembur,
                    SUM(bonus1x) as bonus1x,
                    SUM(potongan1x) + SUM(denda_lupa_absen) + SUM(denda_resigned) + SUM(iuran_air) as potongan1x,
                    SUM(gaji_pokok/198*jam_kerja) as total_gaji_pokok,
                    SUM(gaji_lembur*jam_lembur) as total_lemburan
                ")
                ->where('metode_penggajian', 'Perjam')
                ->whereYear('date', $tahun)
                ->whereMonth('date', $bulan)
                ->groupBy('placement_id', 'placements.placement_name')
                ->get()
                ->map(function ($row) use ($bulan, $tahun) {
                    return [
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'placement_id' => $row->placement_id,
                        'placement_name' => $row->placement_name,
                        'total_gaji' => $row->total_gaji,
                        'jumlah_karyawan' => $row->jumlah_karyawan,
                        'tambahan_shift_malam' => $row->tambahan_shift_malam,
                        'jam_kerja' => $row->jam_kerja,
                        'jam_lembur' => $row->jam_lembur,
                        'bonus1x' => $row->bonus1x,
                        'potongan1x' => $row->potongan1x,
                        'total_gaji_pokok' => $row->total_gaji_pokok,
                        'total_lemburan' => $row->total_lemburan,
                        'rata_rata_gaji' => $row->jumlah_karyawan > 0
                            ? $row->total_gaji_pokok / $row->jumlah_karyawan
                            : 0,
                        'rata_rata_gaji_perjam' => $row->jam_kerja > 0
                            ? $row->total_gaji_pokok / $row->jam_kerja
                            : 0,
                        'rata_rata_lembur_perjam' => $row->jam_lembur > 0
                            ? ($row->total_lemburan ?? 0) / $row->jam_lembur
                            : 0,
                    ];
                });

            $laporan_bulanan = $laporan_bulanan->merge($laporan);
        }

        $laporan_bulanan = $laporan_bulanan->sortBy([
            ['tahun', 'desc'],
            ['placement_name', 'asc'],
        ])->values();

        // =====================
        // RESPONSE API
        // =====================
        return response()->json([
            'success' => true,
            'filter' => [
                'year' => (int) $year,
                'month' => (int) $month,
            ],
            'summary' => [
                'total_staff' => $totalStaff,
                'total_amount' => $totalAmount,
            ],
            'grouped_data' => $data,
            'laporan_3_bulan' => $laporan_bulanan,
        ]);
    }
}
