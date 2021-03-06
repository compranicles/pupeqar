<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertServiceConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_service_consultants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification')->nullable();
            $table->foreignId('category')->nullable();
            $table->foreignId('level')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('title')->nullable();
            $table->string('venue')->nullable();
            $table->string('partner_agency')->nullable();
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
        Schema::dropIfExists('expert_service_consultants');
    }
}
