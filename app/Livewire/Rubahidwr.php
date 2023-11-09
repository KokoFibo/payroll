<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;

class Rubahidwr extends Component
{
    public $idFromArr;
    public $idToArr;

    public function mount () {
       $this->idFromArr = [ 5249, 5250, 5248, 5223 ];

       $this->idToArr = [ 800, 900, 5105, 5301 ];
    }


    public function rubah () {
        for ($i = 0; $i < count($this->idFromArr); $i++) {
            $karyawan_id = Karyawan::where('id_karyawan', $this->idFromArr[$i])->select('id')->first();
            $user_id = User::where('username', $this->idFromArr[$i])->select('id')->first();
            $karyawan = Karyawan::find($karyawan_id->id);
            $user = User::find($user_id->id);
            $karyawan->id_karyawan = $this->idToArr[$i];
            $karyawan->save();
            $user->username = $this->idToArr[$i];
            $user->save();
        }
        dd('done');
    }
    public function render()
    {
        // dd(count($this->idFromArr), count($this->idToArr));


        return view('livewire.rubahidwr');
    }
}
