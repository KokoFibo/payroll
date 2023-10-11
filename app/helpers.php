<?php

use Carbon\Carbon;

function getLastIdKaryawan() {
    return DB::table('karyawans')->max('id_karyawan');
}

function getNextIdKaryawan() {
    return getLastIdKaryawan() + 1;
}
function format_tgl($tgl) {
    return date('d-M-Y', strtotime($tgl));
}

function is_saturday($tgl) {
     // true
    if (Carbon::parse($tgl)->isSaturday()) {
        return true;
    } else {
        return false;
    }
}

function sp_recal_presensi () {
    if (Schema::hasTable('table_name'))
{
    // Do something if exists
}





}
