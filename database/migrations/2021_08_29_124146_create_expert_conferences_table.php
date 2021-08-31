<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertConferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_conferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->foreignId('service_conference_id');
            $table->string('conference_title');
            $table->string('partner_agency')->nullable();
            $table->string('venue')->nullable();
            $table->date('date_started');
            $table->date('date_ended')->nullable();
            $table->string('present')->nullable();
            $table->foreignId('level_id');
            $table->text('document_description');
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
        Schema::dropIfExists('expert_conferences');
    }
}
