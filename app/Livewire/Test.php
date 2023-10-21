<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Yfrekappresensi;

class Test extends Component
{
    // public $saturday;
    public function render()
    {
        // checkSecondOutLate($second_out, $shift, $tgl)
        $late = checkSecondOutLate('00:00:00', 'Malam', '2023-08-05');


        return view('livewire.test', compact('late'));
    }
}
