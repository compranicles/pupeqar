<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('inventions');
        Schema::create('inventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('invention_title');
            $table->foreignId('invention_class_id');
            $table->text('collaborator');
            $table->date('date_started');
            $table->date('date_ended')->nullable();
            $table->string('present');
            $table->string('invention_nature');
            $table->foreignId('invention_status_id');
            $table->string('funding_agency')->nullable();
            $table->foreignId('funding_type_id');
            $table->decimal('funding_amount', 9, 2)->nullable();
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
        Schema::dropIfExists('inventions');
    }
}
