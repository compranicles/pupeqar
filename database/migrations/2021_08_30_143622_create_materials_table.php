<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('category')->nullable();
            $table->foreignId('level_id');
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('editor_name')->nullable();
            $table->string('editor_profession')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->date('date_publication')->nullable();
            $table->string('copyright')->nullable();
            $table->date('date_started');
            $table->date('date_completed');
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
        Schema::dropIfExists('materials');
    }
}
