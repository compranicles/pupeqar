<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyInterCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_inter_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->foreignId('engagment_nature_id');
            $table->foreignId('faculty_involvement_id');
            $table->string('host_name');
            $table->string('host_address');
            $table->string('host_type');
            $table->date('date_started');
            $table->date('date_ended');
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
        Schema::dropIfExists('faculty_inter_countries');
    }
}
