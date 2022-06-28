<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentCollegeInTechnicalExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('technical_extensions', function (Blueprint $table) {
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade')->after('total_profit');
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
        Schema::table('technical_extensions', function (Blueprint $table) {
            //
        });
    }
}
