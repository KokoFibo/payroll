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

class AdjustmentCategorySheetExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    public function __construct(protected Collection $rows, protected string $title)
    {
    }

    public function collection()
    {
        return $this->rows->map(fn ($row) => [
            $row['directorate'],
            $row['departemen'],
            $row['adjustment_type'],
            $row['jumlah_karyawan'],
            $row['total_adjustment'],
            $row['status'],
            $row['remark'],
        ]);
    }

    public function headings(): array
    {
        return [
            'Directorate',
            'Departemen',
            'Adjustment Type',
            'Number of Employees',
            'Total Adjustment Amount',
            'Status',
            'Remark',
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function columnFormats(): array
    {
        return [
            // Total Adjustment Amount -> ribuan pakai pemisah koma, tanpa desimal
            'E' => '#,##0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
