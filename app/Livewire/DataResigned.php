<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

class DataResigned extends Component
{
    public $month, $year;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }
    public function render()
    {
        $data = Karyawan::whereMonth('tanggal_resigned', $this->month)->whereYear('tanggal_resigned', $this->year)
            ->orWhereMonth('tanggal_blacklist', $this->month)
            ->orWhereYear('tanggal_blacklist', $this->year)
            ->paginate(10);

        $dataResigned = Karyawan::whereMonth('tanggal_resigned', $this->month)->whereYear('tanggal_resigned', $this->year)
            ->count();
        $dataBlacklist = Karyawan::whereMonth('tanggal_blacklist', $this->month)->whereYear('tanggal_blacklist', $this->year)
            ->count();

        $data = Karyawan::whereNotNull('tanggal_resigned')
            ->whereMonth('tanggal_resigned', $this->month)->whereYear('tanggal_resigned', $this->year)
            ->whereRaw('DATEDIFF(tanggal_resigned, tanggal_bergabung) < 90')
            ->paginate(10);

        return view('livewire.data-resigned', compact(['data', 'dataResigned', 'dataBlacklist']));
    }
}
