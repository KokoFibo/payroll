<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class KaryawanController extends Controller
{

    public function cari (Request $request) {
        // dd($request->cari);
        $datas = Karyawan::where('nama','LIKE','%'.$request->cari.'%')
        ->orWhere('branch','LIKE','%'.$request->cari.'%')
        ->orWhere('departemen','LIKE','%'.$request->cari.'%')
        ->orderBy('id_karyawan', 'desc')->paginate(10);
        // return back()->compact('datas');
        if($datas->isEmpty()){
            $datas = Karyawan::orderBy('id_karyawan', 'desc')->paginate(10);
            return view('karyawan.index', compact(['datas']));
        }
        return view('karyawan.index', compact(['datas']));
    }

    public function resetTable () {
        $datas = Karyawan::orderBy('id_karyawan', 'desc')->paginate(10);
            return view('karyawan.index', compact(['datas']));
    }

    public function erase () {
        Karyawan::query()->delete();

        return back()->with('success', 'Data Karaywan sudah di hapus semua');
    }
    public function import (Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');


        $spreadsheet = IOFactory::load($file);
        $importedData = $spreadsheet->getActiveSheet();
        $row_limit    = $importedData->getHighestDataRow();
        $start_row = 2;
        $total_data = 0;

        for($i=$start_row; $i<=$row_limit; $i++){
            if ($importedData->getCell('A' . $i)->getValue() != "") {

                $id_karyawan = $importedData->getCell('A' . $i)->getValue();
                $nama = $importedData->getCell('B' . $i)->getValue();
                $departemen = $importedData->getCell('C' . $i)->getValue();
                $jabatan = $importedData->getCell('D' . $i)->getValue();
                $gender = $importedData->getCell('E' . $i)->getValue();
                $tanggal_bergabung = $importedData->getCell('F' . $i)->getValue();

                $no_identitas = $importedData->getCell('G' . $i)->getValue();
                $tempat_lahir = $importedData->getCell('H' . $i)->getValue();
                $tanggal_lahir = $importedData->getCell('I' . $i)->getValue();
                $email = $importedData->getCell('J' . $i)->getValue();
                $hp = $importedData->getCell('K' . $i)->getValue();
                // $no rek = $importedData->getCell('L' . $i)->getValue();
                $alamat_identitas = $importedData->getCell('M' . $i)->getValue();

                $data = Karyawan::where('id_karyawan',$id_karyawan)->get();
                if($data->isEmpty()) {

                    $total_data++;

                Karyawan::create([
                    'id_karyawan' => $id_karyawan,
                    'nama' => titleCase($nama),
                    'departemen' => $departemen,
                    'jabatan' => $jabatan,
                    'gender' => $gender,
                    'tanggal_bergabung' => $tanggal_bergabung,
                    'no_identitas' => $no_identitas,
                    'tempat_lahir' => titleCase($tempat_lahir),
                    'tanggal_lahir' => $tanggal_lahir,
                    'email' => $email,
                    'hp' => $hp,
                    'alamat_identitas' => titleCase($alamat_identitas),

                ]);

            }

            }

        }
        return back()->with('success', 'File Excel Sudah Berhasil di tambahkan, total data di Excel = '.($row_limit - $start_row + 1).'data berhasil di imported: '. $total_data) ;


    }

    public function index () {
        $datas = Karyawan::orderBy('id_karyawan', 'desc')->paginate(10);
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
