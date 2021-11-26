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
            $table->foreignId('category');
            $table->foreignId('level');
            $table->date('date_started');
            $table->date('date_completed');
            $table->string('title');
            $table->string('authors_compilers');
            $table->string('editor_name');
            $table->string('editor_profession');
            $table->integer('volume_no');
            $table->integer('issue_no');
            $table->date('date_published');
            $table->string('copyright_regi_no');
            $table->text('description');
            $table->foreignId('user_id');
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
        Schema::dropIfExists('references');
    }
}
