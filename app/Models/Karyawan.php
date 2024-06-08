<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Karyawan extends Model
{
    use HasFactory, HasUuids, LogsActivity;
    protected $guarded = [];
    protected static $recordEvents = ['updated', 'deleted'];

    public function getActivitylogOptions(): LogOptions
    {


        return LogOptions::defaults()
            ->logOnly(['gaji_pokok', 'gaji_overtime', 'bonus'])
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")
            ->logOnlyDirty()
            // ->causedBy(Auth::user()->id)
            ->dontSubmitEmptyLogs();
        // Chain fluent methods for configuration options
    }





    public function yfrekappresensi()
    {
        return $this->hasMany(Yfrekappresensi::class);
    }
}
