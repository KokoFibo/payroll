<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class Prindexwr extends Component
{
    use WithPagination;
    public $periode;
    public $search = '';


    public function mount()
    {
        $getTglTerakhir = Jamkerjaid::select('date')
            ->orderBy('date', 'desc')
            ->first();
        if($getTglTerakhir != null) {
            $this->periode = $getTglTerakhir->date;
        }
    }

    public function rubah()
    {
        $this->render();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getPayrollConfirmation()
    {
        $checkIfJamKerjaExist = Jamkerjaid::where('date', $this->periode)->first();
        if ($checkIfJamKerjaExist) {
            // $this->dispatch('error', message: 'Masih ada data no scan');
            $this->dispatch('confirm');
            return back();
        } else {
            $this->getPayroll();
        }
    }

    #[On('getPayroll')]
    public function getPayroll()
    {
        $checkIfJamKerjaExist = Jamkerjaid::where('date', $this->periode)->first();
        if ($checkIfJamKerjaExist) {
            dd($checkIfJamKerjaExist);
            $this->dispatch('error', message: 'Masih ada data no scan');
            return back();
        }

        $tglsementara = Yfrekappresensi::where('no_scan', 'No Scan')
            ->whereYear('date', getTahun($this->periode))
            ->whereMonth('date', getBulan($this->periode))
            ->count();
            dd($tglsementara);

        if ($tglsementara) {
            $this->dispatch('error', message: 'Masih ada data no scan');
            return back();
        }

        $checkIfJamKerjaExist = Jamkerjaid::where('date', $this->periode)->first();

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
            $jumlah_menit_lembur = null;
            $jumlah_jam_terlambat = null;
            $jumlah_menit_lembur = null;
            $total_late_1 = null;
            $total_late_2 = null;
            $total_late_3 = null;
            $total_late_4 = null;
            $total_late_5 = null;
            $total_late = null;
            $dataId = Yfrekappresensi::where('user_id', $data->user_id)->get();
            if (!$dataId) {
                dd('data kosong from Prindex.php', $dataId);
            } else {
                foreach ($dataId as $dt) {
                    if ($dt->late == null) {
                        // khusus NO Late
                        $jumlah_hari_kerja = $dataId->count();
                        if ($dt->overtime_in != null) {
                            $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                        }
                    } else {
                        // khusus yang late
                        $jumlah_hari_kerja = $dataId->count();

                        // check keterlambatan di hari kerja non overtime
                        $late1 = checkFirstInLate($dt->first_in, $dt->shift, $dt->date);
                        $late2 = checkFirstOutLate($dt->first_out, $dt->shift, $dt->date);
                        $late3 = checkSecondInLate($dt->second_in, $dt->shift, $dt->first_out, $dt->date);
                        $late4 = checkSecondOutLate($dt->second_out, $dt->shift, $dt->date);
                        $late5 = checkOvertimeInLate($dt->overtime_in, $dt->shift, $dt->date);
                        $total_late_1 = $total_late_1 + $late1;
                        $total_late_2 = $total_late_2 + $late2;
                        $total_late_3 = $total_late_3 + $late3;
                        $total_late_4 = $total_late_4 + $late4;
                        $total_late_5 = $total_late_5 + $late5;
                        $total_late = $total_late_1 + $total_late_2 + $total_late_3 + $total_late_4 + $total_late_5;
                        if ($dt->overtime_in != null) {
                            $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                        }
                    }
                }

                $jumlah_jam_terlambat = $jumlah_jam_terlambat + $late;
            }
            // DATA TOTAL

            $jumlah_jam_kerja = $jumlah_hari_kerja * 8 - ($total_late + $total_late_5);

            $data = Jamkerjaid::find($data->id);
            $data->name = $dt->name;
            $data->date = buatTanggal($dt->date);
            $data->jumlah_jam_kerja = $jumlah_jam_kerja;
            $data->jumlah_menit_lembur = $jumlah_menit_lembur;
            $data->jumlah_jam_terlambat = $total_late;
            $data->first_in_late = $total_late_1 == 0 ? null : $total_late_1;
            $data->first_out_late = $total_late_2 == 0 ? null : $total_late_2;
            $data->second_in_late = $total_late_3 == 0 ? null : $total_late_3;
            $data->second_out_late = $total_late_4 == 0 ? null : $total_late_4;
            $data->overtime_in_late = $total_late_5 == 0 ? null : $total_late_5;
            $data->save();
        }
        $this->dispatch('success', message: 'Data Karyawan Sudah di Save');
    }

    public function render()
    {
        // $getPeriode = Yfrekappresensi::distinct('date')->get();
        $periodePayroll = DB::table('yfrekappresensis')
            ->select(DB::raw('YEAR(date) year, MONTH(date) month, MONTHNAME(date) month_name'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $filteredData = Jamkerjaid::whereDate('date', $this->periode)
            ->orWhere('name', 'LIKE', '%' . trim($this->search) . '%')
            ->orWhere('user_id', 'LIKE', '%' . trim($this->search) . '%')
            ->paginate(10);
        return view('livewire.prindexwr', compact(['filteredData', 'periodePayroll']));
    }
}
