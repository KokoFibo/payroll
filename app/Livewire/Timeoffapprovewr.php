<?php

namespace App\Livewire;

use App\Models\Timeoff;
use Livewire\Component;

class Timeoffapprovewr extends Component
{
    public function render()
    {
        $data = Timeoff::all();
        return view('livewire.timeoffapprovewr', [
            'data' => $data
        ]);
    }
}
