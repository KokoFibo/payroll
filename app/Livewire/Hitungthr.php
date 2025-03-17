<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;


class Hitungthr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $karyawans = Karyawan::with(['placement', 'company', 'department', 'jabatan'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->orderBy('id', 'desc')->paginate(10);





        return view('livewire.hitungthr', [
            'karyawans' => $karyawans
        ]);
    }
}
