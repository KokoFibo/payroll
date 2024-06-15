<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

class ChangeField extends Component
{
    public function change()
    {
        $data = Karyawan::get();
        $company = '';
        foreach ($data as $d) {
            switch ($d->company) {
                case 'ASB':
                    $company = '1';
                    break;
                case 'DPA':
                    $company = '2';
                    break;
                case 'YCME':
                    $company = '3';
                    break;
                case 'YEV':
                    $company = '4';
                    break;
                case 'YIG':
                    $company = '5';
                    break;
                case 'YSM':
                    $company = '6';
                    break;
                case 'YAM':
                    $company = '7';
                    break;
                case 'GAMA':
                    $company = '8';
                    break;
                case 'WAS':
                    $company = '9';
                    break;
            }
            $d->company = $company;
            $d->save();
        }
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data has been changed',
        );
    }
    public function render()
    {


        return view('livewire.change-field');
    }
}
