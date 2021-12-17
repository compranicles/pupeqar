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
            $table->string('moa_code')->nullable();
            $table->foreignId('collab_nature')->nullable();
            $table->foreignId('partnershp_type')->nullable();
            $table->foreignId('deliverable')->nullable();
            $table->string('name_of_partner')->nullable();
            $table->string('title_of_partnership')->nullable();
            $table->string('partnership_type')->nullable();
            $table->string('beneficiaries')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('level')->nullable();
            $table->string('name_of_contact_person')->nullable();
            $table->string('address_of_contact_person')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('college_id')->nullable();
            $table->foreignId('department_id')->nullable();
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
        Schema::dropIfExists('partnerships')->nullable();
    }
}

