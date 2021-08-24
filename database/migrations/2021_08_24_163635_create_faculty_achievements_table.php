<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('award_received');
            $table->foreignId('faculty_award_id');
            $table->string('award_body');
            $table->foreignId('level');
            $table->string('venue');
            $table->date('date_started');
            $table->date('date_ended');
            $table->text('document_description');
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
        Schema::dropIfExists('faculty_achievements');
    }
}
