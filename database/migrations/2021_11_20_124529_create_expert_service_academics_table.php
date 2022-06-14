<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertServiceAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_service_academics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification')->nullable();
            $table->string('nature')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('publication_or_audio_visual')->nullable();
            $table->string('copyright_no', 100)->nullable();
            $table->foreignId('indexing')->nullable();
            $table->foreignId('level')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('expert_service_academics');
    }
}
