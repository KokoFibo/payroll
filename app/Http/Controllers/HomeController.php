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
        $desktop = $agent->isDesktop();

        if($desktop) {

            return view('dashboard');
        } else {
            if(Auth::user()->role == 4) {
                return view('dashboard');
            }
            return view('dashboardMobile');

        }
    }
}
