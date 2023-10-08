<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportPresensi;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PresensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = date('Y-m-d');
        $short = 'no_scan';
        $order = 'desc';

        if ($request->short) {
            $short = $request->short;
        }


        if ($request->order) {
            $order = $request->order;
        }

        if ($request->date) {
            $date = date('Y-m-d', strtotime($request->date));
        }
        $search = $request->search;

        $noScanCount = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->where('no_scan',1)
        ->count();

        $noScanCountPagi = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->where('shift','Shift pagi')
        ->where('no_scan',1)
        ->count();



        $lateCount = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->where('late',1)
        ->count();
        $totalDataPerHari = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->count();

        $lateCountPagi = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->where('shift','Shift pagi')
        ->where('late',1)
        ->count();
        $totalDataPerHari = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->count();

        $totalShiftPagi = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->where('shift','Shift pagi')
        ->count();

        $totalDataPerHari = DB::table('rekap_presensis')
        ->where('date',$request->date)
        ->count();


        $data = DB::table('rekap_presensis');
        if ($search != '' && $request->date == '') {
            $data = DB::table('rekap_presensis')->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')->orWhere('user_id', $search);
            })->orderBy($short, $order)->paginate(10);
        } else {
            $data = DB::table('rekap_presensis')->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')->orWhere('user_id', $search);
            })->where(function ($query2) use ($date) {
                $query2->where('date', 'like', '%' . $date . '%');
            })->orderBy($short, $order)->paginate(10);
        }
        return view('content.presensi.index', compact('data', 'date', 'search', 'short', 'order', 'lateCount', 'noScanCount','lateCountPagi', 'noScanCountPagi','totalDataPerHari', 'totalShiftPagi'));
    }



    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     // dd(date('H:i', strtotime(str_replace('+', '', '12:32 AM+'))));
    //     return view('content.presensi.import');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);


        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);

        // $properties = $spreadsheet->getProperties();

        // $author = $properties->getCreator();
        // $lastModifiedBy = $properties->getLastModifiedBy();
        // $createdDate = $properties->getCreated();
        // $modifiedDate = $properties->getModified();
        // $data = [
        //     'author' => date('h:i', strtotime($createdDate)),
        //     'lastModifiedBy' => date('h:i', strtotime($modifiedDate))
        // ];

        // dd($data);

        // if ($createdDate != $modifiedDate) {
        //     // dd("File has been modified");
        //     return back()->with('error', 'File has been modified.');
        // }


        $importedData = $spreadsheet->getActiveSheet();
        $row_limit    = $importedData->getHighestDataRow();
        // $column_limit = $importedData->getHighestDataColumn();
        // $row_range    = range(2, $row_limit);
        // $column_range = range('F', $column_limit);

        $tgl = explode('~', $importedData->getCell('A2')->getValue())[1];
        $user_id = "";
        $name = "";
        $department = "";
        // random check

        for ($i = 5; $i < $row_limit; $i++) {
            if ($importedData->getCell('A' . $i)->getValue() != "") {

                $user_id = $importedData->getCell('A' . $i)->getValue();
                $name = $importedData->getCell('B' . $i)->getValue();

                $check_data = DB::table('rekap_presensis')->where('user_id', $user_id)->where('date', $tgl)->get();
                if ($check_data->isNotEmpty()) {
                    return back()->with('error', 'The file has been uploaded.');
                }


                $department = $importedData->getCell('C' . $i)->getValue();
                $dept = Department::updateOrCreate(['name' => $department], ['name' => $department]);
                Employee::updateOrCreate(
                    ['user_id' => $user_id, 'name' => $name],
                    ['user_id' => $user_id, 'name' => $name, 'department_id' => $dept->id]
                );
            }

            if ($importedData->getCell('D' . $i)->getValue() != "") {

                $time = date('H:i', strtotime($importedData->getCell('D' . $i)->getValue()));
                if (strpos($importedData->getCell('D' . $i)->getValue(), '+') !== false) {
                    $str = str_replace('+', '', $importedData->getCell('D' . $i)->getValue());
                    $time = date('H:i', strtotime($str));
                }

                Presensi::create([
                    'user_id' => $user_id,
                    'name' => $name,
                    'department' => $department,
                    'date' => $tgl,
                    'time' => $time,
                    'day_number' => date('w', strtotime($tgl))

                ]);
            }

        }



        $tempTbl = DB::table('vrekappresensi_overtime')->where('date', $tgl)->get();
        foreach ($tempTbl as $row) {

            DB::table('temp_rekap_presensi')->insert([
                "user_id" => $row->user_id,
                "name" => $row->name,
                "department" => $row->department,
                "date" => $row->date,
                "jml_fp" => $row->jml_fp,
                "first_in" => $row->first_in,
                "first_out" => $row->first_out,
                "second_in" => $row->second_in,
                "second_out" => $row->second_out,
                "overtime_in" => $row->overtime_in,
                "overtime_out" => $row->overtime_out,
                "shift" => $row->shift
            ]);
        }



        DB::select("CALL spRecalPresensi()");
        if(is_saturday($tgl)) {
            $datas = DB::table('rekap_presensis')
            ->where('date', $tgl)
            ->where('shift', 'Shift pagi')
            ->where('first_in','>', '15:00')
            ->get();
            foreach($datas as $data){
                DB::table('rekap_presensis')->where('user_id', $data->user_id)
                        ->whereDate('date', '=', $data->date)
                        ->update(['shift' => 'Shift malam']);
            }
        }
        if (is_saturday($tgl)){
            $datas = DB::table('rekap_presensis')
            ->where('date', $tgl)
            ->where('shift', 'Shift malam')
            ->get();
            foreach($datas as $data){
                if($data->first_in != '' || $data->first_out != '' || $data->second_in != '' || $data->second_out != '' || $data->overtime_in != '' || $data->overtime_out != '' ){
                    $late = 0;
                    if($data->first_in != '' && $data->first_in > '17:03') {
                        $late = 1;
                    }
                    if($data->first_out != '' && $data->first_out < '21:00') {
                        $late = 1;
                    }
                    if($data->second_in != '' && $data->second_in > '22:03') {
                        $late = 1;
                    }
                    if($data->second_out != '' && $data->second_out < '00:00') {
                        $late = 1;
                    }
                    if($late == 0) {
                        DB::table('rekap_presensis')->where('user_id', $data->user_id)
                        ->whereDate('date', '=', $data->date)
                        ->update(['late' => null]);
                    }
                }
            }
        }

        return back()->with('success', 'Data absensi telah berhasil di import');
    }

    /**
     * Display the specified resource.
     */
    public function show(Presensi $presensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presensi $presensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        dd($request);
        $rekap_presensi = DB::table('rekap_presensis')->where('user_id', $user_id)->where('date', $request->date)->first();
        $rekap_presensi->first_in = $request->first_in;
        $rekap_presensi->first_out = $request->first_out;
        $rekap_presensi->second_in = $request->second_in;
        $rekap_presensi->second_out = $request->second_out;
        $rekap_presensi->overtime_in = $request->overtime_in;
        $rekap_presensi->overtime_out = $request->overtime_out;
        $rekap_presensi->save();

        dd($rekap_presensi);

        //
    }

    public function update_presensi(Request $request, $user_id)
    {

        DB::table('rekap_presensis')->where('user_id', $user_id)->where('date', $request->date)->update([
            'first_in' => $request->first_in,
            'first_out' => $request->first_out,
            'second_in' => $request->second_in,
            'second_out' => $request->second_out,
            'overtime_in' => $request->overtime_in,
            'overtime_out' => $request->overtime_out
        ]);
        $data = DB::table('rekap_presensis')->where('user_id', $user_id)->where('date', $request->date)->get();

        if(($data[0]->first_in=='' && $data[0]->first_out=='') || ($data[0]->first_in!='' && $data[0]->first_out!='')){
            $ok1 = 1; //data masuk dan keluar lengkap
        } else {
            $ok1 = 0; //data masuk dan keluar TIDAK LENGKAP
        }

        if(($data[0]->second_in=='' && $data[0]->second_out=='') || ($data[0]->second_in!='' && $data[0]->second_out!='')){
            $ok2 = 1; //data masuk dan keluar lengkap
        } else {
            $ok2 = 0; //data masuk dan keluar TIDAK LENGKAP
        }
        if(($data[0]->overtime_in=='' && $data[0]->overtime_out=='') || ($data[0]->overtime_in!='' && $data[0]->overtime_out!='')){
            $ok3 = 1; //data masuk dan keluar lengkap
        } else {
            $ok3 = 0; //data masuk dan keluar TIDAK LENGKAP
        }
        if($ok1 == 1 && $ok2 == 1 && $ok3 ) {
            DB::table('rekap_presensis')->where('user_id', $user_id)->where('date', $request->date)->update([
                'no_scan' => '',
            ]);
        }


        return back()->with('success', 'Presensi updated.');
    }

    public function delete_presensi($user_id, $date)
    {
        DB::table('rekap_presensis')->where('user_id', $user_id)->where('date', $date)->delete();
        Presensi::where('user_id', $user_id)->where('date', $date)->delete();
        return back()->with('success', 'Presensi deleted.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presensi $presensi)
    {
        //
    }
}
