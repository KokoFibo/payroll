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
    public $cx = 0;
    public $columnName = 'user_id';
    public $direction = 'desc';
    public $perpage = 10;

    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }

    public function mount()
    {
        $getTglTerakhir = Yfrekappresensi::select('date')
            ->orderBy('date', 'desc')
            ->first();
        if ($getTglTerakhir != null) {
            $this->periode = buatTanggal($getTglTerakhir->date);
        } else {
            $this->periode = '2000-01-01';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getPayrollConfirmation()
    {
        // $checkIfJamKerjaExist = Jamkerjaid::where('date', $this->periode)->first();
        // if ($checkIfJamKerjaExist) {
        //     // $this->dispatch('error', message: 'Masih ada data no scan');
        //     $this->dispatch('foundError');
        //     return back();
        // } else {
        //     $this->getPayroll();
        // }
    }

    #[On('getPayroll')]
    public function getPayroll()
    {
        $jamKerjaKosong = Jamkerjaid::count();

        $adaPresensi = Yfrekappresensi::count();
        if ($jamKerjaKosong == null && $adaPresensi == null) {
            $this->dispatch('error', message: 'Data Presensi Masih Kosong');
            return back();
        }
        // AMBIL DATA TERAKHIR DARI REKAP PRESENSI PADA BULAN YBS
        $last_data_date = Yfrekappresensi::query()
            ->whereMonth('date', getBulan($this->periode))
            ->whereYear('date', getTahun($this->periode))
            ->orderBy('date', 'desc')
            ->first();

        $checkIfJamKerjaExist = Jamkerjaid::where('date', $this->periode)->first();

        $tglsementara = Yfrekappresensi::where('no_scan', 'No Scan')
            ->whereYear('date', getTahun($this->periode))
            ->whereMonth('date', getBulan($this->periode))
            ->count();

        if ($tglsementara) {
            $this->dispatch('error', message: 'Masih ada data no scan');
            return back();
        }
        $checkIfJamKerjaExist = Jamkerjaid::where('date', $this->periode)->first();

        Jamkerjaid::where('date', $this->periode)->delete();

        $jumlah_jam_terlambat = null;
        $jumlah_menit_lembur = null;
        $dt_name = null;
        $dt_date = null;
        $dt_karyawan_id = null;
        $late = null;
        $late1 = null;
        $late2 = null;
        $late3 = null;
        $late4 = null;
        $late5 = null;

        $filterArray = Yfrekappresensi::whereMonth('date', getBulan($this->periode))
            ->whereYear('date', getTahun($this->periode))
            ->pluck('user_id')
            ->unique();

        // buat tabel user_id unique
        foreach ($filterArray as $item) {
            $filteredData = new Jamkerjaid();
            $filteredData->user_id = $item;
            $filteredData->karyawan_id = 1;
            $filteredData->date = $this->periode;
            $filteredData->save();
        }
        $filteredData = Jamkerjaid::whereDate('date', $this->periode)->get();
        foreach ($filteredData as $data) {
            $jumlah_menit_lembur = null;
            $jumlah_jam_terlambat = null;
            $jumlah_menit_lembur = null;
            $jumlah_hari_kerja = null;

            $total_noscan = null;
            $n_noscan = null;

            $total_late_1 = null;
            $total_late_2 = null;
            $total_late_3 = null;
            $total_late_4 = null;
            $total_late_5 = null;
            $total_late = null;
            $jam_kerja = null;

            $dataId = Yfrekappresensi::where('user_id', $data->user_id)
                ->whereMonth('date', getBulan($this->periode))
                ->whereYear('date', getTahun($this->periode))
                ->get();

            if (!$dataId) {
                dd('data kosong from Prindex.php', $dataId);
            } else {
                foreach ($dataId as $dt) {
                    $jam_kerja = 0;
                        foreach ($dataId as $jk) {
                            if (is_saturday($jk->date)) {
                                $jam_kerja += 6;
                            } else {
                                $jam_kerja += 8;
                            }

                        }
                    if ($dt->late == null) {
                        $n_noscan = $dt->no_scan_history;

                        // khusus NO Late
                        $jumlah_hari_kerja = $dataId->count();

                        if ($dt->overtime_in != null) {
                            // $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            // $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                            try {
                                $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                                $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                            } catch (\Exception $e) {
                                //  return $e->getMessage();ook
                                // $this->dispatch('success', message: 'Error user ID:' . $dt->user_id . 'Tanggal :' . $dt->date);
                                $errorId = 'Error user ID: ' . $dt->user_id . ', Tanggal : ' . $dt->date;
                                $this->dispatch('foundError', title: $errorId);

                                return $e->getMessage();
                                //  dd($dt->user_id, $dt->date);
                            }
                        }
                    } else {
                        // khusus yang late

                        $jumlah_hari_kerja = $dataId->count();
                        $n_noscan = $dt->no_scan_history;

                        // check keterlambatan di hari kerja non overtime
                        $late1 = checkFirstInLate($dt->first_in, $dt->shift, $dt->date);
                        $late2 = checkFirstOutLate($dt->first_out, $dt->shift, $dt->date);
                        $late3 = checkSecondInLate($dt->second_in, $dt->shift, $dt->first_out, $dt->date);
                        $late4 = checkSecondOutLate($dt->second_out, $dt->shift, $dt->date);
                        // $late5 = checkOvertimeInLate($dt->overtime_in, $dt->shift, $dt->date);
                        $total_late_1 = $total_late_1 + $late1;
                        $total_late_2 = $total_late_2 + $late2;
                        $total_late_3 = $total_late_3 + $late3;
                        $total_late_4 = $total_late_4 + $late4;
                        // $total_late_5 = $total_late_5 + $late5;
                        // $total_late = $total_late_1 + $total_late_2 + $total_late_3 + $total_late_4 + $total_late_5;
                        if($dt->second_in == null && $dt->second_out == null) {
                            if(is_saturday( $dt->date )) {

                                $total_late_5 = 2;
                            } else {
                                $total_late_5 = 4;

                            }
                        }
                        $total_late = $total_late_1 + $total_late_2 + $total_late_3 + $total_late_4 + $total_late_5;
                        if ($dt->overtime_in != null) {
                            $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                        }
                    }
                    $total_noscan = $total_noscan + $n_noscan;

                }
                $dt_name = $dt->name;
                $dt_date = $dt->date;
                $dt_karyawan_id = $dt->karyawan_id;

                $jumlah_jam_terlambat = $jumlah_jam_terlambat + $late;
            }
            // DATA TOTAL
            if($total_noscan == 0) $total_noscan=null;
            $jumlah_jam_kerja = $jam_kerja  - $total_late ;
            // $jumlah_jam_kerja = $jumlah_hari_kerja * 8 - $total_late ;

            $data = Jamkerjaid::find($data->id);
            // dd($dt_name, $dt_date);
            // $data->name = $dt_name;
            $data->karyawan_id = $dt_karyawan_id;
            // $data->user_id = $dt_user_id;
            // dd($dt_karyawan_id, $dt_user_id );
            $data->date = buatTanggal($dt_date);
            // $data->last_data_date = $last_data_date;
            $data->last_data_date = $last_data_date->date;
            $data->jumlah_jam_kerja = $jumlah_jam_kerja;
            $data->jumlah_menit_lembur = $jumlah_menit_lembur;
            $data->total_noscan = $total_noscan;
            $data->jumlah_jam_terlambat = $total_late == 0 ? null : $total_late;
            $data->first_in_late = $total_late_1 == 0 ? null : $total_late_1;
            $data->first_out_late = $total_late_2 == 0 ? null : $total_late_2;
            $data->second_in_late = $total_late_3 == 0 ? null : $total_late_3;
            $data->second_out_late = $total_late_4 == 0 ? null : $total_late_4;
            // $data->overtime_in_late = $total_late_5 == 0 ? null : $total_late_5;
            $data->save();
        }
        $current_date = Jamkerjaid::orderBy('date', 'desc')->first();
        $this->periode = $current_date->date;

        $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built');
    }

    public function render()
    {
        $periodePayroll = DB::table('yfrekappresensis')
            ->select(DB::raw('YEAR(date) year, MONTH(date) month, MONTHNAME(date) month_name'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        $this->cx++;

        $filteredData = Jamkerjaid::select(['jamkerjaids.*', 'karyawans.nama'])
        ->join('karyawans', 'jamkerjaids.karyawan_id','=', 'karyawans.id')
        ->whereDate('date', 'like', '%' . $this->periode . '%')
            ->orderBy($this->columnName, $this->direction)
            ->when($this->search, function ($query) {
                $query
                    // ->where('name', 'LIKE', '%' . trim($this->search) . '%')
                    // ->orWhere('name', 'LIKE', '%' . trim($this->search) . '%')
                    ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                    // ->orWhere('user_id', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('user_id', trim($this->search))
                    ->orWhere('jabatan', trim($this->search))
                    // ->orWhere('department', 'LIKE', '%' . trim($this->search) . '%')
                    // ->orWhere('shift', 'LIKE', '%' . trim($this->search) . '%')
                    ->where('date', 'like', '%' . $this->periode . '%');
            })
            ->orderBy('user_id', 'asc')
            ->paginate($this->perpage);
        if ($filteredData->isNotEmpty()) {
            $lastData = $filteredData[0]->last_data_date;
        } else {
            $lastData = null;
        }

        return view('livewire.prindexwr', compact(['filteredData', 'periodePayroll', 'lastData']));
    }
}
