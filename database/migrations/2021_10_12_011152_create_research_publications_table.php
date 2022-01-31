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
            $table->string('publisher')->nullable();
            $table->string('journal_name')->nullable();
            $table->string('editor')->nullable();
            $table->foreignId('level')->nullable();
            $table->date('publish_date')->nullable();
            $table->string('issn')->nullable();
            $table->string('page')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->foreignId('indexing_platform')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('research_id');
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
