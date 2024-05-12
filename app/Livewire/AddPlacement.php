<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Placement;
use Livewire\Attributes\Rule;

class AddPlacement extends Component
{
    #[Rule('required')]
    public $placement_name;

    public $is_add, $id;

    public function mount()
    {
        $this->is_add = true;
    }

    public function edit($id)
    {
        $this->is_add = false;
        $data = Placement::find($id);
        $this->id = $data->id;
        $this->placement_name =  $data->placement_name;
    }

    public function update()
    {
        $data = Placement::find($this->id);
        $data->placement_name = $this->placement_name;
        $data->save();
        $this->is_add = true;
        $this->placement_name =  '';

        session()->flash('message', 'Placement successfully updated.');
    }

    public function delete($id)
    {
        $data = Placement::find($id);
        $data->delete();
    }

    public function save()
    {
        // $validatedData = $this->validate([
        //     'placement_name' => 'required'
        // ]);
        $this->validate();
        Placement::create([
            'placement_name' => $this->placement_name
        ]);
        $this->placement_name =  '';

        session()->flash('message', 'Placement successfully created.');
    }
    public function render()
    {
        $data = Placement::all();
        return view('livewire.add-placement', [
            'data' => $data
        ]);
    }
}
