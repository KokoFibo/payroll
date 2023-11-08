<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class KaryawanExport implements FromCollection, FromQuery,  ShouldAutoSize, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $selectedAll;
     public function __construct($selectedAll)
    {
        $this->selectedAll = $selectedAll;
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'M' => Text::make('No. Iden', 'no_identitas'),
    //         'X' => Text::make('No. Rekening', 'nomor_rekening'),
    //         // 'D' => Date::make('Date of Birth', 'dob'),
    //     ];
    // }

    public function collection()
    {
        // return Karyawan::all();

        // return Karyawan::where('status_karyawan', 'Karyawan Tetap')->get(['id_karyawan', 'nama', 'email', 'hp', 'telepon', 'tempat_lahir', 'tanggal_lahir', 'gender', 'status_pernikahan', 'golongan_darah', 'agama', 'jenis_identitas', 'no_identitas', 'alamat_identitas', 'alamat_tinggal', 'status_karyawan', 'tanggal_bergabung', 'company', 'departemen', 'jabatan', 'level_jabatan', 'metode_penggajian', 'gaji_pokok', 'gaji_overtime', 'bonus', 'tunjangan_jabatan', 'tunjangan_bahasa', 'tunjangan_skill', 'tunjangan_lembur_sabtu', 'tunjangan_lama_kerja', 'iuran_air', 'potongan_seragam', 'denda']);
        return Karyawan::whereIn('id', $this->selectedAll)->get([
            'id_karyawan',
            'nama',
            'email',
            'tanggal_lahir',
            'hp',
            'telepon',
            'tempat_lahir',
            'gender',
            'status_pernikahan',
            'golongan_darah',
            'agama',
            'jenis_identitas',
            'no_identitas',
            'alamat_identitas',
            'alamat_tinggal',
            'status_karyawan',
            'tanggal_bergabung',
            'company',
            'placement',
            'departemen',
            'jabatan',
            'level_jabatan',
            'nama_bank',
            'nomor_rekening',
            'metode_penggajian',
            'gaji_pokok',
            'gaji_overtime',
            'bonus',
            'tunjangan_jabatan',
            'tunjangan_bahasa',
            'tunjangan_skill',
            'tunjangan_lembur_sabtu',
            'tunjangan_lama_kerja',
            'iuran_air',
            'denda',
            'potongan_seragam',
            'potongan_JHT',
            'potongan_JP',
            'potongan_kesehatan'
             ]);
    }
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Tanggal Lahir',
            'HP',
            'Telepon',
            'Tempat Lahir',
            'Gender',
            'Status Pernikahan',
            'Golongan Darah',
            'Agama',
            'Jenis Identitas',
            'No. Identitas',
            'Alamat Identitas',
            'Alamat Tinggal',
            'Status Karyawan',
            'Tanggal Bergabung',
            'Company',
            'Placement',
            'Departemen',
            'Jabatan',
            'Level Jabatan',
            'Nama Bank',
            'Nomor Rekening',
            'Metode Penggajian',
            'Gaji Pokok',
            'Gaji Overtime',
            'Bonus',
            'Tunjangan Jabatan',
            'Tunjangan Bahasa',
            'Tunjangan Skill',
            'Tunjangan Lembur Sabtu',
            'Tunjangan Lama Kerja',
            'Iuran Air',
            'Denda',
            'Potongan Seragam',
            'P. JHT',
            'P. JP',
            'P. Kesehatan',
            ];
    }
    public function query()
    {
        return Karyawan::all();
        // return Karyawan::where('status_karyawan', 'Karyawan Tetap')->get();
        // return Karyawan::whereIn('id', $this->selectedId);
        // return Karyawan::whereIn('id', $this->selectedAll);
    }
// ================================
            // 'id',
            // 'Nama',
            // 'Email',
            // 'Tanggal Lahir',
            // 'HP',
            // 'Telepon',
            // 'Tempat Lahir',
            // 'Gender',
            // 'Status Pernikahan',
            // 'Golongan Darah',
            // 'Agama',
            // 'Jenis Identitas',
            // 'No. Identitas',
            // 'Alamat Identitas',
            // 'Alamat Tinggal',
            // 'Status Karyawan',
            // 'Tanggal Bergabung',
            // 'Company',
            // 'Placement',
            // 'Departemen',
            // 'Jabatan',
            // 'Level Jabatan',
            // 'Nama Bank',
            // 'Nomor Rekening',
            // 'Metode Penggajian',
            // 'Gaji Pokok',
            // 'Gaji Overtime',
            // 'Bonus',
            // 'Tunjangan Jabatan',
            // 'Tunjangan Bahasa',
            // 'Tunjangan Skill',
            // 'Tunjangan Lembur Sabtu',
            // 'Tunjangan Lama Kerja',
            // 'Iuran Air',
            // 'Denda',
            // 'Potongan Seragam',
            // 'P. JHT',
            // 'P. JP',
            // 'P. Kesehatan'



// ====================================
}
