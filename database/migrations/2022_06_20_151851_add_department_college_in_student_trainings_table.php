<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentCollegeInStudentTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_trainings', function (Blueprint $table) {
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade')->after('total_hours');
            $table->foreignId('department_id')->nullable()->onUpdate('cascade')->onDelete('cascade')->after('college_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_trainings', function (Blueprint $table) {
            //
        });
    }
}
