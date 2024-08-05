<?php

namespace App\Livewire;

use Livewire\Component;

class DeveloperDashboard extends Component
{
    public function clear_build()
    {
        clear_build();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Build Clear',
            position: 'center'
        );
    }
    public function render()
    {
        return view('livewire.developer-dashboard');
    }
}
