<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('program')->nullable();
            $table->string('project')->nullable();
            $table->string('activity')->nullable();
            $table->foreignId('extension_nature_id');
            $table->foreignId('funding_type_id');
            $table->decimal('funding_amount', 9, 2)->nullable();
            $table->foreignId('extension_class_id');
            $table->string('others')->nullable();
            $table->date('date_started');
            $table->date('date_ended')->nullable();
            $table->string('present')->nullable();
            $table->foreignId('status_id');
            $table->string('venue')->nullable();
            $table->integer('trainees')->nullable();
            $table->string('trainees_class')->nullable();
            $table->integer('quality_poor')->nullable();
            $table->integer('quality_fair')->nullable();
            $table->integer('quality_satisfactory')->nullable();
            $table->integer('quality_vsatisfactory')->nullable();
            $table->integer('quality_outstanding')->nullable();
            $table->integer('timeliness_poor')->nullable();
            $table->integer('timeliness_fair')->nullable();
            $table->integer('timeliness_satisfactory')->nullable();
            $table->integer('timeliness_vsatisfactory')->nullable();
            $table->integer('timeliness_outstanding')->nullable();
            $table->integer('hours')->nullable();
            $table->string('document_description');
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
        Schema::dropIfExists('extension_programs');
    }
}
