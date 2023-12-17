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
    public $month = 12;
    public $year = 2023;
    public function render()
    {

        $data = Tambahan::all();
        foreach($data as $d){
            dd('stop jangan lanjut, hapus dulu');
            $no_id = Karyawan::where('id_karyawan', $d->user_id )->first();
            $data_bonus = new Bonuspotongan;
            $data_bonus->karyawan_id = $no_id->id;
            $data_bonus->user_id = $d->user_id;
            $data_bonus->uang_makan = $d->uang_makan;
            $data_bonus->bonus_lain = $d->bonus_lain;
            $data_bonus->baju_esd = $d->baju_esd;
            $data_bonus->gelas = $d->gelas;
            $data_bonus->sandal = $d->sandal;
            $data_bonus->seragam = $d->seragam;
            $data_bonus->sport_bra = $d->sport_bra;
            $data_bonus->hijab_instan = $d->hijab_instan;
            $data_bonus->id_card_hilang = $d->id_card_hilang;
            $data_bonus->masker_hijau = $d->masker_hijau;
            $data_bonus->potongan_lain = $d->potongan_lain;
            $data_bonus->tanggal = $d->tanggal;
            $data_bonus->save();
        }
 

        return view('livewire.test');
    }
}
