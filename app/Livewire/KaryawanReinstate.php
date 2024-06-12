<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use App\Rules\FileSizeLimit;
use Livewire\Attributes\Url;
use App\Models\Applicantfile;
use Livewire\WithFileUploads;
use App\Livewire\Karyawanindexwr;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\RequiredIf;
use Google\Service\YouTube\ThirdPartyLinkStatus;
use Intervention\Image\ImageManagerStatic as Image;

class KaryawanReinstate extends Component
{
    public $id;
    public $id_karyawan, $nama, $status_karyawan;

    public function mount($id)
    {
        $this->id = $id;
        $data = Karyawan::find($this->id);
        $this->id_karyawan = $data->id_karyawan;
        $this->nama = $data->nama;
        $this->status_karyawan = '';
    }

    public function reinstate()
    {
        $this->validate([
            'status_karyawan' => 'required'
        ]);
        $data = Karyawan::find($this->id);
        $data->status_karyawan = $this->status_karyawan;
        $data->tanggal_bergabung = date('Y-m-d', strtotime(now()->toDateString()));
        $data->save();
        $user = User::where('username', $data->id_karyawan)->first();
        $user->password = Hash::make(generatePassword($data->tanggal_lahir));
        $user->save();

        return redirect()->to('/karyawanindex');
    }

    public function cancel()
    {
        return redirect()->to('/karyawanindex');
    }

    public function render()
    {


        return view('livewire.karyawan-reinstate')
            ->layout('layouts.appeloe');
    }
}
