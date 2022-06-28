<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuarterAndYearToTables2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $lists = [
            'intra_mobilities',
            'community_engagements',
            'other_accomplishments',
            'other_dept_accomplishments',
        ];
        foreach($lists as $list){
            Schema::table($list, function (Blueprint $table) {
                $table->integer('report_quarter')->after('deleted_at');
                $table->integer('report_year')->after('report_quarter');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $lists = [
            'intra_mobilities',
            'community_engagements',
            'other_accomplishments',
            'other_dept_accomplishments',
        ];
        foreach($lists as $list){
            Schema::table($list, function (Blueprint $table) {
                $table->dropColumn('report_quarter');
                $table->dropColumn('report_year');
            });
        }
    }

}
