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

        $second_out = '06:01:00';
        $tgl = '2023-11-03';
        $shift = 'Malam';
        $langsungLembur = langsungLembur( $second_out, $tgl, $shift);

        return view( 'livewire.test', compact('langsungLembur') );
    }
}
