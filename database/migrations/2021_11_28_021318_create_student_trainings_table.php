<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('name_of_student');
            $table->string('title');
            $table->string('classification');
            $table->string('nature');
            $table->foreignId('currency');
            $table->decimal('budget', 9, 2);
            $table->foreignId('source_of_fund');
            $table->string('organization');
            $table->foreignId('level');
            $table->string('venue');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_hours');
            $table->text('description');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('student_trainings');
    }
}
