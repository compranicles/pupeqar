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
            $table->string('moa_code');
            $table->foreignId('collab_nature');
            $table->foreignId('partnershp_type');
            $table->foreignId('deliverable');
            $table->string('name_of_partner');
            $table->string('title_of_partnership');
            $table->string('partnership_type');
            $table->string('beneficiaries');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('level');
            $table->string('name_of_contact_person');
            $table->string('address_of_contact_person');
            $table->string('telephone_number');
            $table->string('description');
            $table->foreignId('user_id');
            $table->foreignId('college_id');
            $table->foreignId('department_id');
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

