<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyExtensionistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_extensionists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('college_id')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('faculty_extensionists');
    }
}
