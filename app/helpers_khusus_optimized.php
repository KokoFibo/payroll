<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Payroll;
use App\Models\Yfrekappresensi;
use App\Models\Karyawan;
use App\Models\Liburnasional;

function quickRebuildOptimized($month, $year)
{
    // -- PREP: ambil data libur dan total hari kerja bulan ini --
    $libur = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->orderBy('tanggal_mulai_hari_libur', 'asc')
        ->get('tanggal_mulai_hari_libur');

    $total_n_hari_kerja = getTotalWorkingDays($year, $month); // pastikan helper ada
    $startOfMonth = Carbon::parse("$year-$month-01");
    $endOfMonth = $startOfMonth->copy()->endOfMonth();

    // kalau mau langsung eksekusi set $pass = true; (tidak digunakan lebih lanjut)
    $pass = true;

    // Hapus payroll bulan tsb dulu (sebelumnya)
    Payroll::whereMonth('date', $month)->whereYear('date', $year)->delete();
    delete_failed_jobs(); // pastikan helper ada

    $jumlah_libur_nasional = jumlah_libur_nasional($month, $year); // optional helper

    // ambil semua user_id yang punya presensi di bulan ini (kecuali karyawan Blacklist)
    $datas = Yfrekappresensi::whereBetween('date', [$startOfMonth, $endOfMonth])
        ->whereHas('karyawan', function ($q) {
            $q->where('status_karyawan', '!=', 'Blacklist');
        })
        ->pluck('user_id')
        ->unique()
        ->toArray();

    if (empty($datas)) {
        return 0;
    }

    // mulai transaksi untuk safety (opsional)
    DB::beginTransaction();
    try {
        foreach ($datas as $user_id) {

            // ambil semua presensi untuk user di bulan tsb
            $rows = Yfrekappresensi::where('user_id', $user_id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

            if ($rows->isEmpty()) {
                // tidak ada presensi yang valid, lewati
                continue;
            }

            // ambil data karyawan sekali saja (data statis)
            $kar = Karyawan::where('id_karyawan', $user_id)->first();
            if (!$kar) {
                // kalau tidak ada karyawan, skip
                continue;
            }

            // inisialisasi accumulator
            $total_hari_kerja = 0;
            $total_jam_kerja = 0;
            $total_jam_lembur = 0;
            $total_jam_kerja_libur = 0;
            $jam_lembur_libur = 0;
            $no_scan_history = 0;
            $late_history = 0;
            $shift_malam = 0;
            $late = 0;
            $hari_kerja_libur = 0;
            $tanggal_terakhir = null; // untuk tanggal entry payroll

            // akumulasi dari semua row presensi
            foreach ($rows as $r) {
                $total_hari_kerja += (float) ($r->total_hari_kerja ?? 0);
                $total_jam_kerja += (float) ($r->total_jam_kerja ?? 0);
                $total_jam_lembur += (float) ($r->total_jam_lembur ?? 0);
                $total_jam_kerja_libur += (float) ($r->total_jam_kerja_libur ?? 0);
                $jam_lembur_libur += (float) ($r->jam_lembur_libur ?? 0); // pastikan kolom benar
                if (!empty($r->no_scan_history)) $no_scan_history++;
                if (!empty($r->late_history)) $late_history++;
                $shift_malam += (float) ($r->shift_malam ?? 0);
                $late += (float) ($r->late ?? 0);
                $hari_kerja_libur += (float) ($r->hari_kerja_libur ?? 0); // pastikan kolom ada
                $tanggal_terakhir = $r->date; // last overwritten = terakhir
            }

            // --- HITUNG BPJS / POTONGAN berdasarkan data karyawan (kar) ---
            // Asumsikan field di Karyawan sesuai:
            // potongan_JP, potongan_JHT, potongan_kesehatan, gaji_bpjs, tanggungan
            // Jika field berbeda, sesuaikan.
            $jp = 0;
            if (isset($kar->potongan_JP) && intval($kar->potongan_JP) === 1) {
                // batas atas JP: gunakan nilai aturan lokal — ubah konstanta jika perlu
                $jp_upah_limit = 10042300; // <-- sesuaikan bila perlu
                $jp_base = $kar->gaji_bpjs ?? 0;
                $jp = ($jp_base <= $jp_upah_limit) ? ($jp_base * 0.01) : ($jp_upah_limit * 0.01);
            }

            $jht = 0;
            if (isset($kar->potongan_JHT) && intval($kar->potongan_JHT) === 1) {
                $jht = ($kar->gaji_bpjs ?? 0) * 0.02;
            }

            $kesehatan = 0;
            if (isset($kar->potongan_kesehatan) && intval($kar->potongan_kesehatan) === 1) {
                $data_gaji_bpjs = $kar->gaji_bpjs ?? 0;
                $bpjs_kesehatan_limit = 12000000; // ubah jika aturan berubah
                $data_gaji_bpjs = ($data_gaji_bpjs >= $bpjs_kesehatan_limit) ? $bpjs_kesehatan_limit : $data_gaji_bpjs;
                $kesehatan = $data_gaji_bpjs * 0.01;
            }

            // tanggungan (jika kolom tanggungan menyimpan angka tanggungan)
            $tanggungan = 0;
            if (!empty($kar->tanggungan) && $kar->tanggungan >= 1) {
                $tanggungan = $kar->tanggungan * ($kar->gaji_bpjs ?? 0) * 0.01;
            }

            // potongan JKK / JKM (jika ada flag)
            $jkk = (isset($kar->potongan_JKK) && intval($kar->potongan_JKK) === 1) ? 1 : 0;
            $jkm = (isset($kar->potongan_JKM) && intval($kar->potongan_JKM) === 1) ? 1 : 0;

            // --- DENDA LUPA ABSEN / NOSCAN ---
            $denda_lupa_absen = 0;
            if ($no_scan_history > 3 && trim($kar->metode_penggajian ?? '') == 'Perjam') {
                $denda_lupa_absen = ($no_scan_history - 3) * (($kar->gaji_pokok ?? 0) / 198);
            } else {
                if ($no_scan_history > 3) {
                    $denda_lupa_absen = ($no_scan_history - 3) * (($kar->gaji_pokok ?? 0) / 198);
                } else {
                    $denda_lupa_absen = 0;
                }
            }

            // --- PERHITUNGAN GAJI LIBUR & LEMBUR ---
            // total_gaji_libur: jam kerja libur * (gaji_pokok / 198) + jam_lembur_libur * gaji_overtime
            $gaji_pokok = $kar->gaji_pokok ?? 0;
            $gaji_overtime = $kar->gaji_overtime ?? 0;

            $total_gaji_libur = ($total_jam_kerja_libur * ($gaji_pokok / 198)) + ($jam_lembur_libur * $gaji_overtime);
            $total_gaji_lembur_karyawan = $total_jam_lembur * $gaji_overtime;

            // subtotal tergantung metode penggajian
            if (trim($kar->metode_penggajian ?? '') == 'Perjam') {
                $subtotal = $total_jam_kerja * ($gaji_pokok / 198) + $total_gaji_lembur_karyawan + $total_gaji_libur;
                $gaji_karyawan_bulanan = 0;
            } else {
                $manfaat_libur = 0;
                // perhitungan manfaat_libur: jika karyawan baru atau resigned, panggil helper
                $beginning_date = buat_tanggal($month, $year); // pastikan helper ini ada
                if ($kar->metode_penggajian == 'Perbulan' && ($kar->tanggal_bergabung >= $beginning_date || $kar->status_karyawan == 'Resigned')) {
                    $manfaat_libur = manfaat_libur($month, $year, $libur, $user_id, $kar->tanggal_bergabung);
                } else {
                    $manfaat_libur = $libur->count();
                }

                $gaji_karyawan_bulanan = ($gaji_pokok / max(1, $total_n_hari_kerja)) * ($total_hari_kerja + $manfaat_libur) + $total_gaji_libur + $total_gaji_lembur_karyawan;
                $subtotal = $gaji_karyawan_bulanan;
            }

            // tambahan shift malam: default dari data karyawan (kolom gaji_shift_malam_satpam optional)
            $tambahan_shift_malam = $shift_malam * $gaji_overtime;
            if (isset($kar->jabatan_id) && intval($kar->jabatan_id) === 17 && isset($kar->gaji_shift_malam_satpam)) {
                $tambahan_shift_malam = $shift_malam * $kar->gaji_shift_malam_satpam;
            }

            // libur_nasional default 0 atau dari perhitungan lain jika ada
            $libur_nasional_val = 0; // sesuaikan bila perlu

            // total bonus & potongan statis dari karyawan
            $total_bonus_dari_karyawan = ($kar->bonus ?? 0) + ($kar->tunjangan_jabatan ?? 0) + ($kar->tunjangan_bahasa ?? 0) + ($kar->tunjangan_skill ?? 0) + ($kar->tunjangan_lembur_sabtu ?? 0) + ($kar->tunjangan_lama_kerja ?? 0);
            $total_potongan_dari_karyawan = ($kar->iuran_air ?? 0) + ($kar->iuran_locker ?? 0);

            // pph21 placeholder (kalau mau dihitung, tambahkan logic)
            $pph21 = 0;

            // tanggal payroll (gunakan tanggal terakhir presensi jika ada)
            $payroll_date = $tanggal_terakhir ? buatTanggal($tanggal_terakhir) : buatTanggal($startOfMonth);

            // total akhir
            $total_final = $subtotal
                + $total_bonus_dari_karyawan
                + $libur_nasional_val
                + $tambahan_shift_malam
                - $total_potongan_dari_karyawan
                - 0 // pajak (jika ada)
                - $jp
                - $jht
                - $kesehatan
                - $tanggungan
                - $denda_lupa_absen
                - $pph21;

            // create payroll
            Payroll::create([
                'jp' => $jp,
                'jht' => $jht,
                'kesehatan' => $kesehatan,
                'tanggungan' => $tanggungan,
                'jkk' => $jkk,
                'jkm' => $jkm,
                'denda_lupa_absen' => $denda_lupa_absen,
                'gaji_libur' => $total_gaji_libur, // perhitungan di atas
                'nama' => $kar->nama ?? null,
                'id_karyawan' => $kar->id_karyawan,
                'jabatan_id' => $kar->jabatan_id ?? null,
                'company_id' => $kar->company_id ?? null,
                'placement_id' => $kar->placement_id ?? null,
                'department_id' => $kar->department_id ?? null,
                'status_karyawan' => $kar->status_karyawan ?? null,
                'metode_penggajian' => $kar->metode_penggajian ?? null,
                'nomor_rekening' => $kar->nomor_rekening ?? null,
                'nama_bank' => $kar->nama_bank ?? null,
                'gaji_pokok' => $gaji_pokok,
                'gaji_lembur' => $gaji_overtime,
                'gaji_bpjs' => $kar->gaji_bpjs ?? 0,
                'ptkp' => $kar->ptkp ?? 0,
                'libur_nasional' => $libur_nasional_val,
                'hari_kerja_libur' => $hari_kerja_libur,
                'jam_lembur_libur' => $jam_lembur_libur,
                'jam_kerja_libur' => $total_jam_kerja_libur,
                'hari_kerja' => $total_hari_kerja,
                'jam_kerja' => $total_jam_kerja,
                'jam_lembur' => $total_jam_lembur,
                'jumlah_jam_terlambat' => $late,
                'total_noscan' => $no_scan_history,
                'thr' => $kar->bonus ?? 0,
                'tunjangan_jabatan' => $kar->tunjangan_jabatan ?? 0,
                'tunjangan_bahasa' => $kar->tunjangan_bahasa ?? 0,
                'tunjangan_skill' => $kar->tunjangan_skill ?? 0,
                'tunjangan_lama_kerja' => $kar->tunjangan_lama_kerja ?? 0,
                'tunjangan_lembur_sabtu' => $kar->tunjangan_lembur_sabtu ?? 0,
                'iuran_air' => $kar->iuran_air ?? 0,
                'iuran_locker' => $kar->iuran_locker ?? 0,
                'tambahan_jam_shift_malam' => $shift_malam,
                'tambahan_shift_malam' => $tambahan_shift_malam,
                'subtotal' => $subtotal,
                'date' => $payroll_date,
                'pph21' => $pph21,
                'total' => $total_final,
                'total_bpjs' => $kar->total_bpjs ?? null,
            ]);
        }

        DB::commit();
        return 1; // sukses
    } catch (\Throwable $e) {
        DB::rollBack();
        // log error supaya gampang debug
        \Log::error('quickRebuildOptimized error: ' . $e->getMessage(), [
            'month' => $month,
            'year' => $year,
            'trace' => $e->getTraceAsString()
        ]);
        throw $e; // atau return false tergantung preferensi
    }
}
