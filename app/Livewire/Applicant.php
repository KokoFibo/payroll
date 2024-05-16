<?php

namespace App\Livewire;

use Livewire\Component;

class Applicant extends Component
{
    public function render()
    {
        return view('livewire.applicant')->layout('layouts.newpolos');
    }
}
