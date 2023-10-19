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

        $data = Yfrekappresensi::find(1);

        $saturday = Carbon::parse($data->date)->isSaturday();





        return view('livewire.test', compact(['saturday']));
    }
}
