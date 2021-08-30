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
            $table->foreignId('department_id');
            $table->string('output');
            $table->string('target');
            $table->string('actual');
            $table->text('accomplishment')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->text('document_description');
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
