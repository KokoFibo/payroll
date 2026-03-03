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
            ->orderBy('id_karyawan', 'asc')
            ->get();
        foreach ($karyawans as $karyawan) {

            $thr = hitungTHR(
                $karyawan->tanggal_bergabung,
                $karyawan->gaji_pokok,
                $karyawan->id_karyawan,
                $this->cutOffDate
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


    public function render()
    {
        $query = Karyawan::whereNotIn('etnis', ['China', 'Tionghoa'])
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->orderBy('id_karyawan', 'asc')
            ->where('tanggal_bergabung', '<', $this->cutoffMinus30);

        $data = $query->get();
        // dd($data->count());

        $total = $data->sum(function ($d) {
            return hitungTHR(
                $d->tanggal_bergabung,
                $d->gaji_pokok,
                $d->id_karyawan,
                $this->cutOffDate
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
