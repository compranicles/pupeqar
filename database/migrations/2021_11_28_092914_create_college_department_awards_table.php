<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegeDepartmentAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_department_awards', function (Blueprint $table) {
            $table->id();
            $table->string('name_of_award');
            $table->string('certifying_body');
            $table->string('place');
            $table->date('date');
            $table->foreignId('level');
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
        Schema::dropIfExists('college_department_awards');
    }
}
