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
        CREATE VIEW vtotalcheck AS
        select
        `p`.`user_id` as `user_id`,
        `p`.`name` as `name`,
        `p`.`department` as `department`,
        `p`.`date` as `date`,
        count(`p`.`time`) as `jml_fp`,
        min(`p`.`id`) as `first_id`
    from
        `payroll`.`presensis` `p`
    group by
        `p`.`user_id`,
        `p`.`name`,
        `p`.`department`,
        `p`.`date`;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vtotalcheck');
    }
};
