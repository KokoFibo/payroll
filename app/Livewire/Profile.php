<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{
    public $current_email;
    public $email;
    public $current_password;
    public $old_password;
    public $new_password;
    public $confirm_password;
    public $language;
    public $kontak_darurat, $hp1, $hp2, $id;

    public function changeLanguage()
    {
        $user = User::find(Auth::user()->id);
        if ($user->language != $this->language) {
            $user->language = $this->language;
            $user->save();
            $this->dispatch('success', message: 'Bahasa berhasil di rubah');
        }
    }

    public function changePassword()
    {
        $this->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|different:old_password',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.require' => 'Wajib diisi',
            'old_password.min' => 'Minimal harus 6 karakter',
            'new_password.require' => 'Wajib diisi',
            'new_password.min' => 'Minimal harus 6 karakter',
            'new_password.different:old_password' => 'Harus berbeda dengan password lama',
            'confirm_password.required' => 'Wajib diisi',
            'confirm_password.same:new_password' => 'Password berbeda',
        ]);

        $user = User::find(Auth::user()->id);
        if (Hash::check($this->old_password, Auth::user()->password)) {
            $user->password = Hash::make($this->new_password);

            $user->save();
            $this->dispatch('success', message: 'Password berhasil di rubah');
        } else {
            $this->dispatch('error', message: 'Password gagal di rubah');
        }
    }

    public function changeEmail()
    {
        $this->validate([
            'email' => 'email|unique:users',
        ], [
            'email.email' => 'Format email salah',
            'email.unique' => 'Email ini sudah terdaftar dalam database kami, gunakan yang lain',
        ]);

        $user = User::find(Auth::user()->id);
        $user->email = $this->email;
        $data_karyawan = Karyawan::where('id_karyawan', $user->username)->first();
        $karyawan = Karyawan::find($data_karyawan->id);
        $karyawan->email = $this->email;
        $user->save();
        $karyawan->save();
        $this->dispatch('success', message: 'Email berhasil di rubah');
    }

    public function mount()
    {
        $this->current_email = auth()->user()->email;
        $this->language = auth()->user()->language;
        $data = Karyawan::where('id_karyawan', auth()->user()->username)->first();
        if ($data != null) {

            $this->kontak_darurat = $data->kontak_darurat;
            $this->hp1 = $data->hp1;
            $this->hp2 = $data->hp2;
            $this->id = $data->id;
        }
    }

    public function update_kontak_darurat()
    {
        $this->validate([
            'kontak_darurat' => 'nullable',
            'hp1' => 'nullable',
            'hp2' => 'nullable',
        ]);
        $data = Karyawan::find($this->id);
        if ($data == null) {
            $this->dispatch('error', message: 'Data Karyawan tidak ada');
            return;
        }
        $data->kontak_darurat = $this->kontak_darurat;
        $data->hp1 = $this->hp1;
        $data->hp2 = $this->hp2;
        $data->save();
    }
    public function render()
    {
        return view('livewire.profile')->layout('layouts.polos');
    }
}
