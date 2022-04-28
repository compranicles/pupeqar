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
            $table->string('name')->nullable();
            $table->foreignId('currency_revenue')->nullable();
            $table->decimal('revenue', 15, 2)->nullable();
            $table->foreignId('currency_cost')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->string('rate_of_return')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
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
