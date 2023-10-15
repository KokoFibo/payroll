<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

function titleCase($data) {
    // $data1 =  Str::of($data)->trim('/');
    return Str::of($data)->trim('/')->title();
}

function lateCheck($data) {

    $late =null;
    if($data[0]->shift == 'Shift pagi' ){
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

    } else if ($data[0]->shift == 'Shift malam') {
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
