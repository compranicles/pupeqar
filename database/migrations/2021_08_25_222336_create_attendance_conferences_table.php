<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceConferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_conferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('title');
            $table->foreignId('develop_class_id');
            $table->foreignId('develop_nature_id');
            $table->decimal('budget', 9, 2)->nullable();
            $table->foreignId('funding_type_id');
            $table->string('organizer');
            $table->foreignId('level_id');
            $table->string('venue');
            $table->date('date_started');
            $table->date('date_ended');
            $table->integer('total_hours');
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
        Schema::dropIfExists('attendance_conferences');
    }
}
