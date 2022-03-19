<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingQuartersAndYearsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $lists = [
            'reports',
            'college_department_awards',
            'expert_service_academics',
            'expert_service_conferences',
            'expert_service_consultants',
            'extension_services',
            'inventions',
            'mobilities',
            'outreach_programs',
            'partnerships',
            'references',
            'requests',
            'research',
            'research_citations',
            'research_completes',
            'research_copyrights',
            'research_presentations',
            'research_publications',
            'research_utilizations',
            'student_awards',
            'student_trainings',
            'syllabi',
            'technical_extensions',
            'viable_projects',
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
            'reports',
            'college_department_awards',
            'expert_service_academics',
            'expert_service_conferences',
            'expert_service_consultants',
            'extension_services',
            'inventions',
            'mobilities',
            'outreach_programs',
            'partnerships',
            'references',
            'requests',
            'research',
            'research_citations',
            'research_completes',
            'research_copyrights',
            'research_presentations',
            'research_publications',
            'research_utilizations',
            'student_awards',
            'student_trainings',
            'syllabi',
            'technical_extensions',
            'viable_projects',
        ];
        foreach($lists as $list){
            Schema::table($list, function (Blueprint $table) {
                $table->dropColumn('report_quarter');
                $table->dropColumn('report_year');
            });
        }
    }
}
