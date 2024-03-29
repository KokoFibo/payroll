<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use App\Livewire\Karyawanindexwr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\RequiredIf;

class Updatekaryawanwr extends Component
{
    public $id;
    public $id_karyawan, $nama, $email, $hp, $telepon, $tempat_lahir, $tanggal_lahir, $gender, $status_pernikahan, $golongan_darah, $agama, $etnis;
    public $jenis_identitas, $no_identitas, $alamat_identitas, $alamat_tinggal;
    public $status_karyawan, $tanggal_bergabung, $tanggal_resigned, $tanggal_blacklist,  $company, $placement,  $departemen, $jabatan, $level_jabatan, $nama_bank, $nomor_rekening;
    public $gaji_pokok, $gaji_overtime, $gaji_shift_malam_satpam, $metode_penggajian,  $bonus, $tunjangan_jabatan, $tunjangan_bahasa;
    public $tunjangan_skill, $tunjangan_lembur_sabtu, $tunjangan_lama_kerja,  $iuran_air, $iuran_locker, $denda, $gaji_bpjs, $potongan_JHT, $potongan_JP, $potongan_JKK, $potongan_JKM;
    public  $potongan_kesehatan, $update;
    public  $no_npwp, $ptkp, $status_off;
    public $kontak_darurat, $hp1, $hp2;
    public $tanggungan;



    public function mount($id)
    {
        $this->status_off = false;
        $this->update = true;
        $this->id = $id;
        $data = Karyawan::find($id);
        $this->id_karyawan = $data->id_karyawan;
        $this->nama = $data->nama;
        $this->email = trim($data->email);
        $this->hp = $data->hp;
        $this->telepon = $data->telepon;
        $this->tempat_lahir = $data->tempat_lahir;
        //  $this->tanggal_lahir = $data->tanggal_lahir;
        $this->tanggal_lahir =  date('d M Y', strtotime($data->tanggal_lahir));

        $this->gender = $data->gender;
        $this->status_pernikahan = trim($data->status_pernikahan);
        $this->golongan_darah = trim($data->golongan_darah);
        $this->agama = trim($data->agama);
        $this->etnis = trim($data->etnis);
        $this->kontak_darurat = trim($data->kontak_darurat);
        $this->hp1 = trim($data->hp1);
        $this->hp2 = trim($data->hp2);


        // Identitas
        $this->jenis_identitas = trim($data->jenis_identitas);
        $this->no_identitas = $data->no_identitas;
        $this->alamat_identitas = $data->alamat_identitas;
        $this->alamat_tinggal = $data->alamat_tinggal;

        //Data Kepegawaian
        $this->status_karyawan = trim($data->status_karyawan);
        $this->tanggal_bergabung =  date('d M Y', strtotime($data->tanggal_bergabung));
        $this->tanggal_resigned = $data->tanggal_resigned;
        $this->tanggal_blacklist = $data->tanggal_blacklist;

        $this->company = trim($data->company);
        $this->placement = trim($data->placement);
        $this->departemen = trim($data->departemen);
        $this->jabatan = trim($data->jabatan);
        $this->level_jabatan = trim($data->level_jabatan);
        $this->nama_bank = trim($data->nama_bank);
        $this->nomor_rekening = $data->nomor_rekening;

        //Payroll
        $this->metode_penggajian = trim($data->metode_penggajian);
        //  $this->gaji_pokok = $data->gaji_pokok;
        $this->gaji_pokok = $data->gaji_pokok;
        $this->gaji_overtime = $data->gaji_overtime;
        $this->gaji_shift_malam_satpam = $data->gaji_shift_malam_satpam;
        $this->bonus = $data->bonus;
        $this->tunjangan_jabatan = $data->tunjangan_jabatan;
        $this->tunjangan_bahasa = $data->tunjangan_bahasa;
        $this->tunjangan_skill = $data->tunjangan_skill;
        $this->tunjangan_lembur_sabtu = $data->tunjangan_lembur_sabtu;
        $this->tunjangan_lama_kerja = $data->tunjangan_lama_kerja;
        $this->iuran_air = $data->iuran_air;
        $this->denda = $data->denda;
        $this->iuran_locker = $data->iuran_locker;
        $this->gaji_bpjs = $data->gaji_bpjs;
        $this->potongan_JHT = $data->potongan_JHT;
        $this->potongan_JP = $data->potongan_JP;
        $this->potongan_JKK = $data->potongan_JKK;
        $this->potongan_JKM = $data->potongan_JKM;
        $this->potongan_kesehatan = $data->potongan_kesehatan;
        $this->tanggungan = $data->tanggungan;
        $this->no_npwp = $data->no_npwp;
        $this->ptkp = $data->ptkp;
    }

