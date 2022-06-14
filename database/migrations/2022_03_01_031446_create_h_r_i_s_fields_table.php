<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHRISFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h_r_i_s_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('h_r_i_s_form_id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('label');
            $table->string('name');
            $table->text('placeholder')->nullable();
            $table->string('size');
            $table->foreignId('field_type_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('dropdown_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('required');
            $table->integer('visibility');
            $table->integer('order');
            $table->integer('is_active');
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
        Schema::dropIfExists('h_r_i_s_fields');
    }
}
