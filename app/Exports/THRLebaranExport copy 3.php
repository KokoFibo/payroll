<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class THRLebaranExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $cutOffDate;

    public function __construct($cutOffDate)
    {
        $this->cutOffDate = $cutOffDate;
    }

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
    | Styling Excel
    |--------------------------------------------------------------------------
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Tambahkan 1 baris di paling atas
                $event->sheet->insertNewRowBefore(1, 1);

                // Judul laporan
                $event->sheet->setCellValue('A1', 'Perincian THR Lebaran untuk OS');
                $event->sheet->mergeCells('A1:P1'); // 16 kolom (A–P)

                // Styling judul
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getStyle('A1')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Bold header tabel
                $event->sheet->getStyle('A2:P2')->getFont()->setBold(true);

                // Freeze header
                $event->sheet->freezePane('A3');

                // Rata kanan kolom Gaji Pokok (M)
                $event->sheet->getStyle('M:M')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Rata kanan kolom THR (N)
                $event->sheet->getStyle('N:N')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Rata kanan kolom Penyesuaian (P)
                $event->sheet->getStyle('P:P')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Format angka dengan comma separator
                $event->sheet->getStyle('M3:M1000')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $event->sheet->getStyle('N3:N1000')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Format angka dengan comma separator (bisa di SUM)
                $event->sheet->getStyle('M3:P1000')
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
            },
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Header Kolom
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
    | Mapping Data
    |--------------------------------------------------------------------------
    */
    public function map($k): array
    {
        $tanggalMasuk = Carbon::parse($k->tanggal_bergabung);
        $cutoff       = Carbon::parse($this->cutOffDate);

        $masaBulan = $tanggalMasuk->diffInMonths($cutoff);
        $masaHari  = $tanggalMasuk->diffInDays($cutoff);

        $thr = $this->hitungTHR($masaBulan, $k->gaji_pokok);

        // ===== ITEM LOGIC =====
        if ($masaBulan >= 12) {
            $item = 'THR 1 Bulan Gaji';
        } elseif ($masaBulan > 0) {
            $item = 'THR Reward Masa Kerja';
        } else {
            $item = 'Tidak Mendapat THR';
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
    | Perhitungan THR
    |--------------------------------------------------------------------------
    */
    private function hitungTHR($masaKerja, $gaji_pokok)
    {
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
            return $gaji_pokok;
        }

        return $thrTable[$masaKerja] ?? 0;
    }
}
