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
            $table->foreignId('classification');
            $table->foreignId('nature');
            $table->date('from');
            $table->date('to');
            $table->string('publication_or_audio_visual');
            $table->string('copyright_no', 100);
            $table->foreignId('indexing');
            $table->foreignId('level');
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
        Schema::dropIfExists('expert_service_academics');
    }
}
