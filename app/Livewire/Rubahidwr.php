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
       $this->idFromArr = [ 5224,
       5225,
       5226,
       5227,
       5228,
       5229,
       5230,
       5231,
       5232,
       5233,
       5234,
       5235,
       5236,
       5237,
       5238,
       5239,
       5240,
       5241,
       5242,
       5243,
       5244,
       5245,
       5246,
       5247,
       5261,
       5262,
       5263,
       5264,
       5265,
       5266,
       5267,
       5268,
       5269,
       5270,
       5271,
       5272,
       5273,
       5274


       ];

       $this->idToArr = [
        5104,
5108,
5109,
5110,
5111,
5112,
5113,
5114,
5115,
5116,
5117,
5118,
5119,
5120,
5121,
5122,
5123,
5124,
5125,
5126,
5127,
5128,
5129,
5130,
5131,
5132,
5133,
5134,
5135,
5136,
5137,
5138,
5139,
5140,
5141,
5142,
5143,
5144

       ];
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
