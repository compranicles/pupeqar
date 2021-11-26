<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropdownNameProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_dropdown_name_by_id` (id INT)
        BEGIN
        SELECT dropdown_options.name FROM dropdown_options
                    WHERE dropdown_options.id = id
                    AND dropdown_options.is_active = 1 LIMIT 1;
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
        Schema::dropIfExists('dropdown_name_procedure');
    }
}
