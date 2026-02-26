<?php

namespace App\Livewire;

use App\Exports\THRLebaranExport;
use App\Models\Bonuspotongan;
use App\Models\Karyawan;
use App\Models\Payroll;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Hitungthrlebaran extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $cutOffDate;
    public $cutoffMinus30;


    // $this->dispatch(
    //             'message',
    //             type: 'success',
    //             title: 'Placement added',
    //         );


    public function pindahKeBonusPotongan()
    {
        $karyawans = Karyawan::whereNotIn('etnis', ['China', 'Tionghoa'])
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->where('tanggal_bergabung', '<', $this->cutoffMinus30)
            ->get();
        foreach ($karyawans as $karyawan) {

            $thr = $this->hitungTHR(
                $karyawan->tanggal_bergabung,
                $karyawan->gaji_pokok,
                $karyawan->id_karyawan
            );

            $record = Bonuspotongan::where('user_id', $karyawan->id_karyawan)
                ->whereYear('tanggal', 2026)
                ->whereMonth('tanggal', 2)
                ->first();
            if ($record) {
                // dd($record);
                $record->update([
                    'thr' => $thr
                ]);
            } else {
                Bonuspotongan::create([
                    'karyawan_id' => $karyawan->id,
                    'user_id'     => $karyawan->id_karyawan,
                    'tanggal'     => Carbon::create(2026, 2, 26),
                    'thr'         => $thr
                ]);
            }
        }

        $this->dispatch(
            'message',
            type: 'success',
            title: 'THR berhasil dipindahkan ke Bonus & Potongan',
        );
    }


    public function mount()
    {
        $this->cutOffDate = '2026-03-21';
        $this->cutoffMinus30 = Carbon::parse($this->cutOffDate)->subDays(30);
    }

    public function excel()
    {
        $nama_file = 'THR_LEBARAN_OS_2026.xlsx';
        return Excel::download(new THRLebaranExport($this->cutOffDate), $nama_file);
    }


    /**
     * Hitung THR berdasarkan tabel reward
     */
    public function hitungTHR($tanggal_bergabung, $gaji_pokok, $id_karyawan)
    {
        $masaKerja = Carbon::parse($tanggal_bergabung)
            ->diffInMonths(Carbon::parse($this->cutOffDate));

        $thrTable = [
            1  => 100000,
            2  => 200000,
            3  => 300000,
            4  => 400000,
            5  => 550000,
            6  => 100000,
            7  => 250000,
            8  => 400000,
            9  => 600000,
            10 => 800000,
            11 => 1000000,
        ];

        if ($masaKerja >= 12) {
            $data = Payroll::whereMonth('date', 3)->whereYear('date', 2025)->where('id_karyawan', $id_karyawan)->first();
            return $data->gaji_pokok ?? 0; // 1 bulan gaji penuh tahun lalu
        }

        return $thrTable[$masaKerja] ?? 0;
    }

    public function render()
    {
        $query = Karyawan::whereNotIn('etnis', ['China', 'Tionghoa'])
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->where('tanggal_bergabung', '<', $this->cutoffMinus30);

        $data = $query->get();
        // dd($data->count());

        $total = $data->sum(function ($d) {
            return $this->hitungTHR(
                $d->tanggal_bergabung,
                $d->gaji_pokok,
                $d->id_karyawan
            );
        });

        $karyawans = (clone $query)
            ->orderBy('tanggal_bergabung', 'desc')
            ->paginate(10);

        return view('livewire.hitungthrlebaran', [
            'karyawans' => $karyawans,
            'total' => $total,
            'cutOffDate' => $this->cutOffDate,
        ]);
    }
}
