<?php

namespace App\Livewire;

use App\Models\Yfrekappresensi;
use Livewire\Component;

class DeleteNoscanByTanggal extends Component
{
    public $tanggal;
    public function delete()
    {
        Yfrekappresensi::where('date', $this->tanggal)
            ->update([
                'no_scan' => null,
                'no_scan_history' => null,
            ]);
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data No Scan & no scan history berhasil direset',
            position: 'center'
        );
    }
    public function render()
    {
        return view('livewire.delete-noscan-by-tanggal');
    }
}
