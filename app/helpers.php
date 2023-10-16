<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

function noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out) {
    if(($first_in == null & $first_out != null) || ($first_in != null & $first_out == null)){
        return 'No Scan';
    }
    if(($second_in == null & $second_out != null) || ($second_in != null & $second_out == null)){
        return 'No Scan';
    }
    if(($overtime_in == null & $overtime_out != null) || ($overtime_in != null & $overtime_out == null)){
        return 'No Scan';
    }
}
function titleCase($data) {
    // $data1 =  Str::of($data)->trim('/');
    return Str::of($data)->trim('/')->title();
}
function lateCheck2($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out, $shift) {
    $late =null;
    if($shift == 'Shift Pagi' ){
        if($first_in > '08:04' && $first_in != '') $late = 1;
        if($first_out < '11:30' && $first_out != '') $late = 1;

        if($first_out >= '11:30' && $first_out < '12:00' && $first_out != '' ){
            if($second_in > '12:31' && $second_in != '') $late = 1;
        }
        if($first_out >= '12:00' && $first_out < '12:30' && $first_out != '' ){
            if($second_in > '13:01' && $second_in != '') $late = 1;
        }
        if($second_out < '17:00' && $second_out != '') $late = 1;

        if($overtime_in > '18:30' && $overtime_in != '') $late = 1;

    } else if ($shift == 'Shift Malam') {
        if($first_in > '20:04' && $first_in != '') $late = 1;
        if($first_out < '24:00' && $first_out > '20:04' && $first_out != '') $late = 1;
        if($second_in > '01:04' && $second_in != '') $late = 1;
        if($second_out < '05:00' && $second_out != '') $late = 1;
        // if($overtime_in < '01:03' && $overtime_in != '') $late = 15;
    }

    return $late;

}

function lateCheck($data) {

    $late =null;
    if($data[0]->shift == 'Shift Pagi' ){
        if($data[0]->first_in > '08:04' && $data[0]->first_in != '') $late = 1;
        if($data[0]->first_out < '11:30' && $data[0]->first_out != '') $late = 1;

        if($data[0]->first_out >= '11:30' && $data[0]->first_out < '12:00' && $data[0]->first_out != '' ){
            if($data[0]->second_in > '12:31' && $data[0]->second_in != '') $late = 1;
        }
        if($data[0]->first_out >= '12:00' && $data[0]->first_out < '12:30' && $data[0]->first_out != '' ){
            if($data[0]->second_in > '13:01' && $data[0]->second_in != '') $late = 1;
        }
        if($data[0]->second_out < '17:00' && $data[0]->second_out != '') $late = 1;

        if($data[0]->overtime_in > '18:30' && $data[0]->overtime_in != '') $late = 1;

    } else if ($data[0]->shift == 'Shift Malam') {
        if($data[0]->first_in > '20:04' && $data[0]->first_in != '') $late = 1;
        if($data[0]->first_out < '24:00' && $data[0]->first_out > '20:04' && $data[0]->first_out != '') $late = 1;
        if($data[0]->second_in > '01:04' && $data[0]->second_in != '') $late = 1;
        if($data[0]->second_out < '05:00' && $data[0]->second_out != '') $late = 1;
        // if($data[0]->overtime_in < '01:03' && $data[0]->overtime_in != '') $late = 15;
    }

    return $late;
}
function getLastIdKaryawan() {
    return DB::table('karyawans')->max('id_karyawan');
}

function getNextIdKaryawan() {
    return getLastIdKaryawan() + 1;
}
function format_tgl($tgl) {
    if ($tgl){

        return date('d-M-Y', strtotime($tgl));
    }
}

function format_jam($jam) {
    if($jam) {
        return Carbon::createFromFormat('H:i:s',$jam)->format('H:i');
    }
}

function is_saturday($tgl) {
     // true
     if ($tgl) {

        if (Carbon::parse($tgl)->isSaturday()) {
            return true;
        } else {
            return false;
        }
    }

}

function sp_recal_presensi () {
    if (Schema::hasTable('table_name'))
{
    // Do something if exists
}





}
