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
            $table->string('name_of_student')->nullable();
            $table->string('title')->nullable();
            $table->string('classification')->nullable();
            $table->string('nature')->nullable();
            $table->foreignId('currency')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->foreignId('source_of_fund')->nullable();
            $table->string('organization')->nullable();
            $table->foreignId('level')->nullable();
            $table->string('venue')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('total_hours')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('student_trainings')->nullable();
    }
}
