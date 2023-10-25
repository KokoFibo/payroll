<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Exports\KaryawanExport;
// use Illuminate\Support\Facades\DB;
use App\Exports\DataPelitaExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Query\Builder;

class Karyawanindexwr extends Component
{
    use WithPagination;

    public $search = '';
    public $columnName = 'id_karyawan';
    public $direction = 'desc';
    public $id;
    public $selectedAll = [];

    #[On('delete')]
    public function delete()
    {
        Karyawan::find($this->id)->delete();
        $this->dispatch('success', message: 'Data Berhasil di delete');
    }

    public function confirmDelete($id)
    {
        $this->id = $id;
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda Yakin',
            'text' => 'isi text dengan apa?',
            'id' => $id,
        ]);
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
    public function excel()
    {
        return Excel::download(new karyawanExport($this->selectedAll), 'Karyawan.xlsx');
    }

    public function render()
    {
        // $datas1 = Karyawan::where('nama', 'LIKE', '%' . trim($this->search) . '%')
        //     ->orWhere('branch', 'LIKE', '%' . trim($this->search) . '%')
        //     ->orWhere('id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
        //     ->orWhere('departemen', 'LIKE', '%' . trim($this->search) . '%')
        //     ->orderBy($this->columnName, $this->direction);
        // $this->selectedAll = $datas1->pluck('id');
        // $datas = $datas1->paginate(10);

        //Query ini untuk ikut filter status karyawan (buat chat GPT)
        $datasQuery = Karyawan::whereIn('status_karyawan', ['PKWT', 'Karyawan Tetap']);
        if ($this->search) {
            $search = '%' . trim($this->search) . '%';
            $datasQuery->where(function ($query) use ($search) {
                $query
                    ->where('nama', 'LIKE', $search)
                    ->orWhere('branch', 'LIKE', $search)
                    ->orWhere('id_karyawan', 'LIKE', $search)
                    ->orWhere('departemen', 'LIKE', $search);
            });
        }
        $datasQuery->orderBy($this->columnName, $this->direction);
        $perPage = 10;
        $this->selectedAll = $datasQuery->pluck('id');
        $datas = $datasQuery->paginate($perPage);

        return view('livewire.karyawanindexwr', compact(['datas']));
    }
}
