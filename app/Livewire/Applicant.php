<?php

namespace App\Livewire;

use App\Models\Applicantdata;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Applicant extends Component
{
    public $is_registered, $show, $registeredEmail;
    public $nama, $email, $password, $confirm_password, $hp, $telp, $tempat_lahir, $tgl_lahir, $gender;
    public $status_pernikahan, $golongan_darah, $agama, $etnis, $nama_contact_darurat;
    public $contact_darurat_1, $contact_darurat_2, $jenis_identitas, $no_identitas;
    public $alamat_identitas, $alamat_tinggal_sekarang, $file;

    public function submit()
    {
        // validate submit
        $data = Applicantdata::where('email', $this->registeredEmail);
    }



    public function messages()
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'confirm_password.required' => 'Confirm Password wajib diisi.',
            'hp.required' => 'Handphone wajib diisi.',
            'telp.required' => 'Telepon wajib diisi.',
            'tempat_lahir.required' => 'Kota Kelahiran wajib diisi.',
            'tgl_lahir.required' => 'Tanggal Lahir wajib diisi.',
            'gender.required' => 'Gender wajib diisi.',
            'status_pernikahan.required' => 'Status Pernikahan wajib diisi.',
            'golongan_darah.required' => 'Golongan Darah wajib diisi.',
            'agama.required' => 'Agama wajib diisi.',
            'etnis.required' => 'Etnis wajib diisi.',
            'nama_contact_darurat.required' => 'Nama Konta Darurat wajib diisi.',
            'contact_darurat_1.required' => 'Kontak Darurat 1 wajib diisi.',
            'jenis_identitas.required' => 'Jenis Identitas wajib diisi.',
            'no_identitas.required' => 'No Identitas wajib diisi.',
            'alamat_identitas.required' => 'Alamat Identitas wajib diisi.',
            'alamat_tinggal_sekarang.required' => 'Alamat tinggal tekarang wajib diisi.',
            // 'file.required' => 'Gender harus memiliki minimal 5 karakter.',

            'nama.min' => 'Nama minimal 5 karakter.',
            'password.min' => 'Password minimal 6 karakter.',
            'hp.min' => 'Handphone minimal 10 karakter.',
            'telp.min' => 'Telepon minimal 9 karakter.',
            'contact_darurat_1.min' => 'Kontak Darurat 1 minimal 10 karakter.',
            'contact_darurat_2.min' => 'Kontak Darurat 2 minimal 10 karakter.',
            'confirm_password.min' => 'Konfirmasi Password minimal 6 karakter.',
            'confirm_password.same' => 'Konfirmasi Password Berbeda',
            'email.unique' => 'Email ini sudah terdaftar dalam database'
        ];
    }

    public function rules()
    {
        return [
            'nama' => 'required|min:5',
            'email' => 'required|unique:App\Models\User,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
            'hp' => 'required|min:10',
            'telp' => 'required|min:9',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'gender' => 'required',
            'status_pernikahan' => 'required',
            'golongan_darah' => 'required',
            'agama' => 'required',
            'etnis' => 'required',
            'nama_contact_darurat' => 'required',
            'contact_darurat_1' => 'required|min:10',
            'contact_darurat_2' => 'nullable|min:10',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'alamat_identitas' => 'required',
            'alamat_tinggal_sekarang' => 'required',
            'file' => 'nullable',
        ];
    }

    public function save()
    {
        $validated = $this->validate();
        // dd($validated);
        Applicantdata::create([
            'nama' => titleCase($this->nama),
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'hp' => $this->hp,
            'telp' => $this->telp,
            'tempat_lahir' => titleCase($this->tempat_lahir),
            'tgl_lahir' => $this->tgl_lahir,
            'gender' => $this->gender,
            'status_pernikahan' => $this->status_pernikahan,
            'golongan_darah' => $this->golongan_darah,
            'agama' => $this->agama,
            'etnis' => $this->etnis,
            'nama_contact_darurat' => titleCase($this->nama_contact_darurat),
            'contact_darurat_1' => $this->contact_darurat_1,
            'contact_darurat_2' => $this->contact_darurat_2,
            'jenis_identitas' => $this->jenis_identitas,
            'no_identitas' => $this->no_identitas,
            'alamat_identitas' => titleCase($this->alamat_identitas),
            'alamat_tinggal_sekarang' => titleCase($this->alamat_tinggal_sekarang),
        ]);
        $this->dispatch('success', message: 'Data Anda sudah berhasil di submit');
    }

    public function mount()
    {
        $this->is_registered = false;
        $this->show = false;
    }

    public function alreadyRegistered()
    {
        $this->is_registered = true;
        $this->show = true;
    }
    public function register()
    {
        $this->is_registered = false;
        $this->show = true;
    }

    public function updatedIsRegistered()
    {
    }
    public function render()
    {
        return view('livewire.applicant')->layout('layouts.newpolos');
    }
}
