<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index () {
        $datas = Karyawan::paginate(10);
        return view('karyawan.index', compact(['datas']));
    }
}
