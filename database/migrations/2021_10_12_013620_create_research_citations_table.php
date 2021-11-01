<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchCitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_citations', function (Blueprint $table) {
            $table->id();
            $table->string('research_code');
            $table->string('article_title');
            $table->string('article_author');
            $table->string('journal_title');
            $table->string('journal_publisher');
            $table->string('volume');
            $table->string('issue');
            $table->string('page');
            $table->integer('year');
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
        Schema::dropIfExists('research_citations');
    }
}