    // Cara benerin email unique agar bisa di update
    public function rules()
    {
        return [

            'id_karyawan' => 'required',
            'nama' => 'required',
            'email' => 'email|nullable|unique:karyawans,email,' . $this->id,
            'tanggal_lahir' => 'date|before:today|required',
            // PRIBADI
            'hp' => 'nullable',
            'telepon' => 'nullable',
            'tempat_lahir' => 'required',
            'gender' => 'required',
            'status_pernikahan' => 'nullable',
            'golongan_darah' => 'nullable',
            'agama' => 'nullable',
            'etnis' => 'required',
            'kontak_darurat' => 'nullable',
            'hp1' => 'nullable',
            'hp2' => 'nullable',

            // IDENTITAS
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'alamat_identitas' => 'required',
            'alamat_tinggal' => 'required',
            // KEPEGAWAIAN
            'status_karyawan' => 'required',
            'tanggal_resigned' => new RequiredIf($this->status_karyawan == 'Resigned'),
            'tanggal_blacklist' => new RequiredIf($this->status_karyawan == 'Blacklist'),
            'tanggal_bergabung' => 'date|before:tomorrow|required',
            'company' => 'required',
            'placement' => 'required',
            'departemen' => 'required',
            'jabatan' => 'required',
            'level_jabatan' => 'nullable',
            'nama_bank' => 'nullable',
            'nomor_rekening' => 'nullable',
            // PAYROLL
            'metode_penggajian' => 'required',
            'gaji_pokok' => 'numeric|required',
            'gaji_overtime' => 'numeric|required',
            'gaji_shift_malam_satpam' => 'numeric',
            'bonus' => 'numeric|nullable',
            'tunjangan_jabatan' => 'numeric|nullable',
            'tunjangan_bahasa' => 'numeric|nullable',
            'tunjangan_skill' => 'numeric|nullable',
            'tunjangan_lembur_sabtu' => 'numeric|nullable',
            'tunjangan_lama_kerja' => 'numeric|nullable',
            'iuran_air' => 'numeric|required',
            'denda' => 'numeric|nullable',
            'iuran_locker' => 'numeric|nullable',
            'gaji_bpjs' => 'nullable',
            'potongan_JHT' => 'nullable',
            'potongan_JP' => 'nullable',
            'potongan_JKK' => 'nullable',
            'potongan_JKM' => 'nullable',
            'potongan_kesehatan' => 'nullable',
            'tanggungan' => 'nullable',
            'no_npwp' => 'nullable',
            'ptkp' => 'nullable',


        ];
    }


