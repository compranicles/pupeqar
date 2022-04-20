<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_documents', function (Blueprint $table) {
            $table->id();
            $table->string('research_code');
            $table->foreignId('research_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('research_form_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('research_citation_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('research_utilization_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('filename');
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
        Schema::dropIfExists('research_documents');
    }
}
