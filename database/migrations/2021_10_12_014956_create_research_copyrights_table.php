<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchCopyrightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_copyrights', function (Blueprint $table) {
            $table->id();
            $table->string('research_code');
            $table->string('copyright_agency')->nullable();
            $table->string('copyright_number')->nullable();
            $table->string('copyright_year')->nullable();
            $table->foreignId('copyright_level')->nullable();
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
        Schema::dropIfExists('research_copyrights');
    }
}