    public function update1()
    {
        $this->gaji_pokok = convert_numeric($this->gaji_pokok);
        $this->gaji_overtime = convert_numeric($this->gaji_overtime);
        $this->gaji_shift_malam_satpam = convert_numeric($this->gaji_shift_malam_satpam);
        $this->bonus = convert_numeric($this->bonus);
        $this->tunjangan_jabatan = convert_numeric($this->tunjangan_jabatan);
        $this->tunjangan_bahasa = convert_numeric($this->tunjangan_bahasa);
        $this->tunjangan_skill = convert_numeric($this->tunjangan_skill);
        $this->tunjangan_lembur_sabtu = convert_numeric($this->tunjangan_lembur_sabtu);
        $this->tunjangan_lama_kerja = convert_numeric($this->tunjangan_lama_kerja);
        $this->iuran_air = convert_numeric($this->iuran_air);
        $this->iuran_locker = convert_numeric($this->iuran_locker);
        $this->gaji_bpjs = convert_numeric($this->gaji_bpjs);
        $this->denda = convert_numeric($this->denda);
        $this->validate();
        $this->tanggal_lahir = date('Y-m-d', strtotime($this->tanggal_lahir));
        $this->tanggal_bergabung = date('Y-m-d', strtotime($this->tanggal_bergabung));
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
        $data->etnis = $this->etnis;
        $data->kontak_darurat = $this->kontak_darurat;
        $data->hp1 = $this->hp1;
        $data->hp2 = $this->hp2;

        // Identitas
        $data->jenis_identitas = $this->jenis_identitas;
        $data->no_identitas = $this->no_identitas;
        $data->alamat_identitas = titleCase($this->alamat_identitas);
        $data->alamat_tinggal = titleCase($this->alamat_tinggal);
        // Data Kepegawaian
        $data->status_karyawan = $this->status_karyawan;
        $data->tanggal_bergabung = $this->tanggal_bergabung;
        if ($this->status_karyawan == 'Resigned') {
            $data->tanggal_blacklist = null;
            $data->tanggal_resigned = $this->tanggal_resigned;
        } elseif ($this->status_karyawan == 'Blacklist') {

            $data->tanggal_resigned = null;
            $data->tanggal_blacklist = $this->tanggal_blacklist;
        } else {
            $data->tanggal_blacklist = null;
            $data->tanggal_resigned = null;
        }

        $data->company = $this->company;
        $data->placement = $this->placement;
        $data->departemen = $this->departemen;
        $data->jabatan = $this->jabatan;
        $data->level_jabatan = $this->level_jabatan;
        $data->nama_bank = $this->nama_bank;
        $data->nomor_rekening = $this->nomor_rekening;

        // Payroll
        $data->gaji_pokok = $this->gaji_pokok;
        $data->gaji_overtime = $this->gaji_overtime;
        $data->gaji_shift_malam_satpam = $this->gaji_shift_malam_satpam;
        $data->metode_penggajian = $this->metode_penggajian;
        $data->bonus = $this->bonus;
        $data->tunjangan_jabatan = $this->tunjangan_jabatan;
        $data->tunjangan_bahasa = $this->tunjangan_bahasa;
        $data->tunjangan_skill = $this->tunjangan_skill;
        $data->tunjangan_lembur_sabtu = $this->tunjangan_lembur_sabtu;
        $data->tunjangan_lama_kerja = $this->tunjangan_lama_kerja;
        $data->iuran_air = $this->iuran_air;
        $data->iuran_locker = $this->iuran_locker;
        $data->gaji_bpjs = $this->gaji_bpjs;
        $data->potongan_JHT = $this->potongan_JHT;
        $data->potongan_JP = $this->potongan_JP;
        $data->potongan_JKK = $this->potongan_JKK;
        $data->potongan_JKM = $this->potongan_JKM;
        $data->potongan_kesehatan = $this->potongan_kesehatan;
        $data->tanggungan = $this->tanggungan;
        $data->no_npwp = $this->no_npwp;
        $data->ptkp = $this->ptkp;




        $data->denda = $this->denda;
        $data->save();

        $dataUser = User::where('username', $data->id_karyawan)->first();
        // if ( $dataUser->id != null ) {
        if ($dataUser->id) {
            $user = User::find($dataUser->id);
            $user->name = titleCase($this->nama);
            $user->email = trim($this->email, ' ');
            $user->save();
            $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
            $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
            $this->dispatch('success', message: 'Data Karyawan Sudah di Update');
        } else {
            $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
            $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
            $this->dispatch('info', message: 'Data Karyawan Sudah di Update, User tidak terupdate');
        }
        get_data_karyawan();
    }


    public function exit()
    {
        // $this->reset();
        return redirect()->to('/karyawanindex');
    }

    public function render()
    {

        return view('livewire.updatekaryawanwr')
            ->layout('layouts.appeloe');
    }
}
