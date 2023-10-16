<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Presensi;
use App\Models\Department;
use App\Models\Yfpresensi;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class YfpresensiController extends Controller
{
    public function deletepresensi()
    {
        Yfpresensi::query()->truncate();
        Yfrekappresensi::query()->truncate();
        Presensi::query()->truncate();
        Employee::query()->truncate();
        DB::table('temp_rekap_presensi')->truncate();
        return back()->with('success', 'Data Presensi telah berhasil di delete');
    }

    public function deletepresensirekap()
    {
        DB::table('rekap_presensis')->truncate();
        return back()->with('success', 'Data Presensi telah berhasil di delete');
    }
    public function index()
    {
        return view('yfpresensi.index');
    }
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
        $row_limit = $importedData->getHighestDataRow();
        // $column_limit = $importedData->getHighestDataColumn();
        // $row_range    = range(2, $row_limit);
        // $column_range = range('F', $column_limit);

        $tgl = explode('~', $importedData->getCell('A2')->getValue())[1];
        $user_id = '';
        $name = '';
        $department = '';
        $late = null;
        $no_scan = null;
        // random check

        for ($i = 5; $i <= $row_limit; $i++) {
            if ($importedData->getCell('A' . $i)->getValue() != '') {
                $user_id = $importedData->getCell('A' . $i)->getValue();
                $name = $importedData->getCell('B' . $i)->getValue();

                $check_data = DB::table('yfrekappresensis')
                    ->where('user_id', $user_id)
                    ->where('date', $tgl)
                    ->get();
                if ($check_data->isNotEmpty()) {
                    return back()->with('error', 'The file has been uploaded.');
                }

                $department = $importedData->getCell('C' . $i)->getValue();
                $dept = Department::updateOrCreate(['name' => $department], ['name' => $department]);
                Employee::updateOrCreate(['user_id' => $user_id, 'name' => $name], ['user_id' => $user_id, 'name' => $name, 'department_id' => $dept->id]);
            }

            if ($importedData->getCell('D' . $i)->getValue() != '') {
                $time = date('H:i', strtotime($importedData->getCell('D' . $i)->getValue()));
                if (strpos($importedData->getCell('D' . $i)->getValue(), '+') !== false) {
                    $str = str_replace('+', '', $importedData->getCell('D' . $i)->getValue());
                    $time = date('H:i', strtotime($str));
                }

                Yfpresensi::create([
                    'user_id' => $user_id,
                    'name' => $name,
                    'department' => $department,
                    'date' => $tgl,
                    'time' => $time,
                    'day_number' => date('w', strtotime($tgl)),
                ]);
            }
        }
        // mulai rekap data dari tabel Yfpresensi

        $jumlahKaryawanHadir = DB::table('yfpresensis')
            ->distinct('user_id')
            ->count('user_id');
        $karyawanHadir = DB::table('yfpresensis')
            ->select('user_id', 'name', 'date', 'department')
            ->distinct()
            ->get();
        // $tablePresensi =  DB::table('yfpresensis')->get();
        // dd($karyawanHadir);
        foreach ($karyawanHadir as $kh) {
            $user_id = $kh->user_id;
            $name = $kh->name;
            $department = $kh->department;
            $tgl = '2023-10-12';
            $jml_fp = 1;
            $first_in = null;
            $first_out = null;
            $second_in = null;
            $second_out = null;
            $overtime_in = null;
            $overtime_out = null;
            $late = null;
            $no_scan = null;
            $shift = '';
            $tablePresensi = DB::table('yfpresensis')
                ->where('user_id', $kh->user_id)
                ->get();
            if (Carbon::parse($tablePresensi[0]->time)->betweenIncluded('05:30', '15:00')) {
                $shift = 'Shift Pagi';
            } else {
                $shift = 'Shift Malam';
            }

            if ($shift == 'Shift Pagi') {
                // SHIFT PAGI
                $flag = 0;
                foreach ($tablePresensi as $tp) {
                    if (Carbon::parse($tp->time)->betweenIncluded('05:30', '10:00')) {
                        $first_in = $tp->time;
                    } elseif (Carbon::parse($tp->time)->betweenIncluded('10:01', '12:15')) {
                        if ($flag == 0) {
                            $first_out = $tp->time;
                            if (Carbon::parse($tp->time)->betweenIncluded('10:01', '11:59')) {
                                $flag = 1;
                            } else {
                                $flag = 2;
                            }
                        }
                        if ($flag == 1) {
                            $second_in = $tp->time;
                        }
                    } elseif (Carbon::parse($tp->time)->betweenIncluded('12:16', '15:00')) {
                        $second_in = $tp->time;
                    } elseif (Carbon::parse($tp->time)->betweenIncluded('15:01', '17:30')) {
                        $second_out = $tp->time;
                    } elseif (Carbon::parse($tp->time)->betweenIncluded('17:31', '19:15')) {
                        $overtime_in = $tp->time;
                    } else {
                        // } else (Carbon::parse($tp->time)->betweenIncluded('19:16', '23:00')) {
                        $overtime_out = $tp->time;
                    }
                }
            }
            if ($shift == 'Shift Malam') {
                // SHIFT MALAM
                foreach ($tablePresensi as $tp) {
                    if (Carbon::parse($tp->time)->betweenIncluded('16:00', '22:00')) {
                        $first_in = $tp->time;
                    } elseif (Carbon::parse($tp->time)->betweenIncluded('22:01', '23:59') || Carbon::parse($tp->time)->betweenIncluded('00:00', '00:15')) {
                        $first_out = $tp->time;
                    } elseif (Carbon::parse($tp->time)->betweenIncluded('00:16', '03:00')) {
                        $second_in = $tp->time;
                    } else {
                        // } else if (Carbon::parse($tp->time)->betweenIncluded('03:01', '08:30')) {
                        $second_out = $tp->time;
                    }
                }
            }
            if ($shift == 'Shift Pagi') {
                if ($second_out == null && $overtime_out == null && $overtime_in != null) {
                    $second_out = $overtime_in;
                    $overtime_in = null;
                }
            }

            $no_scan = noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out);
            $late = lateCheck2($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out, $shift);

            Yfrekappresensi::create([
                'user_id' => $user_id,
                'name' => $name,
                'department' => $department,
                'date' => $tgl,
                'jml_fp' => $jml_fp,
                'first_in' => $first_in,
                'first_out' => $first_out,
                'second_in' => $second_in,
                'second_out' => $second_out,
                'overtime_in' => $overtime_in,
                'overtime_out' => $overtime_out,
                'shift' => $shift,
                'late' => $late,
                'no_scan' => $no_scan,
            ]);
        }
        return back()->with('success', 'Data absensi telah berhasil di import, jumlah karyawan hadir = ' . $jumlahKaryawanHadir);
    }
}
