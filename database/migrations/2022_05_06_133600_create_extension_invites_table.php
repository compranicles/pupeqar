<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extension_service_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sender_id');
            $table->foreignId('user_id')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('status')->nullable();
            $table->boolean('is_owner');
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
        Schema::dropIfExists('extension_invites');
    }
}
