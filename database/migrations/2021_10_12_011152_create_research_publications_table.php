<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchPublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_publications', function (Blueprint $table) {
            $table->id();
            $table->string('research_code');
            $table->string('publisher');
            $table->string('journal_name');
            $table->string('editor');
            $table->foreignId('level');
            $table->date('publish_date');
            $table->string('issn');
            $table->string('page');
            $table->string('volume');
            $table->string('issue');
            $table->foreignId('indexing_platform');
            $table->text('description');
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
        Schema::dropIfExists('research_publications');
    }
}
