<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;

class Test extends Component
{
    // public $saturday;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month = 12;
    public $year = 2023;
    public $today;



    public function mount()
    {
        $this->today = now();
    }

    public function render()
    {
        // $counttotalHadir = Yfrekappresensi::query()
        // ->where('date', Yfrekappresensi::max('date'))
        // ->count();
        // $latestDate = Yfrekappresensi::query()
        // ->where('date', Yfrekappresensi::max('date'))->first();

        dd(ratarata(30));

        $counttotalHadir = Yfrekappresensi::where('date', Yfrekappresensi::max('date'))
            ->count();
        $latestDate = Yfrekappresensi::where('date', Yfrekappresensi::max('date'))->first();

        $events = 0;
        $avg =0; 
        $i = 0;
            $n = 7;
            $cx=0;
        while($cx < $n) {
            $today = Carbon::today();
            $events = Yfrekappresensi::whereDate('date',  $today->subDays($i+1))->count();
            if($events != 0) {
                $avg+=$events;
                $data[] = $events;
                $cx++;
            }
            $i++;
        }
        dd($data, round($avg/7));


        




        $lembur = Carbon::parse(pembulatanJamOvertimeOut('01:35:00'))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
        dd($lembur);
        // $ninetyDaysAgo  = 0;
        // lama_bekerja($tgl_mulai_kerja, $tgl_resigned)
        $month = '11';
        $year = '2023';
        dd(is_35_days($month, $year));



        $ninetyDaysAgo = Carbon::now()->subDays(90);
        $hundredTwentyDaysAgo = Carbon::now()->subDays(120);
        $hundredFiftyDaysAgo = Carbon::now()->subDays(150);
        $hundredEigtyDaysAgo = Carbon::now()->subDays(180);
        $twoHundredTenDaysAgo = Carbon::now()->subDays(210);
        $twoHundredFortyDaysAgo = Carbon::now()->subDays(240);

        // 90 <= 119
        $data = Karyawan::where('tanggal_bergabung', '<=', $ninetyDaysAgo)->where('tanggal_bergabung', '>', $hundredTwentyDaysAgo)
            ->where('gaji_pokok', '<=', 2100000)
            ->whereIn('status_karyawan', ['PKWT, PKWTT, Dirumahkan'])
            ->orderBy('tanggal_bergabung', 'desc')
            ->paginate(10);
        // 120 < 149
        $data = Karyawan::where('tanggal_bergabung', '<=', $hundredTwentyDaysAgo)->where('tanggal_bergabung', '>', $hundredFiftyDaysAgo)
            ->where('gaji_pokok', '<=', 2200000)
            ->orderBy('tanggal_bergabung', 'desc')
            ->paginate(10);

        // 150 < 179
        $data = Karyawan::where('tanggal_bergabung', '<=', $hundredFiftyDaysAgo)->where('tanggal_bergabung', '>', $hundredEigtyDaysAgo)
            ->where('gaji_pokok', '<=', 2300000)
            ->orderBy('tanggal_bergabung', 'desc')
            ->paginate(10);

        // 180 < 209
        $data = Karyawan::where('tanggal_bergabung', '<=', $hundredEigtyDaysAgo)->where('tanggal_bergabung', '>', $twoHundredTenDaysAgo)
            ->where('gaji_pokok', '<=', 2400000)
            ->orderBy('tanggal_bergabung', 'desc')
            ->paginate(10);

        // 210 < 240
        $data = Karyawan::where('tanggal_bergabung', '<=', $twoHundredTenDaysAgo)->where('tanggal_bergabung', '>', $twoHundredFortyDaysAgo)
            ->where('gaji_pokok', '<=', 2500000)
            ->orderBy('tanggal_bergabung', 'desc')
            ->paginate(10);

        // 240 >
        $data = Karyawan::where('tanggal_bergabung', '<=', $twoHundredFortyDaysAgo)
            ->where('gaji_pokok', '<', 2500000)
            ->orderBy('tanggal_bergabung', 'desc')
            ->paginate(10);


        // $data = Payroll::where('denda_resigned','!=', null)->get();

        return view('livewire.test', compact(['data']));
    }
}
