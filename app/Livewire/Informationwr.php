<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Information;

class Informationwr extends Component
{
    public $title, $description;
    public $date, $id;
    public $is_update = false;

    public function mount () {
        $this->date =  date( 'd M Y', strtotime( now()->toDateString() ) );
    }

    public function update ($id) {
        $this->id = $id;
        $data = Information::find($id);
        // $this->date = $data->date ;
        $this->date =  date( 'd M Y', strtotime( $data->date ) );

        $this->title = $data->title ;
        $this->description = $data->description ;
        $this->is_update = true;
    }

    public function save () {
        $data = Information::find($this->id);
        $this->date = date( 'Y-m-d', strtotime( $this->date ) );

        $data->date = $this->date;
        $data->title = $this->title;
        $data->description = $this->description;
        $data->save();
        $this->is_update = false;
        $this->date =  date( 'd M Y', strtotime( now()->toDateString() ) );

        $this->reset();
        $this->dispatch( 'success', message: 'Informasi berhasil di Update' );

    }

    public function delete ($id) {
        $data = Information::find($id);
        $data->delete();
        $this->reset();
        $this->dispatch( 'success', message: 'Informasi berhasil di delete' );
    }

    public function add () {

        $this->validate([
            'title' => 'required|max:30',
            'description' => 'required',
        ]);
        $this->date = date( 'Y-m-d', strtotime( $this->date ) );

        Information::create([
            'title' => $this->title,
            'description' => $this->description,
            'date' =>  $this->date,
        ]);
        //     dd($this->title, $this->description );
        // $data = new Information;
        // $data->title = $this->title;
        // $data->description = $this->description;
        // $data->save();

        $this->date =  date( 'd M Y', strtotime( now()->toDateString() ) );

        $this->reset();

        $this->dispatch( 'success', message: 'Informasi berhasil di tambah' );




    }
    public function render()
    {
        $data = Information::orderBy('date', 'desc')->get();
        return view('livewire.informationwr', compact('data'));
    }
}