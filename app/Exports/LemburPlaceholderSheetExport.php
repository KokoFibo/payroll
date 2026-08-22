<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Sheet "Lembur" pada template membandingkan jumlah karyawan per rate lembur
 * (mis. Rp15.000/jam vs Rp20.000/jam). Skema payrolls yang dilampirkan hanya
 * menyimpan total (jam_lembur, gaji_lembur), bukan rate per jam, jadi logika
 * kalkulasinya belum bisa dibuat otomatis. Sheet ini disediakan kosong dulu
 * (header saja) supaya format file tetap konsisten dengan template asli.
 * Begitu field rate lembur per jam tersedia, tinggal isi method array()
 * dengan query serupa AdjustmentCategorySheetExport.
 */
class LemburPlaceholderSheetExport implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return ['Directorate', 'Departemen', 15000, 20000, 'Total Employees', 'Status', 'Remark'];
    }

    public function title(): string
    {
        return 'Lembur';
    }
}
