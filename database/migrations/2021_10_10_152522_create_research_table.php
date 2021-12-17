<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research', function (Blueprint $table) {
            $table->id();
            $table->string('research_code');
            $table->foreignId('college_id')->nullable();
            $table->foreignId('department_id')->nullable();
            $table->foreignId('classification')->nullable();
            $table->foreignId('category')->nullable();
            $table->foreignId('agenda')->nullable();
            $table->string('title')->nullable();
            $table->string('researchers')->nullable();
            $table->string('keywords')->nullable();
            $table->foreignId('nature_of_involvement')->nullable();
            $table->foreignId('research_type')->nullable();
            $table->foreignId('funding_type')->nullable();
            $table->foreignId('currency')->nullable();
            $table->decimal('funding_amount', 9, 2)->nullable();
            $table->string('funding_agency')->nullable();
            $table->date('start_date')->nullable();
            $table->date('target_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->foreignId('status')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('research');
    }
}
