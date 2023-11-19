<?php

namespace App\Livewire;

use Livewire\Component;

class UserInformation extends Component
{
    public function render()
    {
        return view('livewire.user-information')->layout('layouts.polos');
    }
}
