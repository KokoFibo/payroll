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
        DB::statement("
        CREATE VIEW vrekappresensi AS
        select
    `p`.`user_id` as `user_id`,
    `p`.`name` as `name`,
    `p`.`department` as `department`,
    `p`.`date` as `date`,
    `p`.`jml_fp` as `jml_fp`,
    min(`p2`.`time`) as `first_in`,
    min(`p3`.`time`) as `first_out`,
    max(`p4`.`time`) as `second_in`,
    min(`p5`.`time`) as `second_out`
from
    ((((`payroll`.`vtotalcheck` `p`
left join `payroll`.`presensis` `p2` on
    (`p2`.`id` = `p`.`first_id`
        and `p2`.`user_id` = `p`.`user_id`
        and `p2`.`date` = `p`.`date`
        and (`p2`.`time` < '10:00'
            or `p2`.`time` between '18:00' and '21:30'
            and `p2`.`day_number` <> 6
            or `p2`.`time` between '15:00' and '18:30'
            and `p2`.`day_number` = 6)))
left join `payroll`.`presensis` `p3` on
    (`p3`.`user_id` = `p`.`user_id`
        and `p3`.`date` = `p`.`date`
        and (`p3`.`time` between '11:00' and '13:00'
            or `p3`.`time` between '00:00' and '01:00'
            and `p3`.`day_number` <> 6
            or `p3`.`time` between '21:00' and '22:00'
            and `p3`.`day_number` = 6)))
left join `payroll`.`presensis` `p4` on
    (`p4`.`user_id` = `p`.`user_id`
        and `p4`.`date` = `p`.`date`
        and (`p4`.`time` between '11:00' and '13:00'
            or `p4`.`time` between '00:00' and '01:00'
            and `p4`.`day_number` <> 6
            or `p4`.`time` between '21:00' and '22:00'
            and `p4`.`day_number` = 6)))
left join `payroll`.`presensis` `p5` on
    (`p5`.`id` <> ifnull(`p2`.`id`, 0)
        and `p5`.`user_id` = `p`.`user_id`
        and `p5`.`date` = `p`.`date`
        and (`p5`.`time` between '15:00' and '19:00'
            or `p5`.`time` between '4:00' and '7:00'
            and `p5`.`day_number` <> 6
            or `p5`.`time` between '00:00' and '02:00'
            and `p5`.`day_number` = 6)))
group by
    `p`.`user_id`,
    `p`.`name`,
    `p`.`department`,
    `p`.`date`,
    `p`.`jml_fp`
order by
    `p`.`user_id`,
    `p`.`date`;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vrekappresensi');
    }
};
