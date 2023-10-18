<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

function fixTrimTime($data)
{
    return $data . ':00';
}

function trimTime($data)
{
    return Str::substr($data, 0, 5);
}

function late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift)
{
    $late = null;
    if (checkFirstInLate($first_in, $shift)) {
        return $late = 1;
    }
    if (checkFirstOutLate($first_out, $shift)) {
        return $late = 1;
    }
    if (checkSecondOutLate($second_out, $shift)) {
        return $late = 1;
    }
    if (checkOvertimeInLate($overtime_in, $shift)) {
        return $late = 1;
    }

    if (checkSecondInLate($second_in, $shift, $first_out)) {
        return $late = 1;
    }
}
function checkFirstInLate($data, $shift)
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Shift Pagi') {
            // Shift Pagi
            if (Carbon::parse($data)->betweenIncluded('05:30', '08:03')) {
                $late = 0;
            } else {
                $late = 1;
            }
        } else {
            if (Carbon::parse($data)->betweenIncluded('16:00', '20:03')) {
                $late = 0;
            } else {
                $late = 1;
            }
        }
    }
    return $late;
}

function checkSecondOutLate($data, $shift)
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Shift Pagi') {
            // Shift Pagi
            if (Carbon::parse($data)->betweenIncluded('12:00', '16:59')) {
                $late = 1;
            } else {
                $late = 0;
            }
        } else {
            if (Carbon::parse($data)->betweenIncluded('00:00', '04:59')) {
                $late = 1;
            } else {
                $late = 0;
            }
        }
    }
    return $late;
}

function checkOvertimeInLate($data, $shift)
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Shift Pagi') {
            // Shift Pagi
            if (Carbon::parse($data)->betweenIncluded('12:00', '18:33')) {
                $late = 0;
            } else {
                $late = 1;
            }
        }
    }
    return $late;
}

function checkFirstOutLate($data, $shift)
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Shift Pagi') {
            // Shift Pagi
            if (Carbon::parse($data)->betweenIncluded('08:00', '11:29')) {
                $late = 1;
            } else {
                $late = 0;
            }
        } else {
            if (Carbon::parse($data)->betweenIncluded('20:00', '23:59')) {
                $late = 1;
            } else {
                $late = 0;
            }
        }
    }
    return $late;
}

function checkSecondInLate($data, $shift, $firstOut)
{
    $late = null;
    $groupIstirahat;

    if ($data != null) {
        if ($shift = 'Shift Pagi') {
            if ($firstOut != null) {
                if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                    $groupIstirahat = 1;
                } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                    $groupIstirahat = 2;
                } else {
                    $groupIstirahat = 0;
                }

                // Shift Pagi
                if ($groupIstirahat == 1) {
                    if (Carbon::parse($data)->betweenIncluded('08:00', '12:33')) {
                        $late = null;
                    } else {
                        $late = 1;
                    }
                } elseif ($groupIstirahat == 2) {
                    if (Carbon::parse($data)->betweenIncluded('11:00', '13:03')) {
                        $late = null;
                    } else {
                        $late = 1;
                    }
                } else {
                    $late = null;
                }
            }
        } else {
            if (Carbon::parse($data)->betweenIncluded('00:00', '01:03')) {
                $late = null;
            } else {
                $late = 1;
            }
        }
    }

    return $late;
}

function noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out)
{
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null) {
        return null;
    }
    if (($first_in == null) & ($first_out != null) || ($first_in != null) & ($first_out == null)) {
        return 'No Scan';
    }
    if (($second_in == null) & ($second_out != null) || ($second_in != null) & ($second_out == null)) {
        return 'No Scan';
    }
    if (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null)) {
        return 'No Scan';
    }
}
function titleCase($data)
{
    // $data1 =  Str::of($data)->trim('/');
    return Str::of($data)
        ->trim('/')
        ->title();
}

