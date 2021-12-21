<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('college_id');
            $table->foreignId('department_id')->nullable();
            $table->foreignId('report_category_id');
            $table->string('report_code')->nullable();
            $table->integer('report_reference_id')->nullable();
            $table->longText('report_details');
            $table->longText('report_documents');
            $table->date('report_date');
            $table->boolean('chairperson_approval')->nullable();
            $table->boolean('dean_approval')->nullable();
            $table->boolean('sector_approval')->nullable();
            $table->boolean('ipqmso_approval')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
