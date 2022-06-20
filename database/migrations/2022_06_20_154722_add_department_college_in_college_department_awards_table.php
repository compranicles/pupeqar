<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentCollegeInCollegeDepartmentAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('college_department_awards', function (Blueprint $table) {
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade')->after('level');
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
        Schema::table('college_department_awards', function (Blueprint $table) {
            //
        });
    }
}
