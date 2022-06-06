<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commitment_measure')->nullable();
            $table->text('final_output')->nullable();
            $table->date('target_date')->nullable();
            $table->date('actual_date')->nullable();
            $table->text('accomplishment_description')->nullable();
            $table->foreignId('status')->nullable();
            $table->string('remarks')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('college_id')->nullable();
            $table->foreignId('department_id')->nullable();
            $table->string('report_quarter')->nullable();
            $table->string('report_year')->nullable();
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
        Schema::dropIfExists('special_tasks');
    }
}
