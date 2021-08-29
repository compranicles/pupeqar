<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->foreignId('service_journal_id');
            $table->foreignId('service_nature_id');
            $table->string('production');
            $table->foreignId('index_platform_id');
            $table->string('isbn')->nullable();
            $table->foreignId('level');
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
        Schema::dropIfExists('expert_journals');
    }
}
