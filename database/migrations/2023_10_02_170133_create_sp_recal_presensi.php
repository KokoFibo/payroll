<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $procedure = "
        CREATE PROCEDURE spRecalPresensi()
        begin
        update temp_rekap_presensi rp set rp.shift ='Shift pagi' where (rp.first_in between '5:00' and '08:00' or rp.second_out<'20:00')  and rp.shift is null;
update temp_rekap_presensi rp set rp.shift ='Shift malam' where (rp.first_in> '16:00' or rp.first_out>'21:00' or rp.second_out between '05:00' and '07:00') and rp.shift is null;

update temp_rekap_presensi set overtime_in=null,overtime_out=null where shift='Shift malam' and (overtime_in is not null or overtime_out is not null);


        update temp_rekap_presensi rp set
        first_in =(select time from presensis where date=rp.date and user_id =rp.user_id order by id asc limit 1),
        second_out =(select time from presensis where date=rp.date and user_id =rp.user_id order by id desc limit 1)
        where rp.department in ('KOKI','SECURITY','SERVICE');

       update temp_rekap_presensi rp set
        rp.no_scan=1
        where (rp.first_in is null or rp.second_out is null);

update temp_rekap_presensi rp set
        rp.no_scan=1
        where (rp.first_out is not null or rp.second_in is not null) and (rp.first_out is null or rp.second_in is null);

update temp_rekap_presensi rp set
        rp.no_scan=1
        where (rp.overtime_in is not null or rp.overtime_out is not null) and (rp.overtime_out is null or rp.overtime_in is null);

update temp_rekap_presensi rp set
        rp.late=1
        where rp.first_in between '08:03' and '11:00' and rp.shift ='Shift pagi';

update temp_rekap_presensi rp set
        rp.late=1
        where rp.first_out<'12:00' and rp.second_in > '12:33'  and rp.shift ='Shift pagi';

update temp_rekap_presensi rp set
        rp.late=1
        where rp.first_out>='12:00' and rp.second_in > '13:00'  and rp.shift ='Shift pagi';


update temp_rekap_presensi rp set
        rp.late=1
        where rp.overtime_in > '18:33'  and rp.shift ='Shift pagi';


update temp_rekap_presensi rp set
        rp.late=1
        where rp.first_in between '20:33' and '23:00'  and rp.shift ='Shift malam';

update temp_rekap_presensi rp set
        rp.late=1
        where rp.second_in > '01:03' and rp.shift ='Shift malam';

insert into rekap_presensis
select * from temp_rekap_presensi;


truncate table temp_rekap_presensi;

truncate table presensis;
        END
        ";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $procedure = "DROP PROCEDURE IF EXISTS spRecalPresensi;";
        DB::unprepared($procedure);
    }
};
