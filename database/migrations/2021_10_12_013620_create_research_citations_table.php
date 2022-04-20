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
            $table->string('article_title')->nullable();
            $table->string('article_author')->nullable();
            $table->string('journal_title')->nullable();
            $table->string('journal_publisher')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->string('page')->nullable();
            $table->integer('year')->nullable();
            $table->foreignId('indexing_platform')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('research_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
