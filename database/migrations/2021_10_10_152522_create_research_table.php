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
            $table->string('research_code')->unique();
            $table->foreignId('college_id');
            $table->foreignId('department_id');
            $table->foreignId('classification');
            $table->foreignId('category');
            $table->foreignId('agenda');
            $table->string('title');
            $table->string('researchers');
            $table->string('keywords');
            $table->foreignId('nature_of_involvement');
            $table->foreignId('research_type');
            $table->foreignId('funding_type');
            $table->decimal('funding_amount', 9, 2);
            $table->string('funding_agency');
            $table->date('start_date');
            $table->date('target_date');
            $table->date('completion_date')->nullable();
            $table->foreignId('status');
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
        Schema::dropIfExists('research');
    }
}
