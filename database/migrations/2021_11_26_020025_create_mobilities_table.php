<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nature_of_engagement');
            $table->foreignId('type');
            $table->string('host_name');
            $table->string('host_address');
            $table->string('mobility_description');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description');
            $table->foreignId('user_id');
            $table->foreignId('college_id');
            $table->foreignId('department_id');
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
        Schema::dropIfExists('mobilities');
    }
}
