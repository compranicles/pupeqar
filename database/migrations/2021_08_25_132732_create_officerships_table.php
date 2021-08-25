<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('organization');
            $table->foreignId('faculty_officer_id');
            $table->string('position');
            $table->foreignId('level_id');
            $table->string('organization_address');
            $table->date('date_started');
            $table->date('date_ended');
            $table->string('present')->nullable();
            $table->string('documentdescription');
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
        Schema::dropIfExists('officerships');
    }
}
