<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Yfpresensiindexwr extends Component
{
    use WithPagination;
    public $search = '';
    public $columnName = 'no_scan';
    public $direction = 'desc';
    public $first_in;
    public $first_out;
    public $second_in;
    public $second_out;
    public $overtime_in;
    public $overtime_out;
    public $tanggal;
    public $shift;
    public $user_id;
    public $name;
    public $id;
    public $no_scan = null;
    public $late = null;

    public function update($id)
    {
        $this->id = $id;
        $data = Yfrekappresensi::find($id);

        $this->first_in = trimTime($data->first_in);
        $this->first_out = trimTime($data->first_out);
        $this->second_in = trimTime($data->second_in);
        $this->second_out = trimTime($data->second_out);
        $this->overtime_in = trimTime($data->overtime_in);
        $this->overtime_out = trimTime($data->overtime_out);
        $this->user_id = $data->user_id;
        $this->name = $data->name;
        $this->shift = $data->shift;

        // $this->dispatch('update-form');
    }
    public function save()
    {
        $this->validate([
            'first_in' => 'date_format:H:i',
            'first_out' => 'date_format:H:i',
            'second_in' => 'date_format:H:i',
            'second_out' => 'date_format:H:i',
            'overtime_in' => 'date_format:H:i',
            'overtime_out' => 'date_format:H:i',
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
        $data->late = late_check_detail($this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->shift);

        $data->save();
        $this->dispatch('hide-form');
        $this->dispatch('success', message: 'Data sudah di update');
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
    public function resetTanggal()
    {
        $this->tanggal = null;
        $this->render();
    }

    public function render()
    {
        if ($this->tanggal == null) {
            $this->tanggal = Carbon::yesterday()->format('Y-m-d');
        }
        $totalHadir = Yfrekappresensi::query()
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalHadirPagi = Yfrekappresensi::where('shift', 'Shift Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalNoScan = Yfrekappresensi::where('no_scan', 'No Scan')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalNoScanPagi = Yfrekappresensi::where('no_scan', 'No Scan')
            ->where('shift', 'Shift Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalLate = Yfrekappresensi::where('late', '1')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalLatePagi = Yfrekappresensi::where('late', '1')
            ->where('shift', 'Shift Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();

        $datas = Yfrekappresensi::whereDate('date', 'like', '%' . $this->tanggal . '%')
            ->orderBy($this->columnName, $this->direction)
            ->when($this->search, function ($query) {
                $query
                    ->where('user_id', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('name', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('department', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('shift', 'LIKE', '%' . trim($this->search) . '%')
                    ->where('date', 'like', '%' . $this->tanggal . '%');
            })
            ->paginate(10);

        return view('livewire.yfpresensiindexwr', compact(['datas', 'totalHadir', 'totalHadirPagi', 'totalNoScan', 'totalNoScanPagi', 'totalLate', 'totalLatePagi']));
    }
}
