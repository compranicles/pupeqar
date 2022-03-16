<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\ReportCategory;

class ReportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportCategory::truncate();
        ReportCategory::create([
            'report_type_id'=> 1,
            'name' =>  'Research Registration',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportCategory::create([
            'report_type_id'=> 1,
            'name' =>  'Research Completed',
            'is_active' => 1,
            'order' => 2,
        ]);
        ReportCategory::create([
            'report_type_id'=> 1,
            'name' =>  'Research Publication',
            'is_active' => 1,
            'order' => 3,
        ]);
        ReportCategory::create([
            'report_type_id'=> 1,
            'name' =>  'Research Presentation',
            'is_active' => 1,
            'order' => 4,
        ]);
        ReportCategory::create([
            'report_type_id'=> 1,
            'name' =>  'Research Citation',
            'is_active' => 1,
            'order' => 5,
        ]);
        ReportCategory::create([
            'report_type_id'=> 1,
            'name' =>  'Research Utilization',
            'is_active' => 1,
            'order' => 6,
        ]);
        ReportCategory::create([
            'report_type_id'=> 1,
            'name' =>  'Copyrighted Research Output',
            'is_active' => 1,
            'order' => 7,
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Invention, Innovation, and Creative Works',
            'is_active' => 1,
            'order' => 8
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Expert Service Rendered as Consultant',
            'is_active' => 1,
            'order' => 9
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Expert Service Rendered in Conference, Workshops, and/or training course for Professional',
            'is_active' => 1,
            'order' => 10
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Expert Service Rendered in Academic Journals/Books/Publication/NewsLetter/Creative Works',
            'is_active' => 1,
            'order' => 11
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Extension Program, Project, and Activity (Ongoing and Completed)',
            'is_active' => 1,
            'order' => 12
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Partnership/Linkages/Network',
            'is_active' => 1,
            'order' => 13
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Involvement in Inter-Country Mobility',
            'is_active' => 1,
            'order' => 14
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Instructional Material, Reference/Text Book, Module, Monographs',
            'is_active' => 1,
            'order' => 15
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Course Syllabus/ Guide Developed/Revised/Enhanced',
            'is_active' => 1,
            'order' => 16
        ]);

        // 2
        ReportCategory::create([
            'report_type_id' => 2,
            'name' => 'Request & Queries Acted Upon',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportCategory::create([
            'report_type_id' => 2,
            'name' => 'Students Awards/ Recognitions from Reputable Organizations',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportCategory::create([
            'report_type_id' => 2,
            'name' => 'Students Trainings and Seminars',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportCategory::create([
            'report_type_id' => 2,
            'name' => 'Viable Demonstration Project',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportCategory::create([
            'report_type_id' => 2,
            'name' => 'Awards/ Recognitions Received by College/Branch/Campus from  Reputable Organizations',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportCategory::create([
            'report_type_id' => 2,
            'name' => 'Community Relation and Outreach Program',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportCategory::create([
            'report_type_id' => 2,
            'name' => 'Technical Extension Program/Project/Activity',
            'is_active' => 1,
            'order' => 1,
        ]);


        //HRIS Report Category Seeder
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Ongoing Advanced/Professional Study',
            'is_active' => 1,
            'order' => 17
        ]);
        //HRIS Report Category Seeder
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Attendance in Development Programs',
            'is_active' => 1,
            'order' => 18
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Attendance in Trainings',
            'is_active' => 1,
            'order' => 19
        ]);
        ReportCategory::create([
            'report_type_id' => 1,
            'name' => 'Outstanding Awards/Achievements',
            'is_active' => 1,
            'order' => 20
        ]);
    }
}
