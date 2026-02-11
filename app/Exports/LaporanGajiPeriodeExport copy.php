<?php

namespace App\Exports;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanGajiPeriodeExport implements
    FromArray,
    WithHeadings,
    WithColumnFormatting,
    ShouldAutoSize
{
    protected $data;
    protected $months;

    public function __construct($data, $months)
    {
        $this->data = $data;
        $this->months = $months;
    }

    public function array(): array
    {
        $result = [];

        foreach ($this->data as $row) {

            $line = [
                $row['id'] ?? '',
                $row['nama'] ?? '',
            ];

            foreach ($this->months as $m) {
                $line[] = $row[$m] ?? 0;
            }

            $result[] = $line;
        }

        return $result;
    }

    public function headings(): array
    {
        $headers = ['ID Karyawan', 'Nama'];

        foreach ($this->months as $m) {
            $headers[] = Carbon::createFromFormat('Y-m', $m)
                ->translatedFormat('M Y');
        }

        return $headers;
    }

    public function columnFormats(): array
    {
        $formats = [];

        $startColumnIndex = 3; // C = kolom ke-3

        foreach ($this->months as $index => $month) {

            $columnLetter = Coordinate::stringFromColumnIndex(
                $startColumnIndex + $index
            );

            // Tanpa desimal
            $formats[$columnLetter] = '#,##0';
        }

        return $formats;
    }
}
