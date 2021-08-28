<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOngoingAdvancedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ongoing_advanceds');
        Schema::create('ongoing_advanceds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('degree');
            $table->string('school');
            $table->foreignId('accre_level_id');
            $table->foreignId('support_type_id');
            $table->string('sponsor');
            $table->decimal('amount', 9, 2)->nullable();
            $table->date('date_started');
            $table->date('date_ended');
            $table->foreignId('study_status_id');
            $table->integer('units_earned');
            $table->integer('units_enrolled');
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
        Schema::dropIfExists('ongoing_advanceds');
    }
}
