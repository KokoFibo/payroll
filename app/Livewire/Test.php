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

        $first_in = '16:37';
        $second_out = '23:21';
        $t1 = strtotime($first_in);
        $t2 = strtotime($second_out);

        $diff = gmdate('H:i:s', $t2 - $t1);

        $diff = explode(':', $diff);
        $jam = (int) $diff[0];
        $menit = (int) $diff[1];

        if ( $menit>=45 ) {
            $jam = $jam + 1;
        } else if($menit<45 && $menit > 15) {
            $jam = $jam + 0.5;
        } else {
            $jam ;
        }

       

        dd($diff, $jam);
        return view( 'livewire.test', compact('langsungLembur') );
    }
}
