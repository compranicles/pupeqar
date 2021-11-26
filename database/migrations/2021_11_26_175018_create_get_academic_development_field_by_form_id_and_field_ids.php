<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetAcademicDevelopmentFieldByFormIdAndFieldIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_academic_development_fields_by_form_id_and_field_ids` (form_id INT, field_id1 INT, field_id2 INT)
        BEGIN
        SELECT academic_development_fields.*, field_types.name as field_type_name FROM academic_development_fields 
                    INNER JOIN field_types on field_types.id = academic_development_fields.field_type_id
                    WHERE (academic_development_fields.id BETWEEN field_id1 AND field_id2) AND
                    academic_development_fields.academic_development_form_id = form_id
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
        Schema::dropIfExists('get_academic_development_field_by_form_id_and_field_ids');
    }
}
