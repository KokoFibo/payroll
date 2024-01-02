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
        $this->pilihLamaKerja = 90;
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

        if ($this->gaji < $this->gaji_pokok || $this->gaji > $this->gaji_rekomendasi) {

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
        $ninetyDaysAgo = Carbon::now()->subDays(90);
        $hundredTwentyDaysAgo = Carbon::now()->subDays(120);
        $hundredFiftyDaysAgo = Carbon::now()->subDays(150);
        $hundredEigtyDaysAgo = Carbon::now()->subDays(180);
        $twoHundredTenDaysAgo = Carbon::now()->subDays(210);
        $twoHundredFortyDaysAgo = Carbon::now()->subDays(240);

        switch ($this->pilihLamaKerja) {

            case 90:
                // 90 <= 119
                $data = Karyawan::where('tanggal_bergabung', '<=', $ninetyDaysAgo)->where('tanggal_bergabung', '>', $hundredTwentyDaysAgo)
                    ->where('gaji_pokok', '<', 2100000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2100000;
                break;
            case 120:
                // 120 < 149
                $data = Karyawan::where('tanggal_bergabung', '<=', $hundredTwentyDaysAgo)->where('tanggal_bergabung', '>', $hundredFiftyDaysAgo)
                    ->where('gaji_pokok', '<', 2200000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2200000;
                break;

            case 150:

                // 150 < 179
                $data = Karyawan::where('tanggal_bergabung', '<=', $hundredFiftyDaysAgo)->where('tanggal_bergabung', '>', $hundredEigtyDaysAgo)
                    ->where('gaji_pokok', '<', 2300000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2300000;
                break;

            case 180:
                // 180 < 209
                $data = Karyawan::where('tanggal_bergabung', '<=', $hundredEigtyDaysAgo)->where('tanggal_bergabung', '>', $twoHundredTenDaysAgo)
                    ->where('gaji_pokok', '<', 2400000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->orderBy('tanggal_bergabung', 'desc')
                    ->paginate(10);
                $this->gaji_rekomendasi = 2400000;
                break;

            case 210:

                // 210 < 240
                // $data = Karyawan::where('tanggal_bergabung', '<=', $twoHundredTenDaysAgo)->where('tanggal_bergabung', '>', $twoHundredFortyDaysAgo)
                $data = Karyawan::where('tanggal_bergabung', '<=', $twoHundredTenDaysAgo)
                    // ->where('tanggal_bergabung', '>', $twoHundredFortyDaysAgo)
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
