<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Karyawanindexwr extends Component
{
    use WithPagination;
    public $search='';
    public $columnName="id_karyawan";
    public $direction = 'desc';
    public $id;

    #[On('delete')]

    public function delete () {
        Karyawan::find($this->id)->delete();
        $this->dispatch('success', message:'Data Berhasil di delete');
    }

    public function confirmDelete ($id) {
       $this->id = $id;
       $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda Yakin',
            'text' => 'isi text dengan apa?',
            'id' => $id,
       ]);
    }

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
        // $datas = Karyawan::orderBy('id_karyawan', 'desc')->paginate(10);

        $datas = Karyawan::where('nama','LIKE','%'.trim($this->search).'%')
        ->orWhere('branch','LIKE','%'.trim($this->search).'%')
        ->orWhere('id_karyawan','LIKE','%'.trim($this->search).'%')
        ->orWhere('departemen','LIKE','%'.trim($this->search).'%')
        ->orderBy($this->columnName, $this->direction)->paginate(10);
        return view('livewire.karyawanindexwr', compact(['datas']));
    }
}
