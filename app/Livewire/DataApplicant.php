<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Applicantdata;
use App\Models\Applicantfile;

class DataApplicant extends Component
{
    use WithPagination;
    public $show_data, $show_table, $personal_data, $personal_files;

    public function mount()
    {
        $this->show_table = true;
        $this->show_data = false;
    }


    protected $paginationTheme = 'bootstrap';

    public function show($id)
    {
        $this->personal_data = Applicantdata::find($id);
        $this->personal_files = Applicantfile::where('id_karyawan', $this->personal_data->applicant_id)->get();
        $this->show_data = true;
        $this->show_table = false;
    }

    public function waitingList($id)
    {
        $this->personal_data = Applicantdata::find($id);
        $this->personal_data->status = 'Waiting List';
        $this->personal_data->save();
        $this->dispatch('success', message: 'Status Telah di rubah menjadi "Waiting List"');
    }

    public function diterima($id)
    {
        $this->personal_data = Applicantdata::find($id);
        $this->personal_data->status = 'Diterima';
        $this->personal_data->save();
        $this->dispatch('success', message: 'Status Telah di rubah menjadi "Diterima"');
    }

    public function kembali()
    {
        $this->show_data = false;
        $this->show_table = true;
    }

    public function render()
    {
        $data = Applicantdata::orderBy('created_at', 'asc')->paginate(10);

        return view('livewire.data-applicant', [
            'data' => $data,
        ]);
    }
}
