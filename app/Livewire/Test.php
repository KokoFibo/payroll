<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class Test extends Component {
    // public $saturday;
    use WithPagination;

    public $array =[];

    public function render() {

        // $rekap = Yfrekappresensi::distinct('user_id')->get('user_id');
        // $array = [] ;
        //         foreach ($rekap as $r) {
        //             $karyawan = Karyawan::where('id_karyawan' , $r->user_id)->first();
        //             if ($karyawan === null) {
        //         $this->array[] = [
        //             'Karyawan_id' => $r->user_id,
        //         ];
        //     }
        // }

        $this->array = checkNonRegisterUser();

        return view( 'livewire.test' );
    }
}
