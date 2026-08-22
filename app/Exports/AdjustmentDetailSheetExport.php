<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdjustmentDetailSheetExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    public function __construct(protected Collection $rows)
    {
    }

    public function collection()
    {
        return $this->rows->map(fn ($row) => [
            $row['nama'],
            $row['directorate'],
            $row['departemen'],
            $row['gaji_pokok_lama'],
            $row['gaji_pokok_baru'],
            $row['gaji_pokok_diff'],
            $row['gaji_lembur_lama'],
            $row['gaji_lembur_baru'],
            $row['gaji_lembur_diff'],
            $row['bonus'],
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Directorate',
            'Departemen',
            'Gaji Pokok Lama',
            'Gaji Pokok Baru',
            'Gaji Pokok Selisih',
            'Gaji Lembur Lama',
            'Gaji Lembur Baru',
            'Gaji Lembur Selisih',
            'Bonus',
        ];
    }

    public function title(): string
    {
        return 'Detail';
    }

    public function columnFormats(): array
    {
        return [
            // Gaji Pokok Lama/Baru/Selisih
            'D' => '#,##0',
            'E' => '#,##0',
            'F' => '#,##0',
            // Gaji Lembur Lama/Baru/Selisih
            'G' => '#,##0',
            'H' => '#,##0',
            'I' => '#,##0',
            // Bonus
            'J' => '#,##0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
