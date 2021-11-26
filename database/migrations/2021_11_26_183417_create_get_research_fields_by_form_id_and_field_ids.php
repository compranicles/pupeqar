<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetResearchFieldsByFormIdAndFieldIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
            CREATE PROCEDURE `get_research_fields_by_form_id_and_field_ids` (form_id INT, field_id1 INT, field_id2 INT)
            BEGIN
            SELECT research_fields.*, field_types.name as field_type_name FROM research_fields 
                        INNER JOIN field_types on field_types.id = research_fields.field_type_id
                        WHERE (research_fields.id BETWEEN field_id1 AND field_id2) AND
                        research_fields.research_form_id = form_id
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
        Schema::dropIfExists('get_research_fields_by_form_id_and_field_ids');
    }
}
