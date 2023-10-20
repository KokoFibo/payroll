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

    public function render()
    {

        Jamkerjaid::query()->truncate();

        $filterArray = Yfrekappresensi::all()->pluck('user_id')->unique();

        // buat tabel user_id unique
        foreach($filterArray as $item) {
            $filteredData = new Jamkerjaid;
            $filteredData->user_id = $item;
            $filteredData->save();
        }
        $filteredData = Jamkerjaid::get();
        foreach($filteredData as $data) {
            $dataId = Yfrekappresensi::where('user_id', $data->user_id)->where('late',null)->get();
            if(!$dataId){
                dd('data kosong', $dataId);
            } else {
                $jumlah_hari_kerja = $dataId->count();
                $jumlah_menit_lembur=0;
                foreach($dataId as $dt) {
                if($dt->overtime_in != null){
                    // $t1 = strtotime($dt->overtime_in);
                    // $t2 = strtotime($dt->overtime_out);
                    // $diff = gmdate('H:i:s', $t2 - $t1);
                    $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                    $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;

                }

            }
            $jumlah_jam_kerja = $jumlah_hari_kerja * 8;
            // dd($jumlah_menit_lembur, $dt->user_id);
            $data = Jamkerjaid::find($data->id);
            $data->jumlah_jam_kerja = $jumlah_jam_kerja;
            $data->jumlah_menit_lembur = $jumlah_menit_lembur;
            $data->save();

            }





        }



        $filteredData = Jamkerjaid::paginate(10);

        return view('livewire.prindexwr', compact(['filteredData']));
    }
}
