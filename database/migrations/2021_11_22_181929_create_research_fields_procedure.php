<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchFieldsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_research_fields_by_form_id` (form_id INT)
        BEGIN
        SELECT research_fields.*, field_types.name as field_type_name FROM research_fields 
                    INNER JOIN field_types on field_types.id = research_fields.field_type_id
                    WHERE research_fields.research_form_id = form_id
                    AND research_fields.is_active = 1 ORDER BY `order`;
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
        Schema::dropIfExists('research_fields_procedure');
    }
}
