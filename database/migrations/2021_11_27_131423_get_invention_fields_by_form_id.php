<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GetInventionFieldsByFormId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_invention_fields_by_form_id` (form_id INT)
        BEGIN
        SELECT invention_fields.*, field_types.name as field_type_name FROM invention_fields 
                    INNER JOIN field_types on field_types.id = invention_fields.field_type_id
                    WHERE invention_fields.invention_form_id = form_id
                    AND invention_fields.is_active = 1 ORDER BY `order`;
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
