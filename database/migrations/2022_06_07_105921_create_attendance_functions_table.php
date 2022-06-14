<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_functions', function (Blueprint $table) {
            $table->id();
            $table->text('activity_description');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('status');
            $table->text('proof_of_attendance');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('college_id')->nullable();
            $table->foreignId('department_id')->nullable();
            $table->string('report_quarter')->nullable();
            $table->string('report_year')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_functions');
    }
}
