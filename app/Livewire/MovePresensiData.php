<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class MovePresensiData extends Component
{
    public $month, $year, $today;

    public $tahun, $bulan, $getYear, $getMonth, $dataBulan, $dataTahun;

    public function cancel()
    {
        $this->today = now();
        $this->year = now()->year;
        $this->month = now()->month;
        $this->getYear = "";
        $this->getMonth = "";
        $this->dataTahun = Yfrekappresensi::selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->pluck('year')
            ->all();

        // $this->render();
    }
    public function move()
    {
        $data = Yfrekappresensi::whereYear('date', $this->getYear)->whereMonth('date', $this->getMonth)->get();
        dd($data->all());
    }

    public function mount()
    {
        $this->today = now();
        $this->year = now()->year;
        $this->month = now()->month;
        $this->getYear = "";
        $this->getMonth = "";
        $this->dataTahun = Yfrekappresensi::selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->pluck('year')
            ->all();
    }
    public function updatedGetYear()
    {

        $currentMonth = $this->month;
        $lastMonth = ($currentMonth - 1) == 0 ? 12 : ($currentMonth - 1);

        $this->dataBulan = Yfrekappresensi::whereYear('date', $this->getYear)
            ->whereNotIn(DB::raw('MONTH(date)'), [$currentMonth, $lastMonth])
            ->selectRaw('MONTH(date) as month')
            ->groupByRaw('MONTH(date)')
            ->pluck('month')
            ->all();
    }

    public function render()
    {
        return view('livewire.move-presensi-data');
    }
}
