<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Changes2OnMobilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mobilities', function (Blueprint $table) {
            // $table->foreignId('classification_of_person')->nullable()->after('id');
            // $table->foreignId('type')->nullable()->after('classification_of_person');
            $table->string('nature_of_engagement')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
