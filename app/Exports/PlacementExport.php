<?php

namespace App\Exports;

use App\Models\Payroll;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlacementExport implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    protected $data;

    protected $selected_placement, $status, $month, $year;
    public function __construct($selected_placement, $status, $month, $year)
    {
        $this->selected_placement = $selected_placement;
        $this->status = $status;
        $this->month = $month;
        $this->year = $year;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            2    => ['font' => ['bold' => true]],
            // Styling a specific cell by coordinate.

            // Styling an entire column.
            2  => ['font' => ['size' => 15]],
            // 2 => ['font' => ['italic' => true]],
            3  => ['font' => ['size' => 12]],


        ];
    }

    public function view(): View
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
        switch ($this->selected_placement) {
            case 0:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 1:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YCME')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 2:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 3:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->whereIn('placement', ['YIG', 'YSM'])
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 4:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'ASB')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 5:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'DPA')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 6:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YCME')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 7:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 8:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YIG')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;

            case 9:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YSM')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;
            case 10:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YAM')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;
            case 11:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV SMOOT')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;
            case 12:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV OFFERO')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;
            case 13:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV SUNRA')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;
            case 14:
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV AIMA')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                break;
        }
        $header_text = 'Perincian Payroll untuk Placement ' . $this->selected_placement . ' ' . nama_bulan($this->month) . ' ' . $this->year;

        return view('payroll_excel_view', [
            'data' => $data,
            'header_text' => $header_text
        ]);
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'D' => "0",

        ];
    }
}
