<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrenciesInViableProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('viable_projects', function (Blueprint $table) {
            $table->foreignId('currency_revenue')->after('name');
            $table->foreignId('currency_cost')->after('revenue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('viable_projects', function (Blueprint $table) {
            //
        });
    }
}
