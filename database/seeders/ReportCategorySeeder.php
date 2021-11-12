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
    }
}
