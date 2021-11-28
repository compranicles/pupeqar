<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViableProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viable_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('revenue', 9, 2);
            $table->decimal('cost', 9, 2);
            $table->date('start_date');
            $table->string('rate_of_return');
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
        Schema::dropIfExists('viable_projects');
    }
}
