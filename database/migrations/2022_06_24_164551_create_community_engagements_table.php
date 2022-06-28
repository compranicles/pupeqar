<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityEngagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_engagements', function (Blueprint $table) {
            $table->id();
            $table->string('active_linkages')->nullable();
            $table->string('classification_of_agro')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('partnership_coverage')->nullable();
            $table->foreignId('college_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('community_engagements');
    }
}
