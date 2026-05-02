<?php

namespace App\Livewire;

use App\Models\Placement;
use App\Models\Yfrekappresensi;
use Livewire\Component;

class ResetNoscanByTanggal extends Component
{
    public $tanggal, $directorate;

    public function delete()
    {
        if (!$this->tanggal) {
            $this->dispatch(
                'message',
                type: 'warning',
                title: 'Silakan pilih tanggal terlebih dahulu',
                position: 'center'
            );
            return;
        }

        $query = Yfrekappresensi::join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
            ->where('yfrekappresensis.date', $this->tanggal);

        // filter directorate (kalau dipilih)
        if (!empty($this->directorate)) {
            $query->where('karyawans.placement_id', $this->directorate);
        }

        $affected = $query->update([
            'yfrekappresensis.no_scan' => null,
            'yfrekappresensis.no_scan_history' => null,
        ]);

        if ($affected === 0) {
            $this->dispatch(
                'message',
                type: 'info',
                title: 'Tidak ada data yang direset',
                position: 'center'
            );
            return;
        }

        $this->dispatch(
            'message',
            type: 'success',
            title: "Berhasil reset {$affected} data No Scan",
            position: 'center'
        );
    }
    public function render()
    {
        $directorates = Placement::all();
        return view(
            'livewire.reset-noscan-by-tanggal',
            compact('directorates')
        );
    }
}
