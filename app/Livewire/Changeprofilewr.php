<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Changeprofilewr extends Component
{
    #[Rule('min:3')]
    public $name, $language;
    public $old_password, $new_password, $confirm_password;

    public function mount () {
        $this->name=Auth::user()->name;
        $this->language=Auth::user()->language;

    }

    public function changeLanguage () {
        $user = User::find(Auth::user()->id);
        if($user->language != $this->language) {
            $user->language = $this->language;
            $user->save();
            $this->dispatch('success', message: 'Bahasa berhasil di rubah');

        }

    }

    public function changePassword () {
        $this->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|different:old_password',
            'confirm_password' => 'required|same:new_password',
        ]);
        $user = User::find(Auth::user()->id);
        if (Hash::check($this->old_password, Auth::user()->password)){
            $user->password = Hash::make($this->new_password);
            $user->save();
            $this->dispatch('success', message: 'Password berhasil di rubah');
        } else {
            $this->dispatch('error', message: 'Password gagal di rubah');
        }

    }

    public function SaveName () {
        if($this->name != "") {
        $data = User::find(Auth::user()->id);
        $data->name = $this->name;
        $data->save();
        $this->dispatch('success', message: 'Nama Profile telah berhasil di rubah');
        return redirect(route('changeprofile'));
    }
    }

    public function render()
    {
        return view('livewire.changeprofilewr');
    }
}
