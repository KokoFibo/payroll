<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Models\Yfrekappresensi;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

class Yfpresensiindexwr extends Component
{
    use WithPagination;
    public $search = '';
    public $columnName = 'no_scan_history';
    public $direction = 'desc';
    public $first_in;
    public $first_out;
    public $second_in;
    public $second_out;
    public $overtime_in;
    public $overtime_out;
    public $tanggal;
    public $shift;
    public $date;
    public $user_id;
    public $name;
    public $id;
    public $no_scan = null;
    public $late = null;
    public $btnEdit = true;
    public $selectedId;

    #[On('delete')]
    public function delete($id) {
        Yfrekappresensi::find($id)->delete();
        $this->dispatch('success', message: 'Data Presensi Sudah di Delete');
    }







    public function filterNoScan()
    {
        $this->columnName = 'no_scan_history';
        $this->direction = 'desc';
        $this->search = null;
        $this->resetPage();
        $this->render();
    }
    public function filterLate()
    {
        $this->columnName = 'late_history';
        $this->direction = 'desc';
        $this->search = null;
        $this->resetPage();
        $this->render();
    }

    public function resetTanggal()
    {
        $this->tanggal = null;
        $this->columnName = 'no_scan_history';
        $this->direction = 'desc';
        $this->search = null;
        $this->resetPage();
        $this->render();
    }
    public function update($id)
    {

        // dd('ok');
        $this->id = $id;
        $data = Yfrekappresensi::find($id);
        $this->first_in = trimTime($data->first_in);
        $this->first_out = trimTime($data->first_out);
        $this->second_in = trimTime($data->second_in);
        $this->second_out = trimTime($data->second_out);
        $this->overtime_in = trimTime($data->overtime_in);
        $this->overtime_out = trimTime($data->overtime_out);
        $this->user_id = $data->user_id;
        // $this->name = $data->name;
        $this->shift = $data->shift;
        $this->date = $data->date;
        $this->btnEdit = false;
        $this->selectedId = $this->id;


        // $this->dispatch('update-form');
    }

    public function save()
    {


        $this->validate([
            'first_in' => 'date_format:H:i|nullable',
            'first_out' => 'date_format:H:i|nullable',
            'second_in' => 'date_format:H:i|nullable',
            'second_out' => 'date_format:H:i|nullable',
            'overtime_in' => 'date_format:H:i|nullable',
            'overtime_out' => 'date_format:H:i|nullable',
        ]);
        // proses penambahan  00 untuk data yg ada isi dan null utk data kosong
        $this->first_in != null ? ($this->first_in = $this->first_in . ':00') : ($this->first_in = null);
        $this->first_out != null ? ($this->first_out = $this->first_out . ':00') : ($this->first_out = null);
        $this->second_in != null ? ($this->second_in = $this->second_in . ':00') : ($this->second_in = null);
        $this->second_out != null ? ($this->second_out = $this->second_out . ':00') : ($this->second_out = null);
        $this->overtime_in != null ? ($this->overtime_in = $this->overtime_in . ':00') : ($this->overtime_in = null);
        $this->overtime_out != null ? ($this->overtime_out = $this->overtime_out . ':00') : ($this->overtime_out = null);

        $data = Yfrekappresensi::find($this->id);

        $data->first_in = $this->first_in;
        $data->first_out = $this->first_out;
        $data->second_in = $this->second_in;
        $data->second_out = $this->second_out;
        $data->overtime_in = $this->overtime_in;
        $data->overtime_out = $this->overtime_out;
        $data->no_scan = noScan($this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->overtime_out);
        $data->late = late_check_detail($this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->shift, $this->date);

        $data->save();
        $this->btnEdit = true;

        $this->dispatch('success', message: 'Data sudah di update');
        // $this->dispatch('hide-form');

    }

    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTanggal()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->tanggal == null) {
            $lastDate = Yfrekappresensi::orderBy('date', 'desc')->first();
            if ($lastDate == null) {
                $this->tanggal = null;
            } else {
                $this->tanggal = Carbon::parse($lastDate->date)->format('Y-m-d');
            }
        }
        $totalHadir = Yfrekappresensi::query()
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalHadirPagi = Yfrekappresensi::where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $overallNoScan = Yfrekappresensi::where('no_scan', 'No Scan')->count();
        $totalNoScan = Yfrekappresensi::where('no_scan', 'No Scan')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalNoScanPagi = Yfrekappresensi::where('no_scan', 'No Scan')
            ->where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalLate = Yfrekappresensi::where('late','>', '0')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalLatePagi = Yfrekappresensi::where('late','>', '0')
            ->where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();

        // $datas = Yfrekappresensi::whereDate('date', 'like', '%' . $this->tanggal . '%')
        //     ->orderBy($this->columnName, $this->direction)
        //     ->when($this->search, function ($query) {
        //         $query
        //             ->where('name', 'LIKE', '%' . trim($this->search) . '%')
        //             ->orWhere('name', 'LIKE', '%' . trim($this->search) . '%')
        //             // ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
        //             // ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
        //             ->orWhere('user_id', trim($this->search))
        //             ->orWhere('department', 'LIKE', '%' . trim($this->search) . '%')
        //             // ->orWhere('departemen', 'LIKE', '%' . trim($this->search) . '%')
        //             ->orWhere('shift', 'LIKE', '%' . trim($this->search) . '%')
        //             ->where('date', 'like', '%' . $this->tanggal . '%');
        //     })
        //     ->paginate(10);


        $datas = Yfrekappresensi::select(['yfrekappresensis.*', 'karyawans.nama', 'karyawans.departemen'])
        ->join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
        ->whereDate('date', 'like', '%' . $this->tanggal . '%')
            ->orderBy($this->columnName, $this->direction)
            ->when($this->search, function ($query) {
                $query
                    // ->where('name', 'LIKE', '%' . trim($this->search) . '%')
                    // ->orWhere('name', 'LIKE', '%' . trim($this->search) . '%')
                    ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('user_id', trim($this->search))
                    // ->orWhere('department', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('departemen', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('shift', 'LIKE', '%' . trim($this->search) . '%')
                    ->where('date', 'like', '%' . $this->tanggal . '%');
            })
            ->paginate(10);





        return view('livewire.yfpresensiindexwr', compact(['datas', 'totalHadir', 'totalHadirPagi',
        'totalNoScan', 'totalNoScanPagi', 'totalLate', 'totalLatePagi', 'overallNoScan'
    ]));
    }
}
