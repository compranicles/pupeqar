<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminSpecialTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_special_tasks', function (Blueprint $table) {
            $table->id();
            $table->text('accomplishment_description')->nullable();
            $table->text('output')->nullable();
            $table->text('outcome')->nullable();
            $table->string('participation')->nullable();
            $table->string('special_order')->nullable();
            $table->foreignId('level')->nullable();
            $table->string('nature_of_accomplishment')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
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
        Schema::dropIfExists('admin_special_tasks');
    }
}
