<?php

namespace App\Http\Controllers;

use App\Models\User;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;

$agent = new Agent();

class HomeController extends Controller {
    /**
    * Create a new controller instance.
    *
    * @return void
    */

    public function __construct() {
        $this->middleware( 'auth' );
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */

    public function index() {
        return view( 'dashboardMobile' );

        $agent = new Agent();
        $desktop = $agent->isDesktop();
        $user = User::find( auth()->user()->id );
        if ( $desktop ) {
            $user->device = 1;
            $user->save();
            return view( 'dashboard' );
        } else {
            $user->device = 1;
            $user->save();
            if ( auth()->user()->role == 4 ) {
                $user->device = 1;
                $user->save();
                return view( 'dashboard' );
            }
            $user->device = 0;
            $user->save();
            return view( 'dashboardMobile' );

        }
    }
}
