<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;

class Updatekaryawanwr extends Component
{
    public $id;
    public $id_karyawan, $nama, $email, $hp, $telepon, $tempat_lahir, $tanggal_lahir, $gender, $status_pernikahan, $golongan_darah, $agama;
    public $jenis_identitas, $no_identitas, $alamat_identitas, $alamat_tinggal;
    public $status_karyawan, $tanggal_bergabung, $branch, $departemen, $jabatan, $level_jabatan;
    public $gaji_pokok, $gaji_overtime, $metode_penggajian, $uang_makan, $bonus, $tunjangan_jabatan, $tunjangan_bahasa;
    public $tunjangan_skill, $tunjangan_lembur_sabtu, $tunjangan_lama_kerja,  $iuran_air, $potongan_seragam, $denda, $potongan_pph21;
    public $potongan_bpjs, $potongan_ijin_alpa;

    public function mount ($id) {
        $this->id = $id;
        $data = Karyawan::find($id);
        // Data Pribadi
       $this->id_karyawan = $data->id_karyawan;
       $this->nama = $data->nama;
       $this->email = $data->email;
       $this->hp = $data->hp;
       $this->telepon = $data->telepon;
       $this->tempat_lahir = $data->tempat_lahir;
       $this->tanggal_lahir = $data->tanggal_lahir;
       $this->gender = $data->gender;
       $this->status_pernikahan = $data->status_pernikahan;
       $this->golongan_darah = $data->golongan_darah;
       $this->agama = $data->agama;

       // Identitas
        $this->jenis_identitas = $data->jenis_identitas;
        $this->no_identitas = $data->no_identitas;
        $this->alamat_identitas = $data->alamat_identitas;
        $this->alamat_tinggal = $data->alamat_tinggal;

        //Data Kepegawaian
        $this->status_karyawan = $data->status_karyawan;
        $this->tanggal_bergabung = $data->tanggal_bergabung;
        $this->branch = $data->branch;
        $this->departemen = $data->departemen;
        $this->jabatan = $data->jabatan;
        $this->level_jabatan = $data->level_jabatan;


        //Payroll
        $this->metode_penggajian = $data->metode_penggajian;
        $this->gaji_pokok = $data->gaji_pokok;
        $this->gaji_overtime = $data->gaji_overtime;
        $this->uang_makan = $data->uang_makan;
        $this->bonus = $data->bonus;
        $this->tunjangan_jabatan = $data->tunjangan_jabatan;
        $this->tunjangan_bahasa = $data->tunjangan_bahasa;
        $this->tunjangan_skill = $data->tunjangan_skill;
        $this->tunjangan_lembur_sabtu = $data->tunjangan_lembur_sabtu;
        $this->tunjangan_lama_kerja = $data->tunjangan_lama_kerja;
        $this->iuran_air = $data->iuran_air;
        $this->denda = $data->denda;
        $this->potongan_seragam = $data->potongan_seragam;
        $this->potongan_pph21 = $data->potongan_pph21;
        $this->potongan_bpjs = $data->potongan_bpjs;
    }

    public function update() {

        $this->validate([
            'id_karyawan' => 'required',
            'nama' => 'required',
            'email' => 'email|nullable',
        ]);

        $data = Karyawan::find($this->id);
        $data->id_karyawan = $this->id_karyawan;
        $data->nama = $this->nama;
        $data->email = trim($this->email,' ');
        $data->hp = $this->hp;
        $data->telepon = $this->telepon;
        $data->tempat_lahir = $this->tempat_lahir;
        $data->tanggal_lahir = $this->tanggal_lahir;
        $data->gender = $this->gender;
        $data->status_pernikahan = $this->status_pernikahan;
        $data->golongan_darah = $this->golongan_darah;
        $data->agama = $this->agama;
        // Identitas
        $data->jenis_identitas = $this->jenis_identitas;
        $data->no_identitas = $this->no_identitas;
        $data->alamat_identitas = $this->alamat_identitas;
        $data->alamat_tinggal = $this->alamat_tinggal;
        // Data Kepegawaian
        $data->status_karyawan = $this->status_karyawan;
        $data->tanggal_bergabung = $this->tanggal_bergabung;
        $data->branch = $this->branch;
        $data->departemen = $this->departemen;
        $data->jabatan = $this->jabatan;
        $data->level_jabatan = $this->level_jabatan;
        // Payroll
        $data->gaji_pokok = $this->gaji_pokok;
        $data->gaji_overtime = $this->gaji_overtime;
        $data->metode_penggajian = $this->metode_penggajian;
        $data->uang_makan = $this->uang_makan;
        $data->bonus = $this->bonus;
        $data->tunjangan_jabatan = $this->tunjangan_jabatan;
        $data->tunjangan_bahasa = $this->tunjangan_bahasa;
        $data->tunjangan_skill = $this->tunjangan_skill;
        $data->tunjangan_lembur_sabtu = $this->tunjangan_lembur_sabtu;
        $data->tunjangan_lama_kerja = $this->tunjangan_lama_kerja;
        $data->iuran_air = $this->iuran_air;
        $data->potongan_seragam = $this->potongan_seragam;
        $data->denda = $this->denda;
        $data->potongan_pph21 = $this->potongan_pph21;
        $data->potongan_bpjs = $this->potongan_bpjs;

        $data->save();

        $this->dispatch('success', message: 'Data Karyawan Sudah di Update');


    }
    public function exit () {
        $this->reset();
        return redirect()->to('/karyawanindex');
    }

    public function render()
    {
        return view('livewire.updatekaryawanwr')
        ->layout('layouts.appeloe');
    }
}
