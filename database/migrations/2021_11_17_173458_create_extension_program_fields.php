<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionProgramFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_program_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extension_programs_form_id');
            $table->string('label');
            $table->string('name');
            $table->text('placeholder')->nullable();
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
        Schema::dropIfExists('extension_program_fields');
    }
}
