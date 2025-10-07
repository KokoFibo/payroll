<?php

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Karyawan;
use App\Models\Bonuspotongan;
use App\Models\Jamkerjaid;
use App\Models\Liburnasional;
use App\Models\Lock;
use App\Models\Yfrekappresensi;

// $is_saturday = is_saturday($tgl);
// $is_sunday = is_sunday($tgl);
// $is_friday = is_friday($tgl);



function langsungLembur($second_out, $tgl, $shift, $jabatan, $placement_id)
{

    $is_saturday = is_saturday($tgl);



    $data = cek_hari_khusus($tgl);
    if ($data) {
        $tgl_khusus = $data->date;
    } else {
        $tgl_khusus = null;
    }

    // betulin
    if ($second_out != null) {
        $t2 = strtotime($second_out);
        if (!$is_saturday && $shift == 'Pagi' && $t2 < strtotime('04:00:00')) {
            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
            $diff = $diff + 7;
            return $diff;
        }
    }
    if (is_puasa($tgl)) {
        if ($second_out != null) {
            $lembur = 0;
            $t2 = strtotime($second_out);
            if ($jabatan == 17) {
                if ($shift == 'Pagi') {
                    if ($is_saturday) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:00:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:00:00') && $t2 > strtotime('11:30:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:29:00') && $t2 >= strtotime('20:00:00')) {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;

                                // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('19:30:00')) / 60;
                            } else {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }
                } else {
                    if ($is_saturday) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:00:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('04:30:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:00:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('07:30:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if ($is_saturday) {
                        if ($t2 < strtotime('15:00:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('14:30:00')) / 60;
                    } else {
                        if ($t2 < strtotime('17:00:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                    }
                } else {
                    //Shift Malam
                    if ($is_saturday) {
                        // if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                        //     return $lembur = 0;
                        // }
                        // if ( $t2 <= strtotime('23:29:00'))  || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:29:00')) {
                        //     return $lembur = 0;
                        // }
                        $t23_29 = strtotime('23:29:00');
                        $t23_30 = strtotime('23:30:00');
                        $t23_00 = Carbon::parse('23:00:00');
                        $t00_00 = strtotime('00:00:00');
                        $t05_00 = strtotime('05:00:00');

                        $t2 = strtotime($second_out);
                        $t20_00 = strtotime('20:00:00');
                        $t23_29 = strtotime('23:29:00');

                        // Jika $t2 berada di antara 22:00:00 dan 23:29:00, lembur = 0
                        if ($t2 >= $t20_00 && $t2 <= $t23_29) {
                            return $lembur = 0;
                        }


                        // if ($t2 >= strtotime('23:30:00') || ($t2 >= strtotime('00:00:00') && $t2 <= $t05_00)) {
                        //     $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->addDay()->diffInMinutes($t23_00) / 60;
                        // }
                        if ($t2 >= strtotime('00:00:00') && $t2 <= $t05_00) {
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->addDay()->diffInMinutes($t23_00) / 60;
                        }
                        if ($t2 >= strtotime('23:30:00')) {
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes($t23_00) / 60;
                        }

                        // Default jika tidak masuk kondisi apapun
                    } else {
                        // if ($t2 < strtotime('05:00:00') && $t2 <= strtotime('23:29:00')) {
                        //     return $lembur = 0;
                        // }
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }
            if (isset($diff) && $diff !== null) return $diff;
            // return $diff;
        } else {
            return $lembur = 0;
        }
    } else {
        if ($second_out != null) {

            $lembur = 0;

            $t2 = strtotime($second_out);
            // ini puasa kah
            if ($jabatan == 17) {
                if ($shift == 'Pagi') {
                    if ($is_saturday) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:30:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:30:00') && $t2 > strtotime('12:00:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:59:00') && $t2 >= strtotime('20:30:00')) {

                                // mk

                                // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('19:30:00')) / 60;
                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;
                            } else {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }
                } else {
                    if ($is_saturday) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if ($is_saturday) {
                        // if ($tgl == '2025-09-05') {
                        if ($tgl == $tgl_khusus) {
                            // rubah disini jika ada perubahan jam lembur
                            // ini perhitungan utk hari 08:00 - 15:30
                            // di hitung hari 6 jam, untuk lembur mulai 15:30
                            if ($t2 < strtotime('16:00:00')) {
                                return $lembur = 0;
                            }
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:30:00')) / 60;
                        } else {
                            if ($t2 < strtotime('15:30:00')) {
                                return $lembur = 0;
                            }
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('17:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                    }
                } else {
                    //Shift Malam
                    if ($is_saturday) {
                        if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
                    } else {
                        if ($t2 < strtotime('05:30:00') && $t2 <= strtotime('23:59:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }

            return $diff;
        } else {
            return $lembur = 0;
        }
    }
}
