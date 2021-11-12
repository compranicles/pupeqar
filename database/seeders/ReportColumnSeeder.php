<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\ReportColumn;

class ReportColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportColumn::truncate();
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Research Type',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Status',
            'table' => 'research',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::create([
            'report_category_id' => 1,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'research',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);
    }
}
