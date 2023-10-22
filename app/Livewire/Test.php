<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class Test extends Component
{
    // public $saturday;
    use WithPagination;
    public $cx = 0;
    public $search;
    public $periode;
    public function render()
    {
        $this->periode = '2023-10-01';
        // checkSecondOutLate($second_out, $shift, $tgl)
        $datas = Jamkerjaid::where('date', $this->periode)
        // ->where('name', 'LIKE', '%' . trim($this->search) . '%')
        // ->orWhere('user_id', 'LIKE', '%' . trim($this->search) . '%')
        ->paginate(10);

        $this->cx++;
        return view('livewire.test', compact('datas'));
    }
}
