<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;

class Updatekaryawanwr extends Component {
    public $id;
    public $id_karyawan, $nama, $email, $hp, $telepon, $tempat_lahir, $tanggal_lahir, $gender, $status_pernikahan, $golongan_darah, $agama;
    public $jenis_identitas, $no_identitas, $alamat_identitas, $alamat_tinggal;
    public $status_karyawan, $tanggal_bergabung, $branch, $departemen, $jabatan, $level_jabatan, $nama_bank, $no_rekening;
    public $gaji_pokok, $gaji_overtime, $metode_penggajian,  $bonus, $tunjangan_jabatan, $tunjangan_bahasa;
    public $tunjangan_skill, $tunjangan_lembur_sabtu, $tunjangan_lama_kerja,  $iuran_air, $potongan_seragam, $denda;

    public function mount ( $id ) {
        $this->id = $id;
        $data = Karyawan::find( $id );
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
        $this->nama_bank = $data->nama_bank;
        $this->no_rekening = $data->no_rekening;

        //Payroll
        $this->metode_penggajian = $data->metode_penggajian;
        $this->gaji_pokok = $data->gaji_pokok;
        $this->gaji_overtime = $data->gaji_overtime;
        $this->bonus = $data->bonus;
        $this->tunjangan_jabatan = $data->tunjangan_jabatan;
        $this->tunjangan_bahasa = $data->tunjangan_bahasa;
        $this->tunjangan_skill = $data->tunjangan_skill;
        $this->tunjangan_lembur_sabtu = $data->tunjangan_lembur_sabtu;
        $this->tunjangan_lama_kerja = $data->tunjangan_lama_kerja;
        $this->iuran_air = $data->iuran_air;
        $this->denda = $data->denda;
        $this->potongan_seragam = $data->potongan_seragam;
    }

    public function update() {

        $this->validate( [
            'id_karyawan' => 'required',
            'nama' => 'required',
            'email' => 'email|nullable',
            'tanggal_lahir' => 'date|required',
        ] );

        $data = Karyawan::find( $this->id );
        $data->id_karyawan = $this->id_karyawan;
        $data->nama = titleCase( $this->nama );
        $data->email = trim( $this->email, ' ' );
        $data->hp = $this->hp;
        $data->telepon = $this->telepon;
        $data->tempat_lahir = titleCase($this->tempat_lahir);
        $data->tanggal_lahir = $this->tanggal_lahir;
        $data->gender = $this->gender;
        $data->status_pernikahan = $this->status_pernikahan;
        $data->golongan_darah = $this->golongan_darah;
        $data->agama = $this->agama;
        // Identitas
        $data->jenis_identitas = $this->jenis_identitas;
        $data->no_identitas = $this->no_identitas;
        $data->alamat_identitas = titleCase($this->alamat_identitas);
        $data->alamat_tinggal = titleCase($this->alamat_tinggal);
        // Data Kepegawaian
        $data->status_karyawan = $this->status_karyawan;
        $data->tanggal_bergabung = $this->tanggal_bergabung;
        $data->branch = $this->branch;
        $data->departemen = $this->departemen;
        $data->jabatan = $this->jabatan;
        $data->level_jabatan = $this->level_jabatan;
        $data->nama_bank = $this->nama_bank;
        $data->no_rekening = $this->no_rekening;

        // Payroll
        $data->gaji_pokok = $this->gaji_pokok;
        $data->gaji_overtime = $this->gaji_overtime;
        $data->metode_penggajian = $this->metode_penggajian;
        $data->bonus = $this->bonus;
        $data->tunjangan_jabatan = $this->tunjangan_jabatan;
        $data->tunjangan_bahasa = $this->tunjangan_bahasa;
        $data->tunjangan_skill = $this->tunjangan_skill;
        $data->tunjangan_lembur_sabtu = $this->tunjangan_lembur_sabtu;
        $data->tunjangan_lama_kerja = $this->tunjangan_lama_kerja;
        $data->iuran_air = $this->iuran_air;
        $data->potongan_seragam = $this->potongan_seragam;
        $data->denda = $this->denda;
        $data->save();

        $dataUser = User::where( 'username', $data->id_karyawan )->first();
        // if ( $dataUser->id != null ) {
        if ( $dataUser->id ) {
            $user = User::find( $dataUser->id );
            $user->name = titleCase( $this->nama );
            $user->email = trim( $this->email, ' ' );
            $user->save();
            $this->dispatch( 'success', message: 'Data Karyawan Sudah di Update' );
        }else
        {
            $this->dispatch( 'info', message: 'Data Karyawan Sudah di Update, User tidak terupdate' );
        }
    }


    public function exit () {
        $this->reset();
        return redirect()->to( '/karyawanindex' );
    }

    public function render() {
        return view( 'livewire.updatekaryawanwr' )
        ->layout( 'layouts.appeloe' );
    }
}
