<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LoggingController extends Controller
{
    public function index()
    {

        $activity = Activity::all()->last();
        // return view('logging.index', [
        //     'activity' => $activity
        // ]);
        return $activity;
    }
}
