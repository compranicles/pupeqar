<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherDeptAccomplishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_dept_accomplishments', function (Blueprint $table) {
            $table->id();
            $table->text('accomplishment_description')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('place')->nullable();
            $table->foreignId('level')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('other_dept_accomplishments');
    }
}
