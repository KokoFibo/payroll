<?php

namespace App\Http\Controllers;

use App\Http\Middleware\User;
use App\Models\Yfrekappresensi;
use Illuminate\Http\Request;

class ApiPresensiController extends Controller
{
    public function userPresensi($month, $year, $user_id)
    {
        $data = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
