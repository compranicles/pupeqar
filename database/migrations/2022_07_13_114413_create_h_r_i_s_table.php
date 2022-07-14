<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHRISTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h_r_i_s', function (Blueprint $table) {
            $table->id();
            $table->integer('hris_id');
            $table->integer('hris_type');
            $table->foreignId('college_id');
            $table->foreignId('department_id');
            $table->foreignId('user_id');
            $table->integer('report_quarter');
            $table->integer('report_year');
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
        Schema::dropIfExists('h_r_i_s');
    }
}
