<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use Livewire\Component;
use App\Models\Jamkerjaid;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Prindexwr extends Component
{
    use WithPagination;
    public $periode;
    public $search = '';
    public $cx = 0;
    public $columnName = 'user_id';
    public $direction = 'desc';
    public $perpage = 10;
    public $year;
    public $month;




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
        $this->year =  now()->year;
        $this->month =  now()->month;

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


// ok1
    #[On('getPayroll')]
    public function getPayroll()
    {

        // supaya tidak dilakukan bersamaan
        $lock = Lock::find(1);
        if($lock->build) {

            return back()->with( 'error', 'Mohon dicoba sebentar lagi' );
        } else {
            $lock->build = 1;
            $lock->save();
        }


        $jamKerjaKosong = Jamkerjaid::count();
        $adaPresensi = Yfrekappresensi::count();
        if ($jamKerjaKosong == null && $adaPresensi == null) {
            clear_locks();
            $this->dispatch('error', message: 'Data Presensi Masih Kosong');
            return back();
        }

        // AMBIL DATA TERAKHIR DARI REKAP PRESENSI PADA BULAN YBS
        $last_data_date = Yfrekappresensi::query()
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'desc')
            ->first();



        // $tglsementara = Yfrekappresensi::where('no_scan', 'No Scan')
        //     ->whereYear('date', $this->year)
        //     ->whereMonth('date', $this->month)
        //     ->count();

        // if ($tglsementara) {
        //     clear_locks();
        //     $this->dispatch('error', message: 'Masih ada data no scan');
        //     return back();
        // }

        $checkIfJamKerjaExist = Jamkerjaid::whereMonth('date', $this->month)
        ->whereYear('date', $this->year)
        ->first();

        Jamkerjaid::whereMonth('date', $this->month)
        ->whereYear('date', $this->year)
        ->delete();

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

        $filterArray = Yfrekappresensi::whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            // ->where('status')
            ->pluck('user_id')
            ->unique();

        // buat tabel user_id unique ook
        foreach ($filterArray as $item) {
            $filteredData = new Jamkerjaid();
            $filteredData->user_id = $item;
            $filteredData->karyawan_id = 1;
            $filteredData->date = $this->year.'-'.$this->month.'-01';
            $filteredData->save();
        }
        $filteredData = Jamkerjaid::with('karyawan')->whereMonth('date', $this->month)
        ->whereYear('date', $this->year)->get();
        foreach ($filteredData as $data) {
            $jumlah_menit_lembur = 0;
            $jumlah_jam_terlambat = 0;
            $jumlah_menit_lembur = 0;
            $jumlah_hari_kerja = 0;

            $total_noscan = 0;
            $n_noscan = 0;

            $total_late_1 = 0;
            $total_late_2 = 0;
            $total_late_3 = 0;
            $total_late_4 = 0;
            $total_late_5 = 0;
            $total_late = 0;
            $jam_kerja = 0;
            $total_jam_kerja = 0;
            $total_langsungLembur = 0;
            $satpam_halfday = 0;

            $dataId = Yfrekappresensi::with('karyawan')->where('user_id', $data->user_id)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->get();


            if (!$dataId) {
                dd('data kosong from Prindex.php', $dataId);
            } else {
                // ambil data per user id ok3
                $n_noscan = 0;
                foreach ($dataId as $dt) {
                if($dt->no_scan != 'No Scan') {

                    $langsungLembur = 0 ;
                    $jam_kerja = 0;

                    if($dt->no_scan_history == 'No Scan') {
                            $n_noscan++;
                        }
                $jam_kerja = hitung_jam_kerja($dt->first_in, $dt->first_out, $dt->second_in, $dt->second_out, $dt->late, $dt->shift, $dt->date, $dt->karyawan->jabatan);
                // if($dt->shift == 'Malam' || is_jabatan_khusus($dt->user_id)) {
                    $langsungLembur = langsungLembur( $dt->second_out, $dt->date, $dt->shift, $dt->karyawan->jabatan);
                // }

                // $jam_kerja = $jam_kerja_harian;
                $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                $total_langsungLembur = $total_langsungLembur + ($langsungLembur * 60 );



                    if ($dt->late == null) {
                        // if($dt->no_scan_history) {
                        //     $n_noscan++;
                        // }
                        // $n_noscan = $dt->no_scan_history;

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
                                clear_locks();
                                $this->dispatch('foundError', title: $errorId);

                                return $e->getMessage();
                                //  dd($dt->user_id, $dt->date);
                            }
                        }
                    } else {
                        // khusus yang late

                        $jumlah_hari_kerja = $dataId->count();
                        // if($dt->no_scan_history) {
                        //     $n_noscan++;
                        // }


                        // check keterlambatan di hari kerja non overtime
                        $late1 = checkFirstInLate($dt->first_in, $dt->shift, $dt->date);
                        $late2 = checkFirstOutLate($dt->first_out, $dt->shift, $dt->date, $dt->karyawan->jabatan);
                        $late3 = checkSecondInLate($dt->second_in, $dt->shift, $dt->first_out, $dt->date, $dt->karyawan->jabatan);
                        $late4 = checkSecondOutLate($dt->second_out, $dt->shift, $dt->date, $dt->karyawan->jabatan);
                        // $late5 = checkOvertimeInLate($dt->overtime_in, $dt->shift, $dt->date);

                        if($dt->karyawan->jabatan == 'Satpam') {
                            if($late4 >= 6) {

                                $satpam_halfday = $satpam_halfday + 0.5;
                            }
                        }

                        if(($dt->second_in === null && $dt->second_out === null) || ($dt->first_in === null && $dt->first_out === null)){
                            $late1 = 0;
                            $late2 = 0;
                            $late3 = 0;
                            $late4 = 0;
                        }


                        $total_late_1 = $total_late_1 + $late1;
                        $total_late_2 = $total_late_2 + $late2;
                        $total_late_3 = $total_late_3 + $late3;
                        $total_late_4 = $total_late_4 + $late4;

                        if(($dt->second_in === null && $dt->second_out === null) || ($dt->first_in === null && $dt->first_out === null)){
                            if(is_saturday( $dt->date )) {
                                if($dt->first_in === null && $dt->first_out === null) {
                                    $jam_kerja = $jam_kerja - 4;
                                } else {
                                    $jam_kerja = $jam_kerja -2;
                                }
                            } else {
                                $jam_kerja = $jam_kerja - 4;
                            }


                        }
                        $total_late = $total_late_1 + $total_late_2 + $total_late_3 + $total_late_4 ;
                        if ($dt->overtime_in != null) {
                            $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                            $jumlah_menit_lembur = $jumlah_menit_lembur + $menitLembur;
                        }
                    }
                    $total_noscan = $total_noscan + $n_noscan;

                }
                }
                $dt_name = $dt->name;
                $dt_date = $dt->date;
                $dt_karyawan_id = $dt->karyawan_id;

                $jumlah_jam_terlambat = $jumlah_jam_terlambat + $late;
            }
            // DATA TOTAL per id yang sdh terkumpul ok4

            if($total_noscan == 0) $total_noscan=null;
            // $jumlah_jam_kerja = $jam_kerja  - $total_late ;
            $jumlah_jam_kerja = $total_jam_kerja  - $total_late ;

            $data = Jamkerjaid::find($data->id);

            $data->karyawan_id = $dt_karyawan_id;

            $data->date = buatTanggal($dt_date);
            // $data->last_data_date = $last_data_date;
            $data->last_data_date = $last_data_date->date;
            $data->jumlah_jam_kerja = $jumlah_jam_kerja;
            $data->jumlah_menit_lembur = $jumlah_menit_lembur + $total_langsungLembur;
            // $data->total_noscan = $total_noscan;
            $data->total_noscan = $n_noscan;
            $data->jumlah_jam_terlambat = $total_late == 0 ? null : $total_late;
            $data->first_in_late = $total_late_1 == 0 ? null : $total_late_1;
            $data->first_out_late = $total_late_2 == 0 ? null : $total_late_2;
            $data->second_in_late = $total_late_3 == 0 ? null : $total_late_3;
            $data->second_out_late = $total_late_4 == 0 ? null : $total_late_4;
            $data->total_hari_kerja = $jumlah_hari_kerja - $satpam_halfday;
            // $data->overtime_in_late = $total_late_5 == 0 ? null : $total_late_5;
            $data->save();
        }
        $current_date = Jamkerjaid::orderBy('date', 'desc')->first();
        // $this->periode = $current_date->date;

        $lock->build = false;
        $lock->save();

        $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built');
    }
    // ok2

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
    ->join('karyawans', 'jamkerjaids.karyawan_id', '=', 'karyawans.id')
    ->where(function ($query) {
        $query
        ->whereMonth('date',  $this->month )
            ->whereYear('date', $this->year )
        // ->whereMonth('date', 'like', '%' . $this->month . '%')
        //     ->whereYear('date', 'like', '%' . $this->year . '%')
            ->when($this->search, function ($subQuery) {
                $subQuery->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('user_id', trim($this->search))
                    ->orWhere('jabatan', trim($this->search));
            });
    })
    ->orderBy($this->columnName, $this->direction)
    ->orderBy('user_id', 'asc')
    ->paginate($this->perpage);

    // $filteredData = Jamkerjaid::with('karyawan')
    // ->whereMonth('date', 'like', '%' . $this->month . '%')
    // ->whereYear('date', 'like', '%' . $this->year . '%')
    // ->orWhereRelation('karyawan', 'nama', 'LIKE', '%' . trim($this->search) . '%')
    // ->orWhereRelation('karyawan', 'user_id', trim($this->search))
    // ->orWhereRelation('karyawan', 'jabatan', trim($this->search))
    // ->orderBy($this->columnName, $this->direction)
    // ->sortBy('karyawan.id_karyawan')
    // ->paginate($this->perpage);

        if ($filteredData->isNotEmpty()) {
            $lastData = $filteredData[0]->last_data_date;
        } else {
            $lastData = null;
        }


        return view('livewire.prindexwr', compact(['filteredData', 'periodePayroll', 'lastData']));
    }
}
