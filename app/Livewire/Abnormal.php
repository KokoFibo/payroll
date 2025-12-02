<?php

namespace App\Livewire;

use App\Models\Yfrekappresensi;
use Livewire\Component;
use Livewire\WithPagination;

class Abnormal extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $month;
    public $year;
    public $compare;
    public $jumlah;

    public $listMonths = [];
    public $listYears = [];

    public function mount()
    {
        // Ambil bulan & tahun yang tersedia dari tabel
        $this->listMonths = Yfrekappresensi::selectRaw('MONTH(date) as m')
            ->groupBy('m')
            ->orderBy('m')
            ->pluck('m')
            ->toArray();

        $this->listYears = Yfrekappresensi::selectRaw('YEAR(date) as y')
            ->groupBy('y')
            ->orderBy('y')
            ->pluck('y')
            ->toArray();

        // Default value
        // $this->month = end($this->listMonths) ?: date('m');
        // $this->year  = end($this->listYears) ?: date('Y');
        $this->month = 11;
        $this->year  = 2025;
        // $this->field = 'total_jam_kerja';
        $this->compare = '>';
        $this->jumlah = 8;
    }

    public function updated($property)
    {
        // reset pagination kalau filter berubah
        $this->resetPage();
    }

    public function render()
    {
        $data = Yfrekappresensi::query()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->where(function ($q) {
                $q->orWhere('total_jam_kerja', $this->compare, $this->jumlah)
                    ->orWhere('total_jam_lembur', $this->compare, $this->jumlah)
                    ->orWhere('total_jam_kerja_libur', $this->compare, $this->jumlah)
                    ->orWhere('total_jam_lembur_libur', $this->compare, $this->jumlah)
                    ->orWhere('total_hari_kerja', '>', 1)
                    ->orWhere('total_hari_kerja_libur', '>', 1);
            })
            ->paginate(10);


        return view('livewire.abnormal', [
            'data' => $data,
        ]);
    }
}
