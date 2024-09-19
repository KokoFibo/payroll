<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function store($id)
    {
        $karyawan = Karyawan::where('id_karyawan', $id)->first();
        $user = User::where('id_karyawan', $id)->first();
        if ($karyawan || $user) {
            $newKaryawan = $karyawan->replicate();
            $newUser = $user->replicate();
            $newKaryawan->save();
            $newUser->save();
            return response()->json($newKaryawan, 200);
        } else {
            return response()->json([
                'message' => 'User or Karyawan not found'
            ], 404);
        }
    }

    public function index()
    {
        return Karyawan::where('id_karyawan', '2')->get();
    }

    // Dibawah ini hanya contoh saja
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

    public function move_data($id)
    {
        // Find the user by ID
        $user = User::where('username', $id)->first();
        $karyawan = Karyawan::where('id_karyawan', $id)->first();

        // Check if the user exists
        if (!$user || !$karyawan) {
            return response()->json([
                'message' => 'User or Karyawan not found'
            ], 404);
        }

        // Return user data
        return response()->json($user, 200);
    }
}
