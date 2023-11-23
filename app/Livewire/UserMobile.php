<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class UserMobile extends Component
{
    use WithPagination;

    public $total_hari_kerja;
    public $total_jam_kerja;
    public $total_jam_lembur;
    public $total_keterlambatan;

    public function render()
    {
        // $user_id = 1112;
        $user_id = auth()->user()->username;
        $month = 11;

        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0;

        $data = Yfrekappresensi::where('user_id', $user_id)->orderBy('date', 'desc')->simplePaginate(5);
        $data1 = Yfrekappresensi::where('user_id', $user_id)->get();

        foreach ($data1 as $d) {
            if ($d->no_scan == null) {
                $tgl = tgl_doang($d->date);
                $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);
                    $langsungLembur = langsungLembur( $d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);
                $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                $this->total_jam_kerja = $this->total_jam_kerja + $jam_kerja;
                $this->total_jam_lembur = $this->total_jam_lembur + $jam_lembur ;
                $this->total_keterlambatan = $this->total_keterlambatan + $terlambat;

                $this->total_hari_kerja++;
            }
        }
        return view('livewire.user-mobile', compact('data'))->layout('layouts.polos');
        // return view('livewire.user-mobile', compact('data'));
    }
}
