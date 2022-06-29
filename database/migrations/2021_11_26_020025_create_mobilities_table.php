<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobilities', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('classification_of_person')->nullable();
            // $table->foreignId('type')->nullable();
            $table->string('nature_of_engagement')->nullable();
            // $table->string('classification_of_mobility')->nullable();
            $table->string('host_name')->nullable();
            $table->string('host_address')->nullable();
            $table->string('mobility_description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('mobilities');
    }
}
