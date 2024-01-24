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
class PayrollExport implements FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles, WithMapping
{
    use Exportable;



    protected $selected_company, $status, $month, $year;
    public function __construct($selected_company, $status, $month, $year)
    {
        $this->selected_company = $selected_company;
        $this->status = $status;
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        if ($this->status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($this->status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }
        if ($this->status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($this->status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }
        switch ($this->selected_company) {
            case 0:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 1:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YCME')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 2:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 3:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->whereIn('placement', ['YIG', 'YSM'])
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 4:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'ASB')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 5:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'DPA')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 6:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YCME')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 7:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YEV')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 8:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YIG')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;

            case 9:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YSM')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;
            case 10:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YAM')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc');
                break;
        }
    }

    public function map($payroll): array
    {
        return [
            $payroll->id_karyawan,
            $payroll->nama,
            $payroll->nama_bank,
            $payroll->nomor_rekening,
            $payroll->jabatan,
            $payroll->company,
            $payroll->placement,
            $payroll->metode_penggajian,
            $payroll->hari_kerja,
            $payroll->jam_kerja,
            $payroll->jam_lembur,
            $payroll->jumlah_jam_terlambat,
            $payroll->tambahan_shift_malam,
            $payroll->gaji_pokok,
            $payroll->gaji_lembur,
            $payroll->gaji_bpjs,
            $payroll->bonus1x,
            $payroll->potongan1x,
            $payroll->total_noscan,
            $payroll->denda_lupa_absen,
            $payroll->pajak,
            $payroll->jht,
            $payroll->jp,
            $payroll->jkk,
            $payroll->jkm,
            $payroll->kesehatan,
            $payroll->iuran_air,
            $payroll->iuran_locker,
            $payroll->status_karyawan,
            $payroll->total,

        ];
    }

    public function headings(): array
    {
        return [
            [
                'Payroll'
            ],
            [
                'ID Karyawan',
                'Nama',
                'Nama Bank',
                'No. Rekening',
                'Jabatan',
                'Company',
                'Placement',
                'Metode Penggajian',
                'Total Hari Kerja',
                'Total Jam Kerja (bersih)',
                'Jam Lembur',
                'Jumlah Jam Terlambat',
                'Tambahan Shift Malam',
                'Gaji Pokok',
                'Gaji Lembur',
                'Gaji BPJS',
                'Bonus/U. Makan',
                'Potongan 1X Potong',
                'Total No Scan',
                'Denda Lupa Absen',
                'Pajak',
                'JHT',
                'JP',
                'JKK',
                'JKM',
                'Kesehatan',
                'Iuran Air',
                'Iuran Locker',
                'Status Karyawan',
                'Total',

            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Gaji Karyawan';
    }


    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'D' => "0",
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'V' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'W' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Z' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AA' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AD' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {
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
