<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Company;
use Livewire\Attributes\Rule;

class AddCompany extends Component
{
    #[Rule('required')]
    public $company_name;

    public $is_add, $id;

    public function mount()
    {
        $this->is_add = true;
    }

    public function edit($id)
    {
        $this->is_add = false;
        $data = Company::find($id);
        $this->id = $data->id;
        $this->company_name =  $data->company_name;
    }

    public function update()
    {
        $data = Company::find($this->id);
        $data->company_name = $this->company_name;
        $data->save();
        $this->is_add = true;
        $this->company_name =  '';

        session()->flash('message', 'Company successfully updated.');
    }

    public function delete($id)
    {
        $data = Company::find($id);
        $data->delete();
    }

    public function save()
    {
        // $validatedData = $this->validate([
        //     'company_name' => 'required'
        // ]);
        $this->validate();
        Company::create([
            'company_name' => $this->company_name
        ]);
        $this->company_name =  '';

        session()->flash('message', 'Company successfully created.');
    }
    public function render()
    {
        $data = Company::all();
        return view('livewire.add-company', [
            'data' => $data
        ]);
    }
}
