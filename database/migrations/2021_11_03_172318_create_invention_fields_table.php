<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventionFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invention_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invention_form_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('label');
            $table->string('name');
            $table->text('placeholder')->nullable();
            $table->string('size');
            $table->foreignId('field_type_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('dropdown_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('invention_fields');
    }
}
