<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id');
            $table->string('title');
            $table->foreignId('partner_type_id');
            $table->foreignId('collab_nature_id');
            $table->foreignId('collab_deliver_id');
            $table->foreignId('target_beneficiary_id');
            $table->foreignId('level_id');
            $table->date('date_started');
            $table->date('date_ended')->nullable();
            $table->string('present')->nullable();
            $table->string('contact_name');
            $table->string('contact_number');
            $table->string('contact_address');
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
        Schema::dropIfExists('partnerships');
    }
}
