<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

class ChangeField extends Component
{
    public function change()
    {
        $data = Karyawan::get();
        $departemen = '';
        foreach ($data as $d) {
            switch ($d->departemen) {
                case 'BD':
                    $departemen = '1';
                    break;
                case 'Engineering':
                    $departemen = '2';
                    break;
                case 'EXIM':
                    $departemen = '3';
                    break;
                case 'Finance Accounting':
                    $departemen = '4';
                    break;
                case 'GA':
                    $departemen = '5';
                    break;
                case 'Gudang':
                    $departemen = '6';
                    break;
                case 'HR':
                    $departemen = '7';
                    break;
                case 'Legal':
                    $departemen = '8';
                    break;
                case 'Procurement':
                    $departemen = '9';
                    break;
                case 'Produksi':
                    $departemen = '10';
                    break;
                case 'Quality Control':
                    $departemen = '11';
                    break;
                case 'Board of Director':
                    $departemen = '12';
                    break;
            }
            $d->departemen = $departemen;
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
