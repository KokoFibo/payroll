<?php

use Carbon\Carbon;
use App\Models\Karyawan;
use Illuminate\Support\Str;
use App\Models\Yfrekappresensi;

function karyawan_allow_edit($id, $role) {
    $data = Karyawan::find($id);
    if ($role < 3 && $data->gaji_pokok > 4500000){
        return 0;
} else {
    return 1;
}
}

function checkNonRegisterUser() {
    $rekap = Yfrekappresensi::distinct('user_id')->get('user_id');
    $array = [] ;
            foreach ($rekap as $r) {
                $karyawan = Karyawan::where('id_karyawan' , $r->user_id)->first();
                if ($karyawan === null) {
            $array[] = [
                'Karyawan_id' => $r->user_id,
            ];
        }
    }
    return $array;

}

function lamaBekerja( $tgl ) {
    $date = Carbon::parse( $tgl );
    $now = Carbon::now();
    $diff = $date->diffIndays( $now );
    $tahun = floor( $diff /365 );
    if ( $diff<30 ) {
        return $diff .' Hari';

    }
    if ( $tahun < 1 ) {
        $month = floor( $diff /30 );
        return ( int )$month .' Bulan';
    }
    $month = floor( ( $diff % ( $tahun *365 ) )/30 );
    return ( int )$tahun .' Tahun '. ( int )$month .' Bulan';
}

function isDesktop() {
    if ( auth()->user()->device == 1 ) {
        return 1;
    } else {
        return 0;
    }
}

function namaDiAside( $nama ) {
    if ( $nama != null ) {
        $arrJam = explode( ' ', $nama );
        if ( count( $arrJam ) == 1 ) {
            return $arrJam[ 0 ];
        } else {
            return $arrJam[ 0 ].' '.$arrJam[ 1 ];
        }
    } else {
        return 'No Name';
    }

}

function generatePassword( $tgl ) {

    if ( $tgl != null ) {
        $arrJam = explode( '-', fixTanggal( $tgl ) );
        $year = substr( $arrJam[ 0 ], 2 );
        return $arrJam[ 2 ] . $arrJam[ 1 ] . $year;
    }
}

function fixTanggal( $tgl ) {
    if ( $tgl != null ) {
        $arrJam = explode( '-', $tgl );
        if ( ( int ) $arrJam[ 1 ] < 10 ) {
            $month = '0' . ( int ) $arrJam[ 1 ];
        } else {
            $month = $arrJam[ 1 ];
        }
        if ( ( int ) $arrJam[ 2 ] < 10 ) {
            $date = '0' . ( int ) $arrJam[ 2 ];
        } else {
            $date = $arrJam[ 2 ];
        }

        return $arrJam[ 0 ] . '-' . $month . '-' . $date;
    }
}

function monthYear( $tgl ) {
    $month = Carbon::parse( $tgl )->format( 'F' );
    $year = Carbon::parse( $tgl )->format( 'Y' );
    return $month . ' ' . $year;
}

function getBulan( $tgl ) {
    $arrJam = explode( '-', $tgl );
    return $arrJam[ 1 ];
}

function addZeroToMonth( $tgl ) {
    if ( $tgl != null ) {
        if ( $tgl < 10 ) {
            return '0' . $tgl;
        } else {
            return $tgl;
        }
    }
}

function getTahun( $tgl ) {
    $arrJam = explode( '-', $tgl );
    return $arrJam[ 0 ];
}

function buatTanggal( $tgl ) {
    $arrJam = explode( '-', $tgl );
    return $arrJam[ 0 ] . '-' . $arrJam[ 1 ] . '-01';
}

function pembulatanJamOvertimeIn( $jam ) {
    $arrJam = explode( ':', $jam );
    if ( ( int ) $arrJam[ 1 ] <= 33 ) {
        if ( ( int ) $arrJam[ 0 ] < 10 ) {
            return $menit = '0' . $arrJam[ 0 ] . ':30:00';
        } else {
            return $menit = $arrJam[ 0 ] . ':30:00';
        }
    } else {
        $tambahJam = ( int ) $arrJam[ 0 ] + 1;
        if ( $tambahJam < 10 ) {
            $strJam = '0' . strval( $tambahJam ) . ':';
        } else {
            $strJam = strval( $tambahJam ) . ':';
        }
        return $strJam . '00:00';
    }
}

