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

function late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl)
{
    // $late = null;
    // $late1 = null;
    // $late2 = null;
    // $late3 = null;
    // $late4 = null;
    $late5 = null;

    if (checkFirstInLate($first_in, $shift, $tgl)) {
        //  return $late = $late + 1;
         return $late = 1;
        // $late1 = 1;
    }
    if (checkFirstOutLate($first_out, $shift, $tgl)) {
        //  return $late = $late + 1;
         return $late = 1;
        // $late2 = 1;
    }
    if (checkSecondOutLate($second_out, $shift, $tgl)) {
        //  return $late = $late + 1;
         return $late = 1;
        // $late3 = 1;
    }
    if (checkOvertimeInLate($overtime_in, $shift, $tgl)) {
        //  return $late = $late + 1 ;
         return $late = 1;
        // $late4 = 1;
    }
    if (checkSecondInLate($second_in, $shift, $first_out, $tgl)) {
        // return $late = $late + 1 ;
        return $late = 1;
        // $late5 = 1;
    }
    // $late = $late1 + $late2 + $late3+ $late4 + $late5 ;
    // return $late;

}

function checkFirstInLate($data, $shift, $tgl)
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($data)->betweenIncluded('05:30', '08:03')) {
                $late = 0;
            } else {
                $late = 1;
            }
        } else {
            if(is_saturday($tgl)) {
                if (Carbon::parse($data)->betweenIncluded('14:00', '17:03')) {
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
    }
    return $late;
}

function checkSecondOutLate($data, $shift, $tgl )
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if(is_saturday($tgl)){
                if (Carbon::parse($data)->betweenIncluded('12:00', '14:59')) {
                    $late = 1;
                } else {
                    $late = 0;
                }

            } else {
            if (Carbon::parse($data)->betweenIncluded('12:00', '16:59')) {
                $late = 1;
            } else {
                $late = 0;
            }
        }
        } else {
            if(is_saturday($tgl)){
                if (Carbon::parse($data)->betweenIncluded('19:00', '23:56')) {
                    $late = 1;
                } else {
                    $late = 0;
                }

            }else {
                if (Carbon::parse($data)->betweenIncluded('00:00', '04:56')) {
                    $late = 1;
                } else {
                    $late = 0;
                }
            }

        }
    }
    return $late;
}

function checkOvertimeInLate($data, $shift, $tgl)
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Pagi') {
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

function checkFirstOutLate($data, $shift, $tgl )
{
    $late = null;
    if ($data != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($data)->betweenIncluded('08:00', '11:29')) {
                $late = 1;
            } else {
                $late = 0;
            }
        } else {
            if(is_saturday($tgl)) {
                if (Carbon::parse($data)->betweenIncluded('17:01', '20:59')) {
                    $late = 1;
                } else {
                    $late = 0;
                }

            }else {
                if (Carbon::parse($data)->betweenIncluded('20:00', '23:59')) {
                    $late = 1;
                } else {
                    $late = 0;
                }
            }

        }
    }
    return $late;
}

function checkSecondInLate($data, $shift, $firstOut, $tgl)
{
    $late = null;
    $groupIstirahat;

    if ($data != null) {
        if ($shift = 'Pagi') {
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
            if(is_saturday($tgl)) {
                if (Carbon::parse($data)->betweenIncluded('20:01', '22:03')) {
                    $late = null;
                } else {
                    $late = 1;
                }
            } else {}
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

function format_tgl_hari($tgl)
{
    if ($tgl) {
        return date('D, d-M-Y', strtotime($tgl));
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
    if ($tgl) {
        // if (Carbon::parse($tgl)->isSaturday()) {
        //     return true;
        // } else {
        //     return false;
        // }
        return Carbon::parse($tgl)->isSaturday();
    }
}

function sp_recal_presensi()
{
    if (Schema::hasTable('table_name')) {
        // Do something if exists
    }
}


