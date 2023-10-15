<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

$agent = new Agent();

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $agent = new Agent();
        $device = $agent->isMobile();
        if($device) {

            return view('dashboardMobile', compact(['device']));
        } else {

            return view('dashboard', compact(['device']));
        }
    }
}
