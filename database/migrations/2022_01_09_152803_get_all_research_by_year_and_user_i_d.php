<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GetAllResearchByYearAndUserID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_all_research_by_year_and_user_id` (year YEAR, user INT)
        BEGIN
        SELECT research.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(research.updated_at) as quarter FROM research
                    INNER JOIN dropdown_options ON dropdown_options.id = research.status
                    INNER JOIN colleges ON colleges.id = research.college_id
                    WHERE YEAR(research.updated_at) = year AND
                    research.user_id = user AND
                    research.is_active_member = 1
                    ORDER BY research.updated_at DESC;
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
