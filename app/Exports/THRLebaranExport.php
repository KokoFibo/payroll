<?php

namespace App\Exports;

use App\Models\Karyawan;
use App\Models\Payroll;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class THRLebaranExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    ShouldAutoSize
{
    protected $cutOffDate;

    public function __construct($cutOffDate)
    {
        $this->cutOffDate = $cutOffDate;
    }

    /*
    |--------------------------------------------------------------------------
    | Collection
    |--------------------------------------------------------------------------
    */
    public function collection()
    {
        return Karyawan::with(['placement', 'company', 'department', 'jabatan'])
            ->whereNotIn('etnis', ['China', 'Tionghoa'])
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->where('tanggal_bergabung', '<', Carbon::parse($this->cutOffDate)->subDays(30))
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Headings
    |--------------------------------------------------------------------------
    */
    public function headings(): array
    {
        return [
            'ID Karyawan',
            'Nama Karyawan',
            'Placement',
            'Company',
            'Department',
            'Jabatan',
            'Etnis',
            'Status',
            'Tanggal Bergabung',
            'Lama Bergabung (Bulan)',
            'Lama Bergabung (Hari)',
            'Metode Penggajian',
            'Gaji Pokok',
            'THR',
            'Item',
            'Penyesuaian THR',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Mapping
    |--------------------------------------------------------------------------
    */
    public function map($k): array
    {
        $tanggalMasuk = Carbon::parse($k->tanggal_bergabung);
        $cutoff       = Carbon::parse($this->cutOffDate);

        $masaBulan = $tanggalMasuk->diffInMonths($cutoff);
        $masaHari  = $tanggalMasuk->diffInDays($cutoff);

        $thr = $this->hitungTHR($k->tanggal_bergabung, $k->gaji_pokok, $k->id_karyawan);

        // Tentukan Item berdasarkan tabel
        if ($masaBulan >= 12) {
            $item = '-';
        } elseif ($masaBulan >= 6 && $masaBulan <= 11) {
            $item = '1 Unit HP';
        } else {
            $item = '-';
        }

        $penyesuaian = 0;

        return [
            $k->id_karyawan,
            $k->nama,
            $k->placement->placement_name ?? '',
            $k->company->company_name ?? '',
            $k->department->nama_department ?? '',
            $k->jabatan->nama_jabatan ?? '',
            $k->etnis,
            $k->status_karyawan,
            $tanggalMasuk->format('d-m-Y'),
            $masaBulan,
            $masaHari,
            $k->metode_penggajian,
            $k->gaji_pokok,
            $thr,
            $item,
            $penyesuaian,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Perhitungan THR sesuai tabel
    |--------------------------------------------------------------------------
    */
    private function hitungTHR($tanggal_bergabung, $gaji_pokok, $id_karyawan)
    {
        $masaKerja = Carbon::parse($tanggal_bergabung)
            ->diffInMonths(Carbon::parse($this->cutOffDate));

        $thrTable = [
            1  => 100000,
            2  => 200000,
            3  => 300000,
            4  => 400000,
            5  => 550000,
            6  => 100000,
            7  => 250000,
            8  => 400000,
            9  => 600000,
            10 => 800000,
            11 => 1000000,
        ];

        if ($masaKerja >= 12) {
            $data = Payroll::whereMonth('date', 3)->whereYear('date', 2025)->where('id_karyawan', $id_karyawan)->first();
            return $data->gaji_pokok ?? 0; // 1 bulan gaji penuh tahun lalu
        }

        return $thrTable[$masaKerja] ?? 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Styling Excel
    |--------------------------------------------------------------------------
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Tambah header di atas
                $event->sheet->insertNewRowBefore(1, 1);
                $event->sheet->setCellValue('A1', 'Perincian THR Lebaran untuk OS');
                $event->sheet->mergeCells('A1:P1');

                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getStyle('A1')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Bold heading
                $event->sheet->getStyle('A2:P2')->getFont()->setBold(true);

                // Freeze header
                $event->sheet->freezePane('A3');

                // Rata kanan kolom angka
                foreach (['M', 'N', 'P'] as $col) {
                    $event->sheet->getStyle($col . ':' . $col)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                // Format angka ribuan (bisa di SUM)
                foreach (['M', 'N', 'P'] as $col) {
                    $event->sheet->getStyle($col . '3:' . $col . '1000')
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }
            },
        ];
    }
}
