<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnicalExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_extensions', function (Blueprint $table) {
            $table->id();
            $table->string('moa_code')->nullable();
            $table->string('program_title')->nullable();
            $table->string('project_title')->nullable();
            $table->string('activity_title')->nullable();
            $table->string('name_of_adoptor')->nullable();
            $table->foreignId('classification_of_adoptor')->nullable();
            $table->string('nature_of_business_enterprise')->nullable();
            $table->string('has_businesses')->nullable();
            $table->foreignId('is_borrowed')->nullable();
            $table->foreignId('currency')->nullable();
            $table->decimal('total_profit', 15, 2 )->nullable();
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
        Schema::dropIfExists('technical_extensions')->nullable();
    }
}