function pembulatanJamOvertimeOut( $jam ) {
    $arrJam = explode( ':', $jam );
    if ( ( int ) $arrJam[ 1 ] >= 30 ) {
        if ( ( int ) $arrJam[ 0 ] < 10 ) {
            return $menit = '0' . ( int ) $arrJam[ 0 ] . ':30:00';
        } else {
            return $menit = $arrJam[ 0 ] . ':30:00';
        }
    } else {
        if ( ( int ) $arrJam[ 0 ] < 10 ) {
            return $menit = '0' . ( int ) $arrJam[ 0 ] . ':00:00';
        } else {
            return $menit = $arrJam[ 0 ] . ':00:00';
        }
    }
}

function hitungLembur( $overtime_in, $overtime_out ) {
    if ( $overtime_in != '' || $overtime_out != '' ) {
        $t1 = strtotime( pembulatanJamOvertimeIn( $overtime_in ) );
        $t2 = strtotime( pembulatanJamOvertimeOut( $overtime_out ) );

        $diff = gmdate( 'H:i:s', $t2 - $t1 );
        $diff = explode( ':', $diff );
        $jam = ( int ) $diff[ 0 ];
        $menit = ( int ) $diff[ 1 ];
        // if ( $menit<30 ) {
        //     $menit = 0;
        // } else {
        //     $menit = 30;
        // }
        $totalMenit = $jam * 60 + $menit;

        return $totalMenit;
    } else {
        return 0;
    }
}

function fixTrimTime( $data ) {
    return $data . ':00';
}

function trimTime( $data ) {
    return Str::substr( $data, 0, 5 );
}

function late_check_jam_kerja_only ($first_in, $first_out, $second_in, $second_out, $shift, $tgl) {
    $late_1 = 0;
    $late_2 = 0;
    $late_3 = 0;
    $late_4 = 0;
    $late1 = checkFirstInLate($first_in, $shift, $tgl);
    $late2 = checkFirstOutLate($first_out, $shift, $tgl);
    $late3 = checkSecondInLate($second_in, $shift, $first_out, $tgl);
    $late4 = checkSecondOutLate($second_out, $shift, $tgl);
    // $total_late_1 = $total_late_1 + $late1;
    // $total_late_2 = $total_late_2 + $late2;
    // $total_late_3 = $total_late_3 + $late3;
    // $total_late_4 = $total_late_4 + $late4;
    return  ($late1 + $late2 + $late3 + $late4);
}
function late_check_jam_lembur_only ($overtime_in, $shift, $date) {
    return checkOvertimeInLate($overtime_in, $shift, $date);

}

function late_check_detail( $first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl ) {
    // $late = null;
    // $late1 = null;
    // $late2 = null;
    // $late3 = null;
    // $late4 = null;
    $late5 = null;

    if ( checkFirstInLate( $first_in, $shift, $tgl ) ) {
        //  return $late = $late + 1;
        return $late = 1;
        // $late1 = 1;
    }
    if ( checkFirstOutLate( $first_out, $shift, $tgl ) ) {
        //  return $late = $late + 1;
        return $late = 1;
        // $late2 = 1;
    }
    if ( checkSecondOutLate( $second_out, $shift, $tgl ) ) {
        //  return $late = $late + 1;
        return $late = 1;
        // $late3 = 1;
    }

    // if ( checkOvertimeInLate( $overtime_in, $shift, $tgl ) ) {
    //     return $late = 1;
    // }

    if ( checkSecondInLate( $second_in, $shift, $first_out, $tgl ) ) {
        // return $late = $late + 1 ;
        return $late = 1;
        // $late5 = 1;
    }

    if ($second_in == null && $second_out==null){
        return $late = 1;
    }
    if ($first_in == null && $first_out==null){
        return $late = 1;
    }
    // $late = $late1 + $late2 + $late3+ $late4 + $late5 ;
    // return $late;
}

// ook

function hoursToMinutes( $jam ) {
    $arrJam = explode( ':', $jam );
    $minJam = ( int ) $arrJam[ 0 ] * 60;
    $min = ( int ) $arrJam[ 1 ];
    return $minJam + $min;
}

