<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class Yfpresensiindexwr extends Component
{
    use WithPagination;
    public $search='';
    public $columnName="no_scan";
    public $direction = 'desc';

    public function sortColumnName ($namaKolom) {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection () {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }
    public function updatingSearch () {
        $this->resetPage();

    }
    public function render()
    {

        $totalNoScan = Yfrekappresensi::where('no_scan','!=',null)->count();
        $totalLate = Yfrekappresensi::where('late','!=',null)->count();
        $datas = Yfrekappresensi::where('user_id','LIKE','%'.trim($this->search).'%')
        ->orWhere('name','LIKE','%'.trim($this->search).'%')
        ->orderBy($this->columnName, $this->direction)->paginate(10);
        return view('livewire.yfpresensiindexwr', compact(['datas', 'totalNoScan', 'totalLate']));
    }
}
