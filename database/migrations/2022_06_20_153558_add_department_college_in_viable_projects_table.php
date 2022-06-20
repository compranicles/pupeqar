<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentCollegeInViableProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('viable_projects', function (Blueprint $table) {
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade')->after('rate_of_return');
            $table->foreignId('department_id')->nullable()->onUpdate('cascade')->onDelete('cascade')->after('college_id');
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
