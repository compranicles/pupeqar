<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GetAllInventionByYearAndUserID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_all_invention_by_year_and_user_id` (year YEAR, user INT)
        BEGIN
        SELECT inventions.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(inventions.updated_at) as quarter FROM inventions
                    INNER JOIN dropdown_options ON dropdown_options.id = inventions.status
                    INNER JOIN colleges ON colleges.id = inventions.college_id
                    WHERE YEAR(inventions.updated_at) = year AND
                    inventions.user_id = user
                    ORDER BY inventions.updated_at DESC;
        END;";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
