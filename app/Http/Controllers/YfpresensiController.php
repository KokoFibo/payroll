<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Department;
use App\Models\Jamkerjaid;
use App\Models\Yfpresensi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class YfpresensiController extends Controller
{
    public function generateUsers()
    {

        // generate user dafault
        // User::create([
        //     'name' => 'Anton',
        //     'email' => 'kokonacci@gmail.com',
        //     'username' => 40000,
        //     'role' => 4,
        //     'password' => Hash::make('Anton888'), // 123456789

        // ]);
        // User::create([
        //     'name' => 'Yifang User',
        //     'email' => 'user@yifang.com',
        //     'role' => 1,
        //     'username' => 10000,

        //     'password' => Hash::make('12345678'), // 123456789
        // ]);
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@yifang.com',
        //     'username' => 20000,

        //     'role' => 2,
        //     'password' => Hash::make('12345678'), // 123456789
        // ]);
        // User::create([
        //     'name' => 'Super Admin',
        //     'email' => 'superadmin@yifang.com',
        //     'username' => 30000,

        //     'role' => 3,
        //     'password' => Hash::make('12345678'), // 123456789
        // ]);

        // mulai generate user dari table karyawan
        $karyawan = Karyawan::all();
        foreach ($karyawan as $item) {
            //    $users = User::where('id_karyawan',$item->id_karyawan);
            User::create([
                'name' => titleCase($item->nama),
                'email' => trim($item->email),
                'username' => trim($item->id_karyawan),

                'role' => 1,
                'password' => Hash::make(generatePassword($item->tanggal_lahir)),
            ]);
        }


        dd('Done');
    }

    public function deleteJamKerja()
    {
        Jamkerjaid::query()->truncate();
        return back()->with('success', 'Data Jam Kerja telah berhasil di delete');
    }
    public function deleteNoScan()
    {
        Yfrekappresensi::where('no_scan', 'No Scan')->delete();
        return back()->with('success', 'Data No scan telah berhasil di delete');

    }
    public function deletepresensi()
    {
        Yfpresensi::query()->truncate();
        Yfrekappresensi::query()->truncate();
        // Presensi::query()->truncate();
        Employee::query()->truncate();
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

        $importedData = $spreadsheet->getActiveSheet();
        $row_limit = $importedData->getHighestDataRow();

        $tgl = explode('~', $importedData->getCell('A2')->getValue())[1];
        $user_id = '';
        $name = '';
        $department = '';
        $late = null;
        $no_scan = null;

        // check Tanggal apakah ada yang sama
        $tgl_sama = DB::table('yfrekappresensis')
            ->where('date', $tgl)
            ->get('user_id');

        for ($i = 5; $i <= $row_limit; $i++) {
            if ($importedData->getCell('A' . $i)->getValue() != '') {
                $user_id = $importedData->getCell('A' . $i)->getValue();
                $name = $importedData->getCell('B' . $i)->getValue();

                if ($tgl_sama->isNotEmpty()) {
                    foreach ($tgl_sama as $data) {
                        if ($user_id == $data->user_id) {
                            return back()->with('error', 'The file has been uploaded.');
                        }
                    }

                    // $check_data = DB::table('yfrekappresensis')
                    //     ->where('user_id', $user_id)
                    //     ->where('date', $tgl)
                    //     ->get();
                    // if ($check_data->isNotEmpty()) {
                    //     return back()->with('error', 'The file has been uploaded.');
                    // }
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

                // Yfpresensi::create([
                //     'user_id' => $user_id,
                //     'name' => $name,
                //     'department' => $department,
                //     'date' => $tgl,
                //     'time' => $time,
                //     'day_number' => date('w', strtotime($tgl)),
                // ]);

                //  pakai Chunk
                $Yfpresensidata[] = [
                    'user_id' => $user_id,
                    'name' => $name,
                    'department' => $department,
                    'date' => $tgl,
                    'time' => $time,
                    'day_number' => date('w', strtotime($tgl)),
                ];
            }
        }
        try {
            foreach (array_chunk($Yfpresensidata, 200) as $item) {
                Yfpresensi::insert($item);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Upload Format tanggal tidak sesuai');
        }
        // dd('ok');
        // mulai rekap data dari tabel Yfpresensi

        $jumlahKaryawanHadir = DB::table('yfpresensis')
            ->distinct('user_id')
            ->count('user_id');
        $karyawanHadir = DB::table('yfpresensis')
            ->select('user_id', 'name', 'date', 'department')
            ->distinct()
            ->get();

        foreach ($karyawanHadir as $kh) {
            $user_id = $kh->user_id;
            $name = $kh->name;
            $department = $kh->department;
            $tgl = $kh->date;
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

            $is_saturday = is_saturday($kh->date);

            if ($is_saturday) {
                // JIKA HARI SABTU kkk
                if (Carbon::parse($tablePresensi[0]->time)->betweenIncluded('05:30', '12:00')) {
                    $shift = 'Pagi';
                } else {
                    $shift = 'Malam';
                }

                if ($shift == 'Pagi') {
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
                        } elseif (Carbon::parse($tp->time)->betweenIncluded('12:16', '14:00')) {
                            $second_in = $tp->time;
                        } elseif (Carbon::parse($tp->time)->betweenIncluded('14:01', '17:30')) {
                            $second_out = $tp->time;
                        } else {
                            // } else (Carbon::parse($tp->time)->betweenIncluded('19:16', '23:00')) {
                            $overtime_out = $tp->time;
                        }
                    }
                }
                if ($shift == 'Malam') {
                    // SHIFT MALAM

                    foreach ($tablePresensi as $tp) {
                        switch ($tp->time) {
                            case Carbon::parse($tp->time)->betweenIncluded('15:00', '20:00'):
                                $first_in = $tp->time;
                                break;
                            case Carbon::parse($tp->time)->betweenIncluded('20:01', '21:30'):
                                if ($first_out == null) {
                                    $first_out = $tp->time;
                                } else {
                                    $second_in = $tp->time;
                                }
                                break;
                            case Carbon::parse($tp->time)->betweenIncluded('21:31', '23:59'):
                                $second_in = $tp->time;
                                break;

                            default:
                                $second_out = $tp->time;
                                break;
                        }
                    }
                }
                if ($shift == 'Pagi') {
                    if ($second_out == null && $overtime_out == null && $overtime_in != null) {
                        $second_out = $overtime_in;
                        $overtime_in = null;
                    }
                    if ($second_out == null && $overtime_in == null && $overtime_out != null) {
                        $second_out = $overtime_out;
                        $overtime_out = null;
                    }
                }
            } else {
                // JIKA BUKAN HARI SABTU
                if (Carbon::parse($tablePresensi[0]->time)->betweenIncluded('05:30', '15:00')) {
                    $shift = 'Pagi';
                } else {
                    $shift = 'Malam';
                }

                if ($shift == 'Pagi') {
                    // SHIFT PAGI
                    $flag = 0;
                    foreach ($tablePresensi as $tp) {
                        if (Carbon::parse($tp->time)->betweenIncluded('05:30', '10:00')) {
                            $first_in = $tp->time;
                        } elseif (Carbon::parse($tp->time)->betweenIncluded('10:01', '12:30')) {
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
                        } elseif (Carbon::parse($tp->time)->betweenIncluded('12:31', '15:00')) {
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
                if ($shift == 'Malam') {
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
                if ($shift == 'Pagi') {
                    if ($second_out == null && $overtime_out == null && $overtime_in != null) {
                        $second_out = $overtime_in;
                        $overtime_in = null;
                    }
                    if ($second_out == null && $overtime_in == null && $overtime_out != null) {
                        $second_out = $overtime_out;
                        $overtime_out = null;
                    }
                }
            }

            $no_scan = noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out);
            $late = late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl);
            // ook
            Yfrekappresensi::create([
                'user_id' => $user_id,
                'name' => $name,
                'department' => $department,
                'date' => $tgl,
                'first_in' => $first_in,
                'first_out' => $first_out,
                'second_in' => $second_in,
                'second_out' => $second_out,
                'overtime_in' => $overtime_in,
                'overtime_out' => $overtime_out,
                'shift' => $shift,
                'late' => $late,
                'no_scan' => $no_scan,
                'no_scan_history' => $no_scan,
                'late_history' => $late,
            ]);
        }

        Yfpresensi::query()->truncate();

        return back()->with('info', 'Berhasil Import : ' . $jumlahKaryawanHadir . ' data');
    }
}
