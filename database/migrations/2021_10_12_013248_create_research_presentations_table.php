<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchPresentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_presentations', function (Blueprint $table) {
            $table->id();
            $table->string('research_code');
            $table->string('conference_title');
            $table->string('organizer');
            $table->string('venue');
            $table->date('date_presented');
            $table->foreignId('level');
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
        Schema::dropIfExists('research_presentations');
    }
}
