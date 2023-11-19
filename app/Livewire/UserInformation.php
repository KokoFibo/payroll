<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Information;

class UserInformation extends Component
{
    public function render()
    {
        $data = Information::orderBy('date', 'desc')->get();
        return view('livewire.user-information', compact('data'))->layout('layouts.polos');
    }
}
