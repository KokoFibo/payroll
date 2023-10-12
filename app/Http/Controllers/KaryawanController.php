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


    public function destroy ($id) {
        if($id != null) {
            $data = Karyawan::find($id);
            $data->delete();
            // $this->dispatchBrowserEvent('success', ['message' => 'Data Deleted']);
        }
        // return redirect(route('karyawan.index'));
        return back()->with('message','Data Sudah di Delete');
    }

    // public function show ($id) {
    //    $this->destroy($id);
    // }


}
