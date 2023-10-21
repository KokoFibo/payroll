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
                $email = $importedData->getCell('C' . $i)->getValue();
                $hp = $importedData->getCell('D' . $i)->getValue();
                $telepon = $importedData->getCell('E' . $i)->getValue();
                $tempat_lahir = $importedData->getCell('F' . $i)->getValue();
                $tanggal_lahir = $importedData->getCell('G' . $i)->getValue();
                $gender = $importedData->getCell('H' . $i)->getValue();
                $status_pernikahan = $importedData->getCell('I' . $i)->getValue();
                $golongan_darah = $importedData->getCell('J' . $i)->getValue();
                $agama = $importedData->getCell('K' . $i)->getValue();
                $jenis_identitas = $importedData->getCell('L' . $i)->getValue();
                $no_identitas = $importedData->getCell('M' . $i)->getValue();
                $alamat_identitas = $importedData->getCell('N' . $i)->getValue();
                $alamat_tinggal = $importedData->getCell('O' . $i)->getValue();
                $status_karyawan = $importedData->getCell('P' . $i)->getValue();
                $tanggal_bergabung = $importedData->getCell('Q' . $i)->getValue();
                $branch = $importedData->getCell('R' . $i)->getValue();
                $departemen = $importedData->getCell('S' . $i)->getValue();
                $jabatan = $importedData->getCell('T' . $i)->getValue();
                $level_jabatan = $importedData->getCell('U' . $i)->getValue();
                $gaji_pokok = $importedData->getCell('V' . $i)->getValue();
                $metode_penggajian = $importedData->getCell('AA' . $i)->getValue();
                $uang_makan = $importedData->getCell('AB' . $i)->getValue();
                $bonus = $importedData->getCell('AC' . $i)->getValue();
                $tunjangan_jabatan = $importedData->getCell('AD' . $i)->getValue();
                $tunjangan_bahasa = $importedData->getCell('AE' . $i)->getValue();
                $tunjangan_skill = $importedData->getCell('AF' . $i)->getValue();
                $tunjangan_lembur_sabtu = $importedData->getCell('AG' . $i)->getValue();
                $tunjangan_lama_kerja = $importedData->getCell('AH' . $i)->getValue();
                $iuran_air = $importedData->getCell('AI' . $i)->getValue();
                $potongan_seragam = $importedData->getCell('AJ' . $i)->getValue();
                $denda = $importedData->getCell('AK' . $i)->getValue();
                $potongan_pph21 = $importedData->getCell('AL' . $i)->getValue();
                $potongan_bpjs = $importedData->getCell('AM' . $i)->getValue();

                $data = Karyawan::where('id_karyawan',$id_karyawan)->get();
                if($data->isEmpty()) {

                    $total_data++;

                Karyawan::create([
                'id_karyawan' => $id_karyawan,
                'nama' => titleCase($nama),
                'email' => $email,
                'hp' => $hp,
                'telepon' => $telepon,
                'tempat_lahir' => titleCase($tempat_lahir),
                'tanggal_lahir' => $tanggal_lahir,
                'gender' => $gender,
                'status_pernikahan' => titleCase($status_pernikahan),
                'golongan_darah' => $golongan_darah,
                'agama' => titleCase($agama),
                'jenis_identitas' => $jenis_identitas,
                'no_identitas' => $no_identitas,
                'alamat_identitas' => titleCase($alamat_identitas),
                'alamat_tinggal' => titleCase($alamat_tinggal),
                'status_karyawan' => $status_karyawan,
                'tanggal_bergabung' => $tanggal_bergabung,
                'branch' => $branch,
                'departemen' => $departemen,
                'jabatan' => $jabatan,
                'level_jabatan' => $level_jabatan,
                'gaji_pokok' => $gaji_pokok,
                'metode_penggajian' => $metode_penggajian,
                'uang_makan' => $uang_makan,
                'bonus' => $bonus,
                'tunjangan_jabatan' => $tunjangan_jabatan,
                'tunjangan_bahasa' => $tunjangan_bahasa,
                'tunjangan_skill' => $tunjangan_skill,
                'tunjangan_lembur_sabtu' => $tunjangan_lembur_sabtu,
                'tunjangan_lama_kerja' => $tunjangan_lama_kerja,
                'iuran_air' => $iuran_air,
                'potongan_seragam' => $potongan_seragam,
                'denda' => $denda,
                'potongan_pph21' => $potongan_pph21,
                'potongan_bpjs' => $potongan_bpjs

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
