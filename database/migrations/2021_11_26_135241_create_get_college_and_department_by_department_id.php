<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetCollegeAndDepartmentByDepartmentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        CREATE PROCEDURE `get_college_and_department_by_department_id` (department_id INT)
        BEGIN
        SELECT colleges.id, colleges.name as college_name, departments.name as department_name FROM departments
                    INNER JOIN colleges on colleges.id = departments.college_id
                    WHERE departments.id = department_id LIMIT 1;
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
        Schema::dropIfExists('get_college_and_department_by_department_id');
    }
}
