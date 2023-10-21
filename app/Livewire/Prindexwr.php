<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Prindexwr extends Component
{
    use WithPagination;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getPayroll()
    {
        Jamkerjaid::query()->truncate();
        $jumlah_jam_terlambat = null;
        $jumlah_menit_lembur = null;
        $late = null;
        $late1 = null;
        $late2 = null;
        $late3 = null;
        $late4 = null;
        $late5 = null;

        $filterArray = Yfrekappresensi::all()
            ->pluck('user_id')
            ->unique();

        // buat tabel user_id unique
        foreach ($filterArray as $item) {
            $filteredData = new Jamkerjaid();
            $filteredData->user_id = $item;
            $filteredData->save();
        }

        $filteredData = Jamkerjaid::get();
        foreach ($filteredData as $data) {
            $dataId = Yfrekappresensi::where('user_id', $data->user_id)->get();
            if (!$dataId) {
                dd('data kosong from Prindex.php', $dataId);
            } else {
                // $jumlah_menit_lembur = 0;
                foreach ($dataId as $dt) {
                    if ($dt->late == null) {
                        // khusus NO Late
                        $jumlah_hari_kerja = $dataId->count();
                        if ($dt->overtime_in != null) {
                            $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            // $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                        }
                    } else {
                        // khusus yang late
                        $jumlah_hari_kerja = $dataId->count();
                        // $jumlah_menit_lembur ;
                        // $jumlah_jam_terlambat = 0;
                        // $jumlah_telat_overtime_hours = 0;

                        // check keterlambatan di hari kerja non overtime
                        $late1 = checkFirstInLate($dt->first_in, $dt->shift, $dt->date);
                        $late2 = checkFirstOutLate($dt->first_out, $dt->shift, $dt->date);
                        $late3 = checkSecondInLate($dt->second_in, $dt->shift, $dt->first_out, $dt->date);
                        $late4 = checkSecondOutLate($dt->second_out, $dt->shift, $dt->date);
                        $late5 = checkOvertimeInLate($dt->overtime_in, $dt->shift, $dt->date);
                        $late = $late1 + $late2 + $late3 + $late4 + $late5;

                        // foreach ($dataId as $dt) {
                        //     if ($dt->overtime_in != null) {
                        $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                        // $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                        // $jumlah_jam_terlambat = $jumlah_jam_terlambat + $late;
                        //     }
                        // }
                        // $jumlah_jam_terlambat = $jumlah_jam_terlambat + $late;
                        // dd($jumlah_jam_terlambat);
                    }
                    $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;

                    $jumlah_jam_terlambat = $jumlah_jam_terlambat + $late;
                }
            }

            $jumlah_jam_kerja = $jumlah_hari_kerja * 8;
            // dd($jumlah_menit_lembur, $dt->user_id);
            // dd( $dt->name);
            $data = Jamkerjaid::find($data->id);
            $data->name = $dt->name;
            $data->jumlah_jam_kerja = $jumlah_jam_kerja;
            $data->jumlah_menit_lembur = $jumlah_menit_lembur;
            $data->jumlah_jam_terlambat = $jumlah_jam_terlambat;
            $data->first_in_late = $late1;
            $data->first_out_late = $late2;
            $data->second_in_late = $late3;
            $data->second_out_late = $late4;
            $data->overtime_in_late = $late5;
            $data->save();
            $this->dispatch('success', message: 'Data Karyawan Sudah di Save');
        }
    }

    public function render()
    {
        $filteredData = Jamkerjaid::where('name', 'LIKE', '%' . trim($this->search) . '%')
            ->orWhere('user_id', 'LIKE', '%' . trim($this->search) . '%')
            ->paginate(10);
        return view('livewire.prindexwr', compact(['filteredData']));
    }
}
