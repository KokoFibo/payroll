<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class DataLog extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {


        $activity = Activity::whereIn('event', ['updated', 'deleted'])->paginate(10);
        // $activity = Activity::all();

        // dd($activity);

        return view('livewire.data-log', [
            'activity' => $activity
        ]);
    }
}
