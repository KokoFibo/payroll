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

    


    public function render() {

       
       $number = "12,250,0,0,0";
       dd(convert_numeric($number));
        





        return view( 'livewire.test' );
    }
}
