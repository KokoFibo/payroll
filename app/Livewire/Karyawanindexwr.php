<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\WithPagination;
// use Illuminate\Support\Facades\DB;
use App\Exports\KaryawanExport;
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
    public $selectStatus = 'Aktif';
    public $perpage = 10;

    #[On('delete')]
    public function delete()
    {

        $Data_Karyawan = Karyawan::find($this->id);
        $dataUser = User::where( 'username', $Data_Karyawan->id_karyawan )->first();
        if ( $dataUser->id ) {
            $user = User::find( $dataUser->id );
            $user->delete();
            $Data_Karyawan->delete();
            $this->dispatch( 'success', message: 'Data Karyawan Sudah di delete' );
        }else
        {
            $this->dispatch( 'info', message: 'Data Karyawan Sudah di Delete, User tidak terdelete' );
        }

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

        //Query ini untuk ikut filter status karyawan ( yang buat chat GPT)
        switch($this->selectStatus) {
            case 'All' : $datasQuery = Karyawan::query(); break;
            case 'Aktif' : $datasQuery = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT']); break;
            case 'Non Aktif':  $datasQuery = Karyawan::whereNotIn('status_karyawan', ['PKWT', 'PKWTT']); break;
        }
        // $datasQuery = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT']);
        // $datasQuery = Karyawan::query();
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
        $this->selectedAll = $datasQuery->pluck('id');
        $datas = $datasQuery->paginate($this->perpage);

        return view('livewire.karyawanindexwr', compact(['datas']));
    }
}