function checkFirstInLate( $check_in, $shift, $tgl ) {
    $perJam = 60;
    $late = null;
    if ( $check_in != null ) {
        if ( $shift == 'Pagi' ) {
            // Shift Pagi
            if ( Carbon::parse( $check_in )->betweenIncluded( '05:30', '08:03' ) ) {
                $late = null;
            } else {
                $t1 = strtotime( '08:03:00' );
                $t2 = strtotime( $check_in );

                $diff = gmdate( 'H:i:s', $t2 - $t1 );
                $late = ceil( hoursToMinutes( $diff ) / $perJam );
            }
        } else {
            if ( is_saturday( $tgl ) ) {
                if ( Carbon::parse( $check_in )->betweenIncluded( '14:00', '17:03' ) ) {
                    $late = null;
                } else {
                    $t1 = strtotime( '17:03:00' );
                    $t2 = strtotime( $check_in );

                    $diff = gmdate( 'H:i:s', $t2 - $t1 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                }
            } else {
                if ( Carbon::parse( $check_in )->betweenIncluded( '16:00', '20:03' ) ) {
                    $late = null;
                } else {
                    $t1 = strtotime( '20:03:00' );
                    $t2 = strtotime( $check_in );

                    $diff = gmdate( 'H:i:s', $t2 - $t1 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                }
            }
        }
    }
    return $late;
}

function checkSecondOutLate( $second_out, $shift, $tgl ) {
    $perJam = 60;
    $late = null;
    if ( $second_out != null ) {
        if ( $shift == 'Pagi' ) {
            // Shift Pagi
            if ( is_saturday( $tgl ) ) {
                if ( Carbon::parse( $second_out )->betweenIncluded( '12:00', '14:59' ) ) {
                    $t1 = strtotime( '15:00:00' );
                    $t2 = strtotime( $second_out );
//kkk
                    $diff = gmdate( 'H:i:s', $t1 - $t2 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );

                } else {
                    $late = null;
                }
            } else {
                if ( Carbon::parse( $second_out )->betweenIncluded( '12:00', '16:59' ) ) {
                    $t1 = strtotime( '17:00:00' );
                    $t2 = strtotime( $second_out );

                    $diff = gmdate( 'H:i:s', $t1 - $t2 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                } else {
                    $late = null;
                }
            }
        } else {
            if ( is_saturday( $tgl ) ) {
                if ( Carbon::parse( $second_out )->betweenIncluded( '19:00', '23:59' ) ) {
                    $t1 = strtotime( '00:00:00' );
                    $t2 = strtotime( $second_out );

                    $diff = gmdate( 'H:i:s', $t1 - $t2 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                } else {
                    $late = null;
                }
            } else {
                if ( Carbon::parse( $second_out )->betweenIncluded( '00:00', '04:59' ) ) {
                    $t1 = strtotime( '05:00:00' );
                    $t2 = strtotime( $second_out );

                    $diff = gmdate( 'H:i:s', $t1 - $t2 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                } else {
                    $late = null;
                }
            }
        }
    }
    return $late;
}

function checkOvertimeInLate( $overtime_in, $shift, $tgl ) {
    $persetengahJam = 30;
    $late = null;
    if ( $overtime_in != null ) {
        if ( $shift == 'Pagi' ) {
            // Shift Pagi
            if ( Carbon::parse( $overtime_in )->betweenIncluded( '12:00', '18:33' ) ) {
                $late = null;
            } else {
                $t1 = strtotime( '18:33:00' );
                $t2 = strtotime( $overtime_in );

                $diff = gmdate( 'H:i:s', $t2 - $t1 );
                $late = ceil( hoursToMinutes( $diff ) / $persetengahJam );
            }
        }
    }
    return $late;
}

function checkFirstOutLate( $first_out, $shift, $tgl ) {
    $perJam = 60;
    $late = null;
    if ( $first_out != null ) {
        if ( $shift == 'Pagi' ) {
            // Shift Pagi
            if ( Carbon::parse( $first_out )->betweenIncluded( '08:00', '11:29' ) ) {
                $t1 = strtotime( '11:30:00' );
                $t2 = strtotime( $first_out );

                $diff = gmdate( 'H:i:s', $t1 - $t2 );
                $late = ceil( hoursToMinutes( $diff ) / $perJam );
            } else {
                $late = null;
            }
        } else {
            if ( is_saturday( $tgl ) ) {
                if ( Carbon::parse( $first_out )->betweenIncluded( '17:01', '20:59' ) ) {
                    $t1 = strtotime( '21:00:00' );
                    $t2 = strtotime( $first_out );

                    $diff = gmdate( 'H:i:s', $t1 - $t2 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                } else {
                    $late = null;
                }
            } else {
                if ( Carbon::parse( $first_out )->betweenIncluded( '20:00', '23:59' ) ) {
                    $t1 = strtotime( '00:00:00' );
                    $t2 = strtotime( $first_out );

                    $diff = gmdate( 'H:i:s', $t1 - $t2 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                } else {
                    $late = null;
                }
            }
        }
    }
    return $late;
}

function checkSecondInLate( $second_in, $shift, $firstOut, $tgl ) {
    $perJam = 60;
    $late = null;
    $groupIstirahat;

    if ( $second_in != null ) {
        if ( $shift = 'Pagi' ) {
            if ( $firstOut != null ) {
                if ( Carbon::parse( $firstOut )->betweenIncluded( '08:00', '11:59' ) ) {
                    $groupIstirahat = 1;
                } elseif ( Carbon::parse( $firstOut )->betweenIncluded( '12:00', '12:59' ) ) {
                    $groupIstirahat = 2;
                } else {
                    $groupIstirahat = 0;
                }

                // Shift Pagi
                if ( $groupIstirahat == 1 ) {
                    if ( Carbon::parse( $second_in )->betweenIncluded( '08:00', '12:33' ) ) {
                        $late = null;
                    } else {
                        $t1 = strtotime( '12:33:00' );
                        $t2 = strtotime( $second_in );

                        $diff = gmdate( 'H:i:s', $t2 - $t1 );
                        $late = ceil( hoursToMinutes( $diff ) / $perJam );
                    }
                } elseif ( $groupIstirahat == 2 ) {
                    if ( Carbon::parse( $second_in )->betweenIncluded( '11:00', '13:03' ) ) {
                        $late = null;
                    } else {
                        $t1 = strtotime( '13:03:00' );
                        $t2 = strtotime( $second_in );

                        $diff = gmdate( 'H:i:s', $t2 - $t1 );
                        $late = ceil( hoursToMinutes( $diff ) / $perJam );
                    }
                } else {
                    $late = null;
                }
            }
        } else {
            if ( is_saturday( $tgl ) ) {
                if ( Carbon::parse( $second_in )->betweenIncluded( '20:01', '22:03' ) ) {
                    $late = null;
                } else {
                    $t1 = strtotime( '22:03:00' );
                    $t2 = strtotime( $second_in );

                    $diff = gmdate( 'H:i:s', $t2 - $t1 );
                    $late = ceil( hoursToMinutes( $diff ) / $perJam );
                }
            } else {
            }
            if ( Carbon::parse( $second_in )->betweenIncluded( '00:00', '01:03' ) ) {
                $late = null;
            } else {
                $t1 = strtotime( '01:03:00' );
                $t2 = strtotime( $second_in );

                $diff = gmdate( 'H:i:s', $t2 - $t1 );
                $late = ceil( hoursToMinutes( $diff ) / $perJam );
            }
        }
    }

    return $late;
}

function noScan( $first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out ) {
    if ( $first_in != null && $second_out != null && $first_out == null && $second_in == null && ( ( $overtime_in == null ) & ( $overtime_out != null ) || ( $overtime_in != null ) & ( $overtime_out == null ) ) ) {
        return 'No Scan';
    }
    if ( $first_in != null && $second_out != null && $first_out == null && $second_in == null ) {
        return null;
    }
    if ( ( $first_in == null ) & ( $first_out != null ) || ( $first_in != null ) & ( $first_out == null ) ) {
        return 'No Scan';
    }
    if ( ( $second_in == null ) & ( $second_out != null ) || ( $second_in != null ) & ( $second_out == null ) ) {
        return 'No Scan';
    }
    // if (( $second_in == null ) && ( $second_out == null )) {
    //     return 'No Scan';
    // }
    // if ( ( $first_in == null ) && ( $first_out == null ) ) {
    //     return 'No Scan';
    // }

    if ( ( $overtime_in == null ) & ( $overtime_out != null ) || ( $overtime_in != null ) & ( $overtime_out == null ) ) {
        return 'No Scan';
    }
}

function titleCase( $data ) {
    // $data1 =  Str::of( $data )->trim( '/' );
    return Str::of( $data )
    ->trim( '/' )
    ->title();
}

function getLastIdKaryawan() {
    return DB::table( 'karyawans' )->max( 'id_karyawan' );
}

function getNextIdKaryawan() {
    return getLastIdKaryawan() + 1;
}

function format_tgl( $tgl ) {
    if ( $tgl ) {
        return date( 'd-M-Y', strtotime( $tgl ) );
    }
}

function format_tgl_hari( $tgl ) {
    if ( $tgl ) {
        return date( 'D, d-M-Y', strtotime( $tgl ) );
    }
}

function format_jam( $jam ) {
    if ( $jam ) {
        return Carbon::createFromFormat( 'H:i:s', $jam )->format( 'H:i' );
    }
}

function is_saturday( $tgl ) {
    if ( $tgl ) {
        // if ( Carbon::parse( $tgl )->isSaturday() ) {
        //     return true;
        // } else {
        //     return false;
        // }
        return Carbon::parse( $tgl )->isSaturday();
    }
}

function sp_recal_presensi() {
    if ( Schema::hasTable( 'table_name' ) ) {
        // Do something if exists
    }
}
