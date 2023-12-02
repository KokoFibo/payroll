<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KaryawanExcelController extends Controller
{
   public function index () {
    return view('reports.karyawanexcel');
   }

   public function createExcel () {
    dd('create excel');
   }
}
