<?php

namespace App\Livewire;

use App\Models\Branch;
use Livewire\Component;
use App\Models\Karyawan;

class ChangeFieldData extends Component
{
    public function changeBranch () {
        $branch = Branch::all();
        foreach($branch as $br) {
            $filteredKaryawan = Karyawan::where('branch', $br->branch)->get(['branch', 'id']);
            foreach($filteredKaryawan as $filtered) {
                $data = Karyawan::find($filtered->id);
                $data->branch = $br->id;
                $data->update();
            }
        }
        $this->dispatch('success', message: 'Branch sudah dirubah dengan sukses');
    }
    public function render()
    {

        return view('livewire.change-field-data');
    }
}
