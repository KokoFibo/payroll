<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class Karyawanwr extends Component
{
    public $id;
    public $id_karyawan, $nama, $email, $hp, $telepon, $tempat_lahir, $tanggal_lahir, $gender, $status_pernikahan, $golongan_darah, $agama;
    public $jenis_identitas, $no_identitas, $alamat_identitas, $alamat_tinggal;
    public $status_karyawan, $tanggal_bergabung, $company, $placement, $departemen, $jabatan, $level_jabatan, $nama_bank, $no_rekening;

    public $metode_penggajian, $gaji_pokok, $gaji_overtime;
    public $bonus, $tunjangan_jabatan, $tunjangan_bahasa;
    public $tunjangan_skill, $tunjangan_lembur_sabtu, $tunjangan_lama_kerja;
    public $iuran_air, $denda, $potongan_seragam, $potongan_JHT, $potongan_JP, $potongan_kesehatan;

    public function mount()
    {
        $this->id_karyawan = getNextIdKaryawan();
        $this->status_karyawan = 'PKWT';
        $this->tanggal_bergabung = now()->toDateString();
    }

    protected $rules = [

            'id_karyawan' => 'required',
            'nama' => 'required',
            'email' => 'email|required',
            'tanggal_lahir' => 'date|before:today|required',
            // PRIBADI
            'hp' => 'required',
            'telepon' => 'nullable',
            'tempat_lahir' => 'required',
            'gender' => 'required',
            'status_pernikahan' => 'nullable',
            'golongan_darah' => 'nullable',
            'agama' => 'nullable',
            // IDENTITAS
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'alamat_identitas' => 'required',
            'alamat_tinggal' => 'required',
            // KEPEGAWAIAN
            'status_karyawan' => 'required',
            'tanggal_bergabung' => 'required',
            'company' => 'required',
            'placement' => 'required',
            'departemen' => 'required',
            'jabatan' => 'required',
            'level_jabatan' => 'nullable',
            'nama_bank' => 'nullable',
            'no_rekening' => 'nullable',
            // PAYROLL
            'metode_penggajian' => 'required',
            'gaji_pokok' => 'numeric|required',
            'gaji_overtime' => 'numeric|required',
            'bonus' => 'numeric|nullable',
            'tunjangan_jabatan' => 'numeric|nullable',
            'tunjangan_bahasa' => 'numeric|nullable',
            'tunjangan_skill' => 'numeric|nullable',
            'tunjangan_lembur_sabtu' => 'numeric|nullable',
            'tunjangan_lama_kerja' => 'numeric|nullable',
            'iuran_air' => 'numeric|required',
            'denda' => 'numeric|nullable',
            'potongan_seragam' => 'numeric|nullable',
            'potongan_JHT' => 'nullable',
            'potongan_JP' => 'nullable',
            'potongan_kesehatan' => 'nullable',
    ];

    public function save()
    {
        $this->validate();

        $ada = Karyawan::where('id_karyawan', $this->id_karyawan)->first();

        if ($ada) {
            $this->id = $ada->id;
            $this->update();
        } else {
            $this->id = '';

            $data = new Karyawan();
            // Data Pribadi
            $data->id_karyawan = $this->id_karyawan;
            $data->nama = titleCase($this->nama);
            $data->email = trim($this->email, ' ');
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
            $data->company = $this->company;
            $data->placement = $this->placement;
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
            $data->potongan_JHT = $this->potongan_JHT;
            $data->potongan_JP = $this->potongan_JP;
            $data->potongan_kesehatan = $this->potongan_kesehatan;

            $data->denda = $this->denda;

            try {
                $data->save();
                // create user
                User::create([
                    'name' => titleCase($this->nama),
                    'email' => trim($this->email, ' '),
                    'username' => $this->id_karyawan,
                    'role' => 1,
                    'remember_token' => Str::random(10),
                    'password' => Hash::make(generatePassword($this->tanggal_lahir)),
                ]);
                $this->dispatch('success', message: 'Data Karyawan Sudah di Save');
            } catch (\Exception $e) {
                $this->dispatch('error', message: $e->getMessage());
                return $e->getMessage();
            }

            // $this->reset();
        }
    }

    public function update()
    {
        // $this->validate();

        try {
            $data = Karyawan::find($this->id);

            $data->id_karyawan = $this->id_karyawan;
            $data->nama = titleCase($this->nama);
            $data->email = trim($this->email, ' ');
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
            $data->company = $this->company;
            $data->placement = $this->placement;
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
            $data->potongan_JHT = $this->potongan_JHT;
            $data->potongan_JP = $this->potongan_JP;
            $data->potongan_kesehatan = $this->potongan_kesehatan;
            $data->denda = $this->denda;
            $data->save();
            $this->dispatch('success', message: 'Data Karyawan Sudah di Update');
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
            return $e->getMessage();
        }
    }

    public function clear()
    {
        $this->reset();
        $this->id_karyawan = getNextIdKaryawan();
    }

    public function exit()
    {
        $this->reset();
        return redirect()->to('/karyawanindex');
    }

    public function render()
    {
        return view('livewire.karyawanwr')->layout('layouts.appeloe');
    }
}
