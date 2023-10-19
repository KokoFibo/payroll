<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Yfrekappresensi;

class Yfdeletetanggalpresensiwr extends Component
{
    public $tanggal;

    public function delete () {
        $data = Yfrekappresensi::whereDate('date', $this->tanggal)->get();
        if($data->isEmpty($data)){


        $this->dispatch('error', message: 'Data presensi tidak ditemukan');
        } else {
            Yfrekappresensi::whereDate('date', $this->tanggal)->delete();
            $this->dispatch('success', message: 'Data pada tanggal tersebut telah di hapus');
        }


    }
    public function exit () {
        $this->reset();
        return redirect()->to('/yfpresensiindexwr');
        // or sepertoi dibawah juga bisa
        // return redirect('/yfpresensiindexwr');

    }
    public function render()
    {
        return view('livewire.yfdeletetanggalpresensiwr');
    }
}
