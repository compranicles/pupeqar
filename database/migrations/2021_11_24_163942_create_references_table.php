<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category')->nullable();
            $table->foreignId('level')->nullable();
            $table->date('date_started')->nullable();
            $table->date('date_completed')->nullable();
            $table->string('title')->nullable();
            $table->string('authors_compilers')->nullable();
            $table->string('editor_name')->nullable();
            $table->string('editor_profession')->nullable();
            $table->integer('volume_no')->nullable();
            $table->integer('issue_no')->nullable();
            $table->date('date_published')->nullable();
            $table->string('copyright_regi_no')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('references')->nullable();
    }
}
