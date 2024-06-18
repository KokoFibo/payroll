<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Requester;
use Livewire\Attributes\On;


class Requesterwr extends Component
{
    public $requestId, $approveBy1, $approveBy2;
    public $namaRequestId, $namaApproveBy1, $namaApproveBy2;
    public $is_update = false;
    public $is_update_id, $delete_id;

    public function edit($id)
    {
        $data = Requester::find($id);
        $this->requestId = $data->request_id;
        $this->approveBy1 = $data->approve_by_1;
        $this->approveBy2 = $data->approve_by_2;
        $this->is_update_id = $data->id;
        $this->namaRequestId = getName($this->requestId);
        $this->namaApproveBy1 = getName($this->approveBy1);
        $this->namaApproveBy2 = getName($this->approveBy2);
        $this->is_update = true;
    }

    public function update()
    {
        $this->validate([
            'requestId' => 'required|numeric',
            'approveBy1' => 'required|numeric',
            'approveBy2' => 'required|numeric'
        ]);

        if ($this->namaRequestId != '' && $this->namaApproveBy1 != '' && $this->namaApproveBy2 != '') {

            $data = Requester::find($this->is_update_id);
            $data->request_id = $this->requestId;
            $data->approve_by_1 = $this->approveBy1;
            $data->approve_by_2 = $this->approveBy2;
            $data->save();
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Requester Updated',
            );
            $this->is_update = false;
            $this->reset();
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Fail to update, user ID unavailable',
            );
        }
    }

    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $data = Requester::find($id);
        $text = getName($data->request_id) . '(' . $data->request_id . ') -> ' . getName($data->approve_by_1) . '(' . $data->approve_by_1 . ') -> ' . getName($data->approve_by_2) . '(' . $data->approve_by_2 . ')';
        $this->dispatch('show-delete-confirmation', text: $text);
    }

    #[On('delete-confirmed')]
    public function delete()
    {
        $data = Requester::find($this->delete_id)->delete();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Requester Deleted',
        );
    }

    public function updatedRequestId()
    {
        $data = Karyawan::where('id_karyawan', $this->requestId)->first();
        if ($data != null) {
            $this->namaRequestId = $data->nama;
        } else {
            $this->namaRequestId = '';
        }
    }
    public function updatedApproveBy1()
    {
        $data = Karyawan::where('id_karyawan', $this->approveBy1)->first();
        if ($data != null) {
            $this->namaApproveBy1 = $data->nama;
        } else {
            $this->namaApproveBy1 = '';
        }
    }
    public function updatedApproveBy2()
    {
        $data = Karyawan::where('id_karyawan', $this->approveBy2)->first();
        if ($data != null) {
            $this->namaApproveBy2 = $data->nama;
        } else {
            $this->namaApproveBy2 = '';
        }
    }

    public function save()
    {
        $this->validate([
            'requestId' => 'required|numeric',
            'approveBy1' => 'required|numeric',
            'approveBy2' => 'required|numeric'
        ]);

        if ($this->namaRequestId != '' && $this->namaApproveBy1 != '' && $this->namaApproveBy2 != '') {
            Requester::create([
                'request_id' => $this->requestId,
                'approve_by_1' => $this->approveBy1,
                'approve_by_2' => $this->approveBy2
            ]);

            $this->dispatch(
                'message',
                type: 'success',
                title: 'Requester Created',
            );
            $this->is_update = false;
            $this->reset();
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Fail to create, user ID unavailable',
            );
        }
    }


    public function render()
    {
        $data_requester = Requester::all();
        return view('livewire.requesterwr', [
            'data_requester' => $data_requester
        ]);
    }
}
