<?php

namespace App\Livewire;

use App\Models\Yfrekappresensi;
use Livewire\Component;

class ResetLateByTanggal extends Component
{
    public $tanggal;

    public function resetLate()
    {
        if (!$this->tanggal) {
            $this->dispatch(
                'message',
                type: 'warning',
                title: 'Tanggal wajib dipilih',
                position: 'center'
            );
            return;
        }

        $affected = Yfrekappresensi::where('date', $this->tanggal)
            ->update([
                'late' => null,
                'late_history' => null,
            ]);

        $this->dispatch(
            'message',
            type: 'success',
            title: "Berhasil reset {$affected} data",
            position: 'center'
        );
    }
    public function render()
    {
        return view('livewire.reset-late-by-tanggal');
    }
}
