<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

class ChangeField extends Component
{
    public function change()
    {
        $data = Karyawan::get();
        $jabatan = '';
        foreach ($data as $d) {
            switch ($d->jabatan) {
                case 'Admin':
                    $jabatan = '1';
                    break;
                case 'Asisten Direktur':
                    $jabatan = '2';
                    break;
                case 'Asisten Kepala':
                    $jabatan = '3';
                    break;
                case 'Asisten Manager':
                    $jabatan = '4';
                    break;
                case 'Asisten Pengawas':
                    $jabatan = '5';
                    break;
                case 'Asisten Wakil Presiden':
                    $jabatan = '6';
                    break;
                case 'Design Grafis':
                    $jabatan = '7';
                    break;
                case 'Director':
                    $jabatan = '8';
                    break;
                case 'Kepala':
                    $jabatan = '9';
                    break;
                case 'Manager':
                    $jabatan = '10';
                    break;
                case 'Pengawas':
                    $jabatan = '11';
                    break;
                case 'President':
                    $jabatan = '12';
                    break;
                case 'Senior Staff':
                    $jabatan = '13';
                    break;
                case 'Staff':
                    $jabatan = '14';
                    break;
                case 'Supervisor':
                    $jabatan = '15';
                    break;
                case 'Vice President':
                    $jabatan = '16';
                    break;
                case 'Satpam':
                    $jabatan = '17';
                    break;
                case 'Koki':
                    $jabatan = '18';
                    break;
                case 'Dapur Kantor':
                    $jabatan = '19';
                    break;
                case 'Dapur Pabrik':
                    $jabatan = '20';
                    break;
                case 'QC Aging':
                    $jabatan = '21';
                    break;
                case 'Driver':
                    $jabatan = '22';
                    break;
                case 'Translator':
                    $jabatan = '23';
                    break;
                case 'Senior SPV':
                    $jabatan = '24';
                    break;
            }
            $d->jabatan = $jabatan;
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
