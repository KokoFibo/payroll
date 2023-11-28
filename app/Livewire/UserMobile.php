<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class UserMobile extends Component
{
    use WithPagination;

    public $total_hari_kerja;
    public $total_jam_kerja;
    public $total_jam_lembur;
    public $total_keterlambatan;
    public  $selectedMonth;
    public  $selectedYear;
    public $user_id;
    public $data_payroll;
    public $is_slipGaji = false;
    public $data_karyawan;
    
    // public function close () {
    //     $this->is_slipGaji = false;
    // }

    public function slip_gaji () {
        // dd($this->selectedMonth, $this->selectedYear);
        
        $this->data_payroll = Payroll::with('jamkerjaid')->whereMonth('date', $this->selectedMonth)
        ->whereYear('date', $this->selectedYear)
        ->where('id_karyawan', $this->user_id)->first();
        $this->data_karyawan = Karyawan::where('id_karyawan',$this->user_id )->first();
        // dd($this->data_karyawan);
       
        if($this->data_payroll != null) {
            $this->is_slipGaji = true;
            // $this->dataKaryawanArr = [
            //     'ID Karyawan' => $this->id_karyawan,
            //     'Nama' => $this->nama,
            // ];
        }



    }
    public function mount () {
        $this->is_slipGaji = false;
    $this->selectedMonth = Carbon::now()->month;
    $this->selectedYear = Carbon::now()->year;

    }

    public function clear_data () {
        $this->total_hari_kerja = 0;
    $this->total_jam_kerja = 0;
    $this->total_jam_lembur = 0;
    $this->total_keterlambatan = 0;
    }

    public function render()
    {
        // $this->user_id = 1111;
        // $this->user_id = 4;
        $this->user_id = auth()->user()->username;
        // $selectedMonth = 11;

        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0;
        $this->clear_data();

        $data = Yfrekappresensi::where('user_id', $this->user_id)
        ->whereMonth('date', $this->selectedMonth)
        ->whereYear('date', $this->selectedYear)
        ->orderBy('date', 'desc')->simplePaginate(5);
        $data1 = Yfrekappresensi::where('user_id', $this->user_id)
        ->whereMonth('date', $this->selectedMonth)
        ->whereYear('date', $this->selectedYear)
        ->get();

        foreach ($data1 as $d) {
            if ($d->no_scan == null) {

                $tgl = tgl_doang($d->date);
            $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
            $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);

            if ($d->karyawan->jabatan === 'Satpam') {
                $jam_kerja = ($terlambat >= 6) ? 0.5 : $jam_kerja;
            }

            $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);

            $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;

            if($d->shift == 'Malam') {
                if(is_saturday($d->date)) {
                    if($jam_kerja >= 6) {
                        $jam_lembur = $jam_lembur + 1;
                    }
                } else if(is_sunday($d->date)) {
                    if($jam_kerja >= 16) {
                        $jam_lembur = $jam_lembur + 2;
                    }
                }
                else {
                    if($jam_kerja >= 8) {
                        $jam_lembur = $jam_lembur + 1;
                    }
                }
            }
                $this->total_jam_kerja = $this->total_jam_kerja + $jam_kerja;
                $this->total_jam_lembur = $this->total_jam_lembur + $jam_lembur ;
                $this->total_keterlambatan = $this->total_keterlambatan + $terlambat;
                $this->total_hari_kerja++;
            }
        }
        return view('livewire.user-mobile', compact('data'))->layout('layouts.polos');
    }
}
