<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollaboratingCountryInIntraMobilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intra_mobilities', function (Blueprint $table) {
            $table->string('collaborating_country')->after('mobility_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intra_mobilities', function (Blueprint $table) {
            $table->string('collaborating_country')->after('mobility_description')->nullable();
        });
    }
}
