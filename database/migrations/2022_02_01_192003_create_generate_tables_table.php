<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generate_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_table');
            $table->foreignId('type_id')->nullable();
            $table->foreignId('report_category_id')->nullable();
            $table->text('footers')->nullable();
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
        Schema::dropIfExists('generate_tables');
    }
}
