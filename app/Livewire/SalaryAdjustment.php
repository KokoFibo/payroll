<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;

class SalaryAdjustment extends Component
{
    use WithPagination;
    public $today;
    protected $paginationTheme = 'bootstrap';

    public $pilihLamaKerja, $gaji_rekomendasi;
    public $gaji, $gaji_pokok;
    public $id, $nama;

    public function mount()
    {
        $this->today = now();
        $this->pilihLamaKerja = 3;
        $this->gaji_rekomendasi = 2100000;
    }

    public function edit($id)
    {
        $this->gaji = 0;
        $data = Karyawan::find($id);

        $this->gaji_pokok = $data->gaji_pokok;
        $this->id = $id;
        $this->nama = $data->nama;
    }

    public function save()
    {
        $this->gaji = convert_numeric($this->gaji);

        if ($this->gaji < $this->gaji_pokok || $this->gaji > 2500000) {

            $this->dispatch('error', message: 'Gaji tidak sesuai rekomendasi');
            return;
        }
        $data = Karyawan::find($this->id);
        $data->gaji_pokok = $this->gaji;
        $data->save();
        $this->gaji = 0;

        $this->dispatch('success', message: 'Data Gaji Karyawan Sudah di Sesuaikan');
    }

    public function render()
    {
        // $ninetyDaysAgo = Carbon::now()->subDays(90);
        // $hundredTwentyDaysAgo = Carbon::now()->subDays(120);
        // $hundredFiftyDaysAgo = Carbon::now()->subDays(150);
        // $hundredEigtyDaysAgo = Carbon::now()->subDays(180);
        // $twoHundredTenDaysAgo = Carbon::now()->subDays(210);
        // $twoHundredFortyDaysAgo = Carbon::now()->subDays(240);
        // $twoHundredseventyDaysAgo = Carbon::now()->subDays(270);

        $bulan3 = Carbon::now()->startOfMonth()->subMonths(4);
        $bulan4 = Carbon::now()->startOfMonth()->subMonths(5);
        $bulan5 = Carbon::now()->startOfMonth()->subMonths(6);
        $bulan6 = Carbon::now()->startOfMonth()->subMonths(7);
        $bulan7 = Carbon::now()->startOfMonth()->subMonths(8);
        $bulan8 = Carbon::now()->startOfMonth()->subMonths(9);
        $bulan9 = Carbon::now()->startOfMonth()->subMonths(10);

        // dd($bulan3->format('m'));
        switch ($this->pilihLamaKerja) {
            case "3":
                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan3->format('m'))
                    ->where('gaji_pokok', '<', 2100000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2100000;
                break;

            case "4":

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2200000;
                break;

            case "5":
                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2300000;
                break;

            case "6":
                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2400000;
                break;

            case "7":
                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan7->format('m'))
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2500000;
                break;

            case "8":
                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan8->format('m'))
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2500000;
                break;
        }

        return view('livewire.salary-adjustment', compact('data'));
    }
}
