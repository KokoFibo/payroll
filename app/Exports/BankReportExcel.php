<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class BankReportExcel implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    protected $payroll;

    public function __construct (object $payroll) {
        $this->payroll = $payroll;
    }




    public function collection()
    {
        return $this->payroll;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Bank',
            'No. Rekening',
            'Total Pembayaran',
            'Company',
            'Placement',
        ];
    }

    // public function bindValue(Cell $cell, $value)
    // {
    //     if ($cell->getColumn() == 'C') {
    //         $cell->setValueExplicit($value, DataType::TYPE_STRING);

    //         return true;
    //     }


    // }


    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'C' => "0",
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}
class UsersExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder
{

}
