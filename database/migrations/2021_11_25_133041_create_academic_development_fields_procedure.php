<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicDevelopmentFieldsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_academic_development_fields_by_form_id` (form_id INT)
        BEGIN
        SELECT academic_development_fields.*, field_types.name as field_type_name FROM academic_development_fields 
                    INNER JOIN field_types on field_types.id = academic_development_fields.field_type_id
                    WHERE academic_development_fields.academic_development_form_id = form_id
                    AND academic_development_fields.is_active = 1 ORDER BY `order`;
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
        Schema::dropIfExists('academic_development_fields_procedure');
    }
}
