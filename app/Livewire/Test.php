<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class Test extends Component
{
    // public $saturday;
    use WithPagination;

    public function render()
    {

        $karyawan = Karyawan::all();
        foreach ($karyawan as $item) {
        //    $users = User::where('id_karyawan',$item->id_karyawan);
           $users = new User();
           $users->name = $item->nama;
           $users->email = $item->email;
           $users->id_karyawan = $item->id_karyawan;
           $users->role = 1;
           $users->password = bcrypt(generatePassword($item->tanggal_lahir));
           $users->save();
        }






        return view('livewire.test');
    }
}
