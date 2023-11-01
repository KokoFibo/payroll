<?php

namespace App\Http\Controllers;

use App\Models\User;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;

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

        // $data = Yfrekappresensi::where('user_id', 5222 )->where('no_scan',null)->get();
        // return view( 'dashboardMobile', compact(['data']) );



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
            return view( 'dashboardMobile1' );

        }
    }
    }
