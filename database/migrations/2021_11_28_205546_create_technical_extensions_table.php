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
            $table->string('moa_code');
            $table->string('program_title');
            $table->string('project_title');
            $table->string('activity_title');
            $table->string('name_of_adoptor');
            $table->foreignId('classification_of_adoptor');
            $table->string('nature_of_business_enterprise');
            $table->string('has_businesses');
            $table->foreignId('is_borrowed');
            $table->foreignId('currency');
            $table->decimal('total_profit', 9, 2 );
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
        Schema::dropIfExists('technical_extensions');
    }
}
