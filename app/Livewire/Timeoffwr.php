<?php

namespace App\Livewire;

use Livewire\Component;

class Timeoffwr extends Component
{
    public function save()
    {
        dd('save');
    }
    public $id_karyawan, $placement, $type, $description, $start_date, $end_date;

    public function render()
    {
        return view('livewire.timeoffwr')->layout('layouts.polos');
    }
}
