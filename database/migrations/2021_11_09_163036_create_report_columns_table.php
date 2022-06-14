<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_category_id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('table');
            $table->string('column');
            $table->integer('is_active');
            $table->integer('order');
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
        Schema::dropIfExists('report_columns');
    }
}
