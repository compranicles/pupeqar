<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 400);
            $table->string('description', 1000)->nullable();
            $table->string('organizer', 1000)->nullable();
            $table->string('sponsor', 1000)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location', 1000);
            $table->integer('status');
            $table->foreignId('event_type_id')
                    ->constrained('event_types')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
