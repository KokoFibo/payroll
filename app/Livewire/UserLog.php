<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class UserLog extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = Activity::orderBy('created_at', 'desc')->paginate(10);
        $log_activities = Activity::select('description')->distinct('description')->count();
        return view('livewire.user-log', compact(['data', 'log_activities']));
    }
}