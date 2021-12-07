<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherFieldsInExtensionServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extension_services', function (Blueprint $table) {
            $table->string('other_classification')->after('classification');
            $table->string('other_classification_of_trainees')->after('classification_of_trainees_or_beneficiaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extension_services', function (Blueprint $table) {
            //
        });
    }
}
