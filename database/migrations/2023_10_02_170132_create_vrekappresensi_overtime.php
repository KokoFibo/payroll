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
        CREATE VIEW vrekappresensi_overtime AS
        select
    `a`.`user_id` as `user_id`,
    `a`.`name` as `name`,
    `a`.`department` as `department`,
    `a`.`date` as `date`,
    `a`.`jml_fp` as `jml_fp`,
    `a`.`first_in` as `first_in`,
    `a`.`first_out` as `first_out`,
    `a`.`second_in` as `second_in`,
    `a`.`second_out` as `second_out`,
    `a`.`overtime_in` as `overtime_in`,
    `a`.`overtime_out` as `overtime_out`,
    case
        when `a`.`first_in` between '06:30' and '11:00'
        or `a`.`second_in` between '11:30' and '13:00' then 'Shift pagi'
        when `a`.`first_in` > '16:00' then 'Shift malam'
    end as `shift`
from
    (
    select
        `rk`.`user_id` as `user_id`,
        `rk`.`name` as `name`,
        `rk`.`department` as `department`,
        `rk`.`date` as `date`,
        `rk`.`jml_fp` as `jml_fp`,
        case
            when `rk`.`first_out` > '17:00'
            and `rk`.`first_in` > `rk`.`first_out` then null
            else `rk`.`first_in`
        end as `first_in`,
        `rk`.`first_out` as `first_out`,
        `rk`.`second_in` as `second_in`,
        `rk`.`second_out` as `second_out`,
        (
        select
            min(`payroll`.`presensis`.`time`)
        from
            `payroll`.`presensis`
        where
            `payroll`.`presensis`.`user_id` = `rk`.`user_id`
            and `payroll`.`presensis`.`date` = `rk`.`date`
            and `payroll`.`presensis`.`time` > ifnull(`rk`.`second_out`, '17:00')
            and `payroll`.`presensis`.`time` <> `rk`.`first_in`
            and `payroll`.`presensis`.`time` < '20:00') as `overtime_in`,
        (
        select
            max(`payroll`.`presensis`.`time`)
        from
            `payroll`.`presensis`
        where
            `payroll`.`presensis`.`user_id` = `rk`.`user_id`
            and `payroll`.`presensis`.`date` = `rk`.`date`
            and `payroll`.`presensis`.`time` > ifnull(`rk`.`second_out`, '19:00')
                and `payroll`.`presensis`.`time` > '19:00') as `overtime_out`
    from
        `payroll`.`vrekappresensi` `rk`) `a`;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vrekappresensi_overtime');
    }
};
