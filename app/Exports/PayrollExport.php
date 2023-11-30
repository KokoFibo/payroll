<?php

namespace App\Exports;

use Style\Alignment;
use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

// class BankReportExcel implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles
class BankReportExcel implements  FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles, WithMapping
{
    use Exportable;
    protected $selected_company;

    // public function __construct (object $payroll) {
    //     $this->payroll = $payroll;
    // }

    public function __construct ($selected_company) {
        $this->selected_company = $selected_company;
    }

    public function query () {
        // $nama_file="";
        if ($this->status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($this->status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }
        switch ($this->selected_company) {
            case 0:
                return Payroll::whereIn('status_karyawan', $statuses)->get();
                $nama_file="semua_payroll.xlsx";
                break;

            case 1:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YCME')->get();
                    $nama_file="payroll_pabrik1.xlsx";
                break;

            case 2:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV')->get();
                    $nama_file="payroll_pabrik2.xlsx";

                break;

            case 3:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->whereIn('placement', ['YIG', 'YSM'])->get();
                    $nama_file="payroll_kantor.xlsx";

                break;

            case 4:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'ASB')
                    ->where('company', 'ASB')
                    ->get();
                    $nama_file="payroll_ASB.xlsx";

                break;

            case 5:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'DPA')
                    ->where('company', 'DPA')
                   ->get();
                   $nama_file="payroll_DPA.xlsx";

                break;

            case 6:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YCME')
                    ->where('company', 'YCME')
                   ->get();
                   $nama_file="payroll_YCME.xlsx";

                break;

            case 7:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YEV')
                    ->where('company', 'YEV')
                   ->get();
                   $nama_file="payroll_YEV.xlsx";

                break;

            case 8:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YIG')
                    ->where('company', 'YIG')
                   ->get();
                   $nama_file="payroll_YIG.xlsx";

                break;

            case 9:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YSM')
                    ->where('company', 'YSM')
                   ->get();
                   $nama_file="payroll_YSM.xlsx";

                break;
        }
    }

    public function map ($payroll): array
    {
        return [
            $payroll->id_karyawan,
            $payroll->nama,
            $payroll->jabatan,


        ];
    }

   



    // public function collection()
    // {
    //     return $this->payroll;
    // }

    public function title(): string {
        return 'Laporan Gaji Karyawan';
    }

    // public function headings(): array
    // {
    //     return [
    //         [
    //             'Data Karyawan'
    //         ],
    //         [
    //             'Nama',
    //             'Bank',
    //             'No. Rekening',
    //             'Total Pembayaran',
    //             'Company',
    //             'Placement',
    //         ],
    //     ];
    // }

    


    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'C' => "0",
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    

    public function styles (Worksheet $sheet) {
        return [
            '1' => ['font' => ['bold' => true]],
            '2' => ['font' => ['bold' => true]],
            '1' => ['font' => ['size' => 24]],
            'C' => ['text' => ['align' => 'center']],
        ];
        // atau bisa juga
        // $sheet->getStyle('1')->getFont()->setBold(true);
        // $sheet->getStyle('2')->getFont()->setBold(true);

    }
}

