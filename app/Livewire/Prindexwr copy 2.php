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
// ok1
    #[On('getPayroll')]
    public function getPayroll()
{
    // Check if there is no data for both Jamkerjaid and Yfrekappresensi
    $jamKerjaKosong = Jamkerjaid::count();
    $adaPresensi = Yfrekappresensi::count();
    if ($jamKerjaKosong == 0 && $adaPresensi == 0) {
        $this->dispatch('error', message: 'Data Presensi Masih Kosong');
        return back();
    }

    // AMBIL DATA TERAKHIR DARI REKAP PRESENSI PADA BULAN YBS
    $lastDataDate = Yfrekappresensi::whereMonth('date', getBulan($this->periode))
        ->whereYear('date', getTahun($this->periode))
        ->orderBy('date', 'desc')
        ->first();

    // Check if JamKerjaExist for the specified date
    $checkIfJamKerjaExist = Jamkerjaid::where('date', $this->periode)->first();

    // Check if there is data with 'No Scan'
    $tglSementaraCount = Yfrekappresensi::where('no_scan', 'No Scan')
        ->whereYear('date', getTahun($this->periode))
        ->whereMonth('date', getBulan($this->periode))
        ->count();

    if ($tglSementaraCount > 0) {
        $this->dispatch('error', message: 'Masih ada data no scan');
        return back();
    }

    // Delete existing records for the specified date in Jamkerjaid
    Jamkerjaid::where('date', $this->periode)->delete();

    // Get unique user_ids for the specified month and year
    $filterArray = Yfrekappresensi::whereMonth('date', getBulan($this->periode))
        ->whereYear('date', getTahun($this->periode))
        ->pluck('user_id')
        ->unique();

    // Create records in Jamkerjaid for each unique user_id
    foreach ($filterArray as $item) {
        Jamkerjaid::create([
            'user_id' => $item,
            'karyawan_id' => 1,
            'date' => $this->periode,
        ]);
    }

    // Process each record in Jamkerjaid
    foreach (Jamkerjaid::whereDate('date', $this->periode)->get() as $data) {
        $totalMenitLembur = 0;
        $totalJamTerlambat = 0;
        $totalNoscan = 0;
        $totalLate = 0;
        $totalLate_1 = 0;
        $totalLate_2 = 0;
        $totalLate_3 = 0;
        $totalLate_4 = 0;

        // Get data for the user_id and specified month-year
        $dataId = Yfrekappresensi::where('user_id', $data->user_id)
            ->whereMonth('date', getBulan($this->periode))
            ->whereYear('date', getTahun($this->periode))
            ->get();

        if ($dataId->isEmpty()) {
            dd('data kosong from Prindex.php', $dataId);
        }
        $totalJamKerja = 0;
        $totalLangsungLembur = 0;
        foreach ($dataId as $dt) {
            // Process each data entry
            $langsungLembur = 0;
            $jamKerja = hitung_jam_kerja($dt->first_in, $dt->first_out, $dt->second_in, $dt->second_out, $dt->late, $dt->shift, $dt->date, $dt->karyawan->jabatan);
            $langsungLembur = langsungLembur($dt->second_out, $dt->date, $dt->shift, $dt->karyawan->jabatan);

            $totalJamKerja += $jamKerja;
            $totalLangsungLembur += ($langsungLembur * 60);

            if ($dt->late == null) {
                // Process for no late
                if ($dt->overtime_in != null) {
                    try {
                        $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                        $totalMenitLembur += $menitLembur;
                    } catch (\Exception $e) {
                        $errorId = 'Error user ID: ' . $dt->user_id . ', Tanggal : ' . $dt->date;
                        $this->dispatch('foundError', title: $errorId);
                        return $e->getMessage();
                    }
                }
            } else {
                // Process for late
                if($dt->no_scan_history != null) $noScan = 1;
                $totalNoscan += $noScan ?? 0;
                $late1 = checkFirstInLate($dt->first_in, $dt->shift, $dt->date);
                $late2 = checkFirstOutLate($dt->first_out, $dt->shift, $dt->date, $dt->karyawan->jabatan);
                $late3 = checkSecondInLate($dt->second_in, $dt->shift, $dt->first_out, $dt->date, $dt->karyawan->jabatan);
                $late4 = checkSecondOutLate($dt->second_out, $dt->shift, $dt->date, $dt->karyawan->jabatan);

                if (($dt->second_in === null && $dt->second_out === null) || ($dt->first_in === null && $dt->first_out === null)) {
                    $late1 = $late2 = $late3 = $late4 = 0;
                    if (is_saturday($dt->date)) {
                        $jamKerja = $dt->first_in === null && $dt->first_out === null ? $jamKerja - 4 : $jamKerja - 2;
                    } else {
                        $jamKerja = $jamKerja - 4;
                    }
                }

                $totalLate += $late1 + $late2 + $late3 + $late4;

                if ($dt->overtime_in != null) {
                    $menitLembur = hitungLembur($dt->overtime_in, $dt->overtime_out);
                    $totalMenitLembur += $menitLembur;
                }
            }
        }

        // Update Jamkerjaid records with the calculated values
        $data->update([
            'karyawan_id' => $dt->karyawan_id,
            'date' => buatTanggal($dt->date),
            'last_data_date' => $lastDataDate->date,
            'jumlah_jam_kerja' => $totalJamKerja - $totalLate,
            'jumlah_menit_lembur' => $totalMenitLembur + $totalLangsungLembur,
            'total_noscan' => $totalNoscan,
            'jumlah_jam_terlambat' => $totalLate == 0 ? null : $totalLate,
            'first_in_late' => $totalLate_1 == 0 ? null : $totalLate_1,
            'first_out_late' => $totalLate_2 == 0 ? null : $totalLate_2,
            'second_in_late' => $totalLate_3 == 0 ? null : $totalLate_3,
            'second_out_late' => $totalLate_4 == 0 ? null : $totalLate_4,
        ]);
    }

    // Get the current date from the latest Jamkerjaid record
    $currentDate = Jamkerjaid::orderBy('date', 'desc')->first();
    $this->periode = $currentDate->date;

    // Dispatch success message
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
