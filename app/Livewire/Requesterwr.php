<?php

namespace App\Livewire;

use App\Models\Karyawan;
use Livewire\Component;

class Requesterwr extends Component
{
    public $requestId, $approveBy1, $approveBy2;
    public $namaRequestId, $namaApproveBy1, $namaApproveBy2;

    public function updatedRequestId()
    {
        $data = Karyawan::where('id_karyawan', $this->requestId)->first();
        if ($data != null) {

            $this->namaRequestId = $data->nama;
        }
    }
    public function updatedApproveBy1()
    {
        $data = Karyawan::where('id_karyawan', $this->requestId)->first();
        $this->namaApproveBy1 = $data->nama;
    }
    public function updatedApproveBy2()
    {
        $data = Karyawan::where('id_karyawan', $this->requestId)->first();

        $this->namaApproveBy2 = $data->nama;
    }


    public function render()
    {
        return view('livewire.requesterwr');
    }
}
