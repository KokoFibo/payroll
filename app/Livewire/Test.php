<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;

class Test extends Component
{
    // public $saturday;
    use WithPagination;
    public $month = 12;
    public $year = 2023;
    public function render()
    {
 $jumlah_libur_nasional = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $this->month)->whereYear('tanggal_mulai_hari_libur', $this->year)->sum('jumlah_hari_libur');
     
 
//  $data = Karyawan::where('tanggal_bergabung', '>=', now()->subDays(90))->paginate(10);
 $data = Karyawan::whereMonth('tanggal_resigned', $this->month)
 ->whereYear('tanggal_resigned', $this->year)
 ->paginate(10);
 $data1 = Karyawan::whereMonth('tanggal_resigned', $this->month)
 ->whereYear('tanggal_resigned', $this->year)
 ->first();
// $tanggal_resigned = Carbon::parse($data1->tanggal_resigned);
// $tanggal_bergabung = Carbon::parse($data1->tanggal_bergabung);
//  dd($tanggal_resigned->diffInDays($tanggal_bergabung));

 $jumlah = jumlah_hari_resign($data1->tanggal_bergabung, $data1->tanggal_resigned);

        return view('livewire.test', [
            'data' => $data
        ]);
    }
}