function getLastIdKaryawan()
{
    return DB::table('karyawans')->max('id_karyawan');
}

function getNextIdKaryawan()
{
    return getLastIdKaryawan() + 1;
}
function format_tgl($tgl)
{
    if ($tgl) {
        return date('d-M-Y', strtotime($tgl));
    }
}

function format_jam($jam)
{
    if ($jam) {
        return Carbon::createFromFormat('H:i:s', $jam)->format('H:i');
    }
}

function is_saturday($tgl)
{
    // true
    if ($tgl) {
        if (Carbon::parse($tgl)->isSaturday()) {
            return true;
        } else {
            return false;
        }
    }
}

function sp_recal_presensi()
{
    if (Schema::hasTable('table_name')) {
        // Do something if exists
    }
}

// ===================================================================
// function lateCheck2($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out, $shift)
// {
//     $late = null;

//     if ($shift == 'Shift Pagi') {
//         if ($first_in > '08:04' && $first_in != '') {
//             $late = 1;
//         }
//         if ($first_out < '11:30' && $first_out != '') {
//             $late = 1;
//         }

//         if ($first_out >= '11:30' && $first_out < '12:00' && $first_out != '') {
//             if ($second_in > '12:31' && $second_in != '') {
//                 $late = 1;
//             }
//         }
//         if ($first_out >= '12:00' && $first_out < '12:30' && $first_out != '') {
//             if ($second_in > '13:01' && $second_in != '') {
//                 $late = 1;
//             }
//         }
//         if ($second_out < '17:00' && $second_out != '') {
//             $late = 1;
//         }

//         if ($overtime_in > '18:30' && $overtime_in != '') {
//             $late = 1;
//         }
//     } elseif ($shift == 'Shift Malam') {
//         if ($first_in > '20:04' && $first_in != '') {
//             $late = 1;
//         }
//         if ($first_out < '24:00' && $first_out > '20:04' && $first_out != '') {
//             $late = 1;
//         }
//         if ($second_in > '01:04' && $second_in != '') {
//             $late = 1;
//         }
//         if ($second_out < '05:00' && $second_out != '') {
//             $late = 1;
//         }
//     }

//     return $late;
// }

// function lateCheck($data)
// {
//     $late = null;
//     if ($data[0]->shift == 'Shift Pagi') {
//         if ($data[0]->first_in > '08:04' && $data[0]->first_in != '') {
//             $late = 1;
//         }
//         if ($data[0]->first_out < '11:30' && $data[0]->first_out != '') {
//             $late = 1;
//         }

//         if ($data[0]->first_out >= '11:30' && $data[0]->first_out < '12:00' && $data[0]->first_out != '') {
//             if ($data[0]->second_in > '12:31' && $data[0]->second_in != '') {
//                 $late = 1;
//             }
//         }
//         if ($data[0]->first_out >= '12:00' && $data[0]->first_out < '12:30' && $data[0]->first_out != '') {
//             if ($data[0]->second_in > '13:01' && $data[0]->second_in != '') {
//                 $late = 1;
//             }
//         }
//         if ($data[0]->second_out < '17:00' && $data[0]->second_out != '') {
//             $late = 1;
//         }

//         if ($data[0]->overtime_in > '18:30' && $data[0]->overtime_in != '') {
//             $late = 1;
//         }
//     } elseif ($data[0]->shift == 'Shift Malam') {
//         if ($data[0]->first_in > '20:04' && $data[0]->first_in != '') {
//             $late = 1;
//         }
//         if ($data[0]->first_out < '24:00' && $data[0]->first_out > '20:04' && $data[0]->first_out != '') {
//             $late = 1;
//         }
//         if ($data[0]->second_in > '01:04' && $data[0]->second_in != '') {
//             $late = 1;
//         }
//         if ($data[0]->second_out < '05:00' && $data[0]->second_out != '') {
//             $late = 1;
//         }
//     }

//     return $late;
// }
