<?php

namespace App\Livewire;

use App\Models\Yfrekappresensi;
use Livewire\Component;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class Newpresensi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $tanggal;
    public $sortField = 'user_id';
    public $sortDirection = 'asc';
    public $search = '';


    public function mount()
    {
        $lastdate = Yfrekappresensi::orderBy('date', 'desc')->first();
        $this->tanggal = $lastdate->date;
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function prevDate()
    {
        $this->tanggal = \Carbon\Carbon::parse($this->tanggal)
            ->subDay()
            ->toDateString();
        $this->resetPage();
    }

    public function nextDate()
    {
        $this->tanggal = \Carbon\Carbon::parse($this->tanggal)
            ->addDay()
            ->toDateString();
        $this->resetPage();
    }


    public function updatedTanggal()
    {
        $this->resetPage();
    }






    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Yfrekappresensi::join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
            ->select(
                'yfrekappresensis.*',
                'karyawans.nama',
                'karyawans.metode_penggajian',
                'karyawans.placement_id',
                'karyawans.jabatan_id'
            )
            ->where('yfrekappresensis.date', $this->tanggal);

        // 🔍 Filter pencarian
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('karyawans.nama', 'like', '%' . $this->search . '%')
                    ->orWhere('yfrekappresensis.user_id', 'like', '%' . $this->search . '%');
            });
        }

        // 🔽 Sorting dinamis
        if (in_array($this->sortField, [
            'user_id',
            'nama',
            'metode_penggajian',
            'placement_id',
            'jabatan_id',
            'date'
        ])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('yfrekappresensis.user_id', 'asc');
        }

        $datas = $query->paginate(10);

        return view('livewire.newpresensi', ['datas' => $datas]);
    }
}
