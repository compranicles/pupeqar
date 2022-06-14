<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegeDepartmentAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_department_awards', function (Blueprint $table) {
            $table->id();
            $table->string('name_of_award')->nullable();
            $table->string('certifying_body')->nullable();
            $table->string('place')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('level')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('college_department_awards');
    }
}
