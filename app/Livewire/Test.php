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

        $nama_file = "summary.xlsx";
        $month = 11;
        $year = 2023;
        // function nama_file_excel($nama_file, $month, $year){
        //     $arrNamaFile = explode('.', $nama_file);
        //     return $arrNamaFile[0] . '_' . $month . '_'.$year.'.'.$arrNamaFile[1];
        // }

        dd(nama_file_excel($nama_file, $month, $year));
       
        





        return view( 'livewire.test' );
    }
}
