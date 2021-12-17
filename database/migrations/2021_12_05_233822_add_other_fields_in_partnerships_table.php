<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherFieldsInPartnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partnerships', function (Blueprint $table) {
            $table->string('other_collab_nature')->after('collab_nature')->nullable();
            $table->string('other_partnership_type')->after('partnership_type')->nullable();
            $table->string('other_deliverable')->after('deliverable')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partnerships', function (Blueprint $table) {
            //
        });
    }
}
