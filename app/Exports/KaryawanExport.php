<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KaryawanExport implements FromCollection, FromQuery, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Karyawan::all();

        return Karyawan::get(['id_karyawan', 'nama', 'email', 'hp', 'telepon', 'tempat_lahir', 'tanggal_lahir', 'gender', 'status_pernikahan', 'golongan_darah', 'agama', 'jenis_identitas', 'no_identitas', 'alamat_identitas', 'alamat_tinggal', 'status_karyawan', 'tanggal_bergabung', 'branch', 'departemen', 'jabatan', 'level_jabatan', 'metode_penggajian', 'gaji_pokok', 'gaji_overtime', 'uang_makan', 'bonus', 'tunjangan_jabatan', 'tunjangan_bahasa', 'tunjangan_skill', 'tunjangan_lembur_sabtu', 'tunjangan_lama_kerja', 'iuran_air', 'potongan_seragam', 'denda', 'potongan_pph21', 'potongan_bpjs']);
    }
    public function headings(): array
    {
        return ['Id', 'Nama', 'Email', 'Handphone', 'Telepon', 'Tempat Lahir', 'Tanggal Lahir', 'Gender', 'Status Pernikahan', 'Golongan Darah', 'Agama', 'Jenis Identitas', 'No Identitas', 'Alamat Identitas', 'Alamat Tinggal', 'Status Karyawan', 'Tanggal Bergabung', 'Branch', 'Department', 'Jabatan', 'Level Jabatan', 'Metode Penggajian', 'Gaji Pokok', 'Gaji Overtime', 'Uang Makan', 'Bonus', 'Tunjangan Jabatan', 'Tunjangan Bahasa', 'Tunjangan Skill', 'tunjangan Lembur Sabtu', 'Tunjangan Lama Kerja', 'Iuran Air', 'Potongan Seragam', 'Denda', 'Potongan Pph 21', 'Potongan BPJS'];
    }
    public function query()
    {
        return Karyawan::all();
    }
}
