<?php

namespace App\Exports;

use App\Models\Karyawan;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use Style\Alignment;
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

class KaryawanByDepartmentExport implements FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $search_department, $search_placement;

    public function __construct($search_placement, $search_department)
    {
        $this->search_department = $search_department;
        $this->search_placement = $search_placement;
    }

    public function query()
    {
        $statuses = ['PKWT', 'PKWTT', 'Dirumahkan'];

        switch ($this->search_placement) {
            case 1:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YCME');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;

            case 2:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;

            case 3:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->whereIn('placement', ['YIG', 'YSM']);
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;

            case 4:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YIG');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;
            case 5:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YSM');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;
            case 6:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YAM');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;
            case 7:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV SMOOT');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;
            case 8:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV OFFERO');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;
            case 9:
                $query = Karyawan::whereIn('status_karyawan', $statuses)
                    ->where('placement', 'YEV SUNRA');
                if ($this->search_department != '') {
                    $query->where('departemen', $this->search_department);
                }
                return $query;
        }
    }

    public function map($karyawan): array
    {
        return [
            $karyawan->id_karyawan, $karyawan->nama, $karyawan->company, $karyawan->placement, $karyawan->jabatan,
            $karyawan->status_karyawan, $karyawan->tanggal_bergabung, $karyawan->metode_penggajian, $karyawan->gaji_pokok, $karyawan->gaji_overtime, $karyawan->gaji_bpjs
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => '0',

            'G' => NumberFormat::FORMAT_DATE_XLSX15,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,


        ];
    }

    public function headings(): array
    {
        return [['Data Karyawan'], [
            'ID Karyawan', 'Nama', 'Company', 'Placement', 'Jabatan',
            'Status Karyawan', 'Tanggal Bergabung', 'Metode Penggajian', 'Gaji Pokok', 'Gaji Lembur', 'Gaji BPJS',
        ]];
    }

    public function title(): string
    {
        return 'Data Karyawan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            '1' => ['font' => ['bold' => true]],
            '2' => ['font' => ['bold' => true]],
            '1' => ['font' => ['size' => 24]],
            // 'C' => ['text' => ['align' => 'center']],
        ];
        // atau bisa juga
        // $sheet->getStyle('1')->getFont()->setBold(true);
        // $sheet->getStyle('2')->getFont()->setBold(true);
    }
}
