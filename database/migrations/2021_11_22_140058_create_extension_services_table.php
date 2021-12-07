<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level');
            $table->foreignId('status');
            $table->foreignId('nature_of_involvement');
            $table->foreignId('classification');
            $table->foreignId('type');
            $table->string('title_of_extension_program');
            $table->string('title_of_extension_project');
            $table->string('title_of_extension_activity');
            $table->string('funding_agency');
            $table->foreignId('currency');
            $table->decimal('amount_of_funding', 9, 2);
            $table->foreignId('type_of_funding');
            $table->date('from');
            $table->date('to');
            $table->integer('no_of_trainees_or_beneficiaries');
            $table->decimal('total_no_of_hours', 9, 1);
            $table->foreignId('classification_of_trainees_or_beneficiaries');
            $table->string('place_or_venue');
            $table->string('keywords');
            $table->integer('quality_poor');
            $table->integer('quality_fair');
            $table->integer('quality_satisfactory');
            $table->integer('quality_very_satisfactory');
            $table->integer('quality_outstanding');
            $table->integer('timeliness_poor');
            $table->integer('timeliness_fair');
            $table->integer('timeliness_satisfactory');
            $table->integer('timeliness_very_satisfactory');
            $table->integer('timeliness_outstanding');
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
        Schema::dropIfExists('extension_services');
    }
}
