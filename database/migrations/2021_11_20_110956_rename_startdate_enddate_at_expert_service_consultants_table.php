<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStartdateEnddateAtExpertServiceConsultantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expert_service_consultants', function (Blueprint $table) {
            $table->renameColumn('start_date', 'from');
            $table->renameColumn('end_date', 'to');
            $table->renameColumn('partner', 'partner_agency');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
