<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return Karyawan::where('id_karyawan', '2')->get();
    }

    public function getDataUser($id)
    {
        // Find the user by ID
        $user = User::where('username', $id)->first();

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Return user data
        return response()->json($user, 200);
    }
}
