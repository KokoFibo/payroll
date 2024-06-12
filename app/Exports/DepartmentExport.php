<?php

namespace App\Exports;

use App\Models\Payroll;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DepartmentExport implements FromView,  ShouldAutoSize, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;


    protected $selected_departemen, $status, $month, $year;
    public function __construct($selected_departemen, $status, $month, $year)
    {
        $this->selected_departemen = $selected_departemen;
        $this->status = $status;
        $this->month = $month;
        $this->year = $year;
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

        if ($this->selected_departemen == 0) {
            $data = Payroll::whereIn('status_karyawan', $statuses)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->orderBy('id_karyawan', 'asc')->get();
        } else {
            $data = Payroll::whereIn('status_karyawan', $statuses)
                ->where('departemen', $this->selected_departemen)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->orderBy('id_karyawan', 'asc')->get();
        }
        $header_text = 'Perincian Payroll untuk Department ' . $this->selected_departemen . ' ' . nama_bulan($this->month) . ' ' . $this->year;

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
