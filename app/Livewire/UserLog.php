<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class UserLog extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $period = 'week';
    public $data_per_hari = [];

    public function mount()
    {
        $this->data_per_hari = []; // inisialisasi biar nggak null
        $this->loadData();
    }

    public function updatedPeriod()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $endDate = now();
        $startDate = match ($this->period) {
            '2weeks' => now()->subWeeks(2),
            'month' => now()->subMonth(),
            default => now()->subWeek(),
        };

        $rawData = DB::table('activity_log')
            ->selectRaw('DATE(created_at) as date, HOUR(created_at) as hour, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at), HOUR(created_at)'))
            ->orderBy('date')
            ->orderBy('hour')
            ->get();

        // Group per tanggal
        $this->data_per_hari = $rawData
            ->groupBy('date')
            ->map(function ($items, $date) {
                return [
                    'tanggal' => Carbon::parse($date)->translatedFormat('d M Y'),
                    'jam' => $items->map(fn($i) => [
                        'hour' => sprintf('%02d:00 - %02d:00', $i->hour, ($i->hour + 1) % 24),
                        'total' => $i->total
                    ])
                ];
            })
            ->values()
            ->toArray(); // pastikan array, bukan collection
    }



    public function render()
    {
        $data = Activity::whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->paginate(10);
        $total_logs = Activity::select('description')->distinct('description')->count();
        $today_logs = Activity::whereDate('created_at', Carbon::today())->select('description')->distinct('description')->count();
        $yesterday_log = Activity::whereDate('created_at', Carbon::yesterday())->select('description')->distinct('description')->count();
        $total_created_logs = Activity::select('description')->count();
        $data_activity = Activity::whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->get();
        $cx = 0;
        foreach ($data_activity as $d) {
            $contains = Str::contains($d->description, ['Admin', 'Senior Admin', 'Super Admin', 'BOD']);
            $contains2 = Str::contains($d->description, ['10000', '20000', '30000', '40000', '50000']);
            if ($contains && $contains2 == false) $cx++;
        }
        return view('livewire.user-log', compact(['data', 'total_logs', 'today_logs', 'yesterday_log', 'total_created_logs', 'cx']));
    }
}
