<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KviewController extends Controller
{
    public function index()
    {
        $data = Karyawan::where('company', 'YIG')->get();
        return view('karyawan_excel_view', [
            'data' => $data
        ]);
    }
}
