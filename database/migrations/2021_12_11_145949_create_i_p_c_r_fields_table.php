<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIPCRFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('i_p_c_r_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('i_p_c_r_form_id');
            $table->string('label');
            $table->string('name');
            $table->string('placeholder')->nullable();
            $table->string('size');
            $table->foreignId('field_type_id');
            $table->foreignId('dropdown_id')->nullable();
            $table->integer('required');
            $table->integer('visibility');
            $table->integer('order');
            $table->integer('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('i_p_c_r_fields');
    }
}
