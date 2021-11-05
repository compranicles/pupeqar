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
        Schema::create('inventions', function (Blueprint $table) {
            $table->string('invention_code')->nullable();
            $table->foreignId('college_id');
            $table->foreignId('department_id');
            $table->foreignId('classification');
            $table->string('nature');
            $table->string('title');
            $table->string('collaborator');
            $table->string('funding_agency');
            $table->decimal('funding_amount', 9, 2);
            $table->foreignId('funding_type');
            $table->foreignId('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('utilization');
            $table->string('copyright_number');
            $table->date('issue_date');
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
        Schema::dropIfExists('inventions');
    }
}
