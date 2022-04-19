<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionProgramFieldsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_extension_program_fields_by_form_id` (form_id INT)
        BEGIN
        SELECT extension_program_fields.*, field_types.name as field_type_name FROM extension_program_fields 
                    INNER JOIN field_types on field_types.id = extension_program_fields.field_type_id
                    WHERE extension_program_fields.extension_program_form_id = form_id
                    AND extension_program_fields.is_active = 1 ORDER BY `order`;
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
        DB::unprepared("DROP procedure IF EXISTS get_extension_program_fields_by_form_id");
    }
}
