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
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Type of Research',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Status',
            'table' => 'research',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'research',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Department',
            'table' => 'research',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 1,
            'name' => 'Description of Supporting Documents',
            'table' => 'research',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Research Completed
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Type of Research',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Status',
            'table' => 'research',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'research',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Department',
            'table' => 'research',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 2,
            'name' => 'Description of Supporting Documents',
            'table' => 'research_completes',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Research Publication
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Type of Research',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Journal Name',
            'table' => 'research_publications',
            'column' => 'journal_name',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Page Number',
            'table' => 'research_publications',
            'column' => 'page',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Volume No.',
            'table' => 'research_publications',
            'column' => 'volume',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Issue No.',
            'table' => 'research_publications',
            'column' => 'issue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Indexing Platform',
            'table' => 'research_publications',
            'column' => 'indexing_platform',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Date Published',
            'table' => 'research_publications',
            'column' => 'publish_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Publisher',
            'table' => 'research_publications',
            'column' => 'publisher',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Editor',
            'table' => 'research_publications',
            'column' => 'editor',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'ISSN/ISBN',
            'table' => 'research_publications',
            'column' => 'issn',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Level of Publication',
            'table' => 'research_publications',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'research',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Department',
            'table' => 'research',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 3,
            'name' => 'Description of Supporting Documents',
            'table' => 'research_publications',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Research Presentations
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Type of Research',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Conference Title',
            'table' => 'research_presentations',
            'column' => 'conference_title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Organizer',
            'table' => 'research_presentations',
            'column' => 'organizer',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Venue',
            'table' => 'research_presentations',
            'column' => 'venue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Date of Presentation',
            'table' => 'research_presentations',
            'column' => 'date_presented',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Level',
            'table' => 'research_presentations',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'research',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Department',
            'table' => 'research',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 4,
            'name' => 'Description of Supporting Documents',
            'table' => 'research_presentations',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Research Citation
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Type of Research',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Title of Article Where Your Research has been cited',
            'table' => 'research_citations',
            'column' => 'article_title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Author/s Who Cited Your Research',
            'table' => 'research_citations',
            'column' => 'article_author',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Title of the Journal/ Books Where Your Article has been cited',
            'table' => 'research_citations',
            'column' => 'journal_title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Volume No. of the Journal/Book Where Your Article has been cited',
            'table' => 'research_citations',
            'column' => 'volume',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Issue No. of the Journal/Book Where Your Article has been cited',
            'table' => 'research_citations',
            'column' => 'issue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Page No. of the Journal/Book Where Your Article has been cited',
            'table' => 'research_citations',
            'column' => 'page',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Year of Publication of the Journal/Book Where Your Article has been cited',
            'table' => 'research_citations',
            'column' => 'year',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Name of Publisher of the Journal/Book Where Your Article has been cited',
            'table' => 'research_citations',
            'column' => 'journal_publisher',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Indexing Platform of the Journal Where Your Article has been cited',
            'table' => 'research_citations',
            'column' => 'indexing_platform',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'research',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Department',
            'table' => 'research',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 5,
            'name' => 'Description of Supporting Documents',
            'table' => 'research_citations',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Research Utilization
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Type of Research',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Agency/Organization that utilized the research output',
            'table' => 'research_utilizations',
            'column' => 'organization',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Brief Description of Research Utilization',
            'table' => 'research_utilizations',
            'column' => 'utilization_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Level of Utilization',
            'table' => 'research_utilizations',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'research',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Department',
            'table' => 'research',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 6,
            'name' => 'Description of Supporting Documents',
            'table' => 'research_utilizations',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Copyrighted Research Output
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Code',
            'table' => 'research',
            'column' => 'research_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Research Classification',
            'table' => 'research',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Category',
            'table' => 'research',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'University Research Agenda',
            'table' => 'research',
            'column' => 'agenda',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Title of Research',
            'table' => 'research',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Researcher/s',
            'table' => 'research',
            'column' => 'researchers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Nature of Involvement',
            'table' => 'research',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Type of Research',
            'table' => 'research',
            'column' => 'research_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Keywords',
            'table' => 'research',
            'column' => 'keywords',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Type of Funding',
            'table' => 'research',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Amount of Funding',
            'table' => 'research',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Funding Agency',
            'table' => 'research',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Actual Date Started',
            'table' => 'research',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Target Date of Completion',
            'table' => 'research',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Date Completed',
            'table' => 'research',
            'column' => 'completion_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Copyright Number (ISSN/ISBN)',
            'table' => 'research_copyrights',
            'column' => 'copyright_number',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Copyright Agency',
            'table' => 'research_copyrights',
            'column' => 'copyright_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Year the research copyrighted',
            'table' => 'research_copyrights',
            'column' => 'copyright_year',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Level',
            'table' => 'research_copyrights',
            'column' => 'copyright_level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'research',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Department',
            'table' => 'research',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 7,
            'name' => 'Description of Supporting Documents',
            'table' => 'research_copyrights',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Faculty invention innovation
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Title of Invention, Innovation, & Creative Works',
            'table' => 'inventions',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Classification',
            'table' => 'inventions',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Name of Collaborator/s',
            'table' => 'inventions',
            'column' => 'collaborator',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'From',
            'table' => 'inventions',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'To',
            'table' => 'inventions',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Nature of Inventions',
            'table' => 'inventions',
            'column' => 'nature',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Status',
            'table' => 'inventions',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Funding Agency',
            'table' => 'inventions',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Funding Type',
            'table' => 'inventions',
            'column' => 'funding_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Amount of Fund',
            'table' => 'inventions',
            'column' => 'funding_amount',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'inventions',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Department',
            'table' => 'inventions',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 8,
            'name' => 'Description of Supporting Documents',
            'table' => 'inventions',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Expert Service Rendered as Consultant
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Classification of Expert services Rendered as a Consultant/Expert',
            'table' => 'expert_service_consultants',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Title of Expert Services Rendered',
            'table' => 'expert_service_consultants',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Category of Expert Services',
            'table' => 'expert_service_consultants',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Partner Agency',
            'table' => 'expert_service_consultants',
            'column' => 'partner_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Venue',
            'table' => 'expert_service_consultants',
            'column' => 'venue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'From',
            'table' => 'expert_service_consultants',
            'column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'To',
            'table' => 'expert_service_consultants',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Level',
            'table' => 'expert_service_consultants',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'expert_service_consultants',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Department',
            'table' => 'expert_service_consultants',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 9,
            'name' => 'Description of Supporting Documents',
            'table' => 'expert_service_consultants',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Expert Service in Conferences
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'Nature of services rendered in conferences, workshops, and/or training courses for professionals',
            'table' => 'expert_service_conferences',
            'column' => 'nature',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'Title of Conference, Workshop, and Training',
            'table' => 'expert_service_conferences',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'Partner Agency',
            'table' => 'expert_service_conferences',
            'column' => 'partner_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'Venue',
            'table' => 'expert_service_consultants',
            'column' => 'venue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'From',
            'table' => 'expert_service_conferences',
            'column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'To',
            'table' => 'expert_service_conferences',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'Level',
            'table' => 'expert_service_conferences',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'expert_service_conferences',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'Department',
            'table' => 'expert_service_conferences',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 10,
            'name' => 'Description of Supporting Documents',
            'table' => 'expert_service_conferences',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);


        // Expert Service in Academic Journals
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'External Services Rendered in Academic Journals/ Books Publication/ Newsletter/ Creative Works ',
            'table' => 'expert_service_academics',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'Nature of Services Rendered ',
            'table' => 'expert_service_academics',
            'column' => 'nature',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'Publication/ Audio Visual Production ',
            'table' => 'expert_service_academics',
            'column' => 'publication_or_audio_visual',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'Indexing (If any)',
            'table' => 'expert_service_academics',
            'column' => 'indexing',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'Copyright No. (ISSN No. /E-ISSN/ ISBN)',
            'table' => 'expert_service_academics',
            'column' => 'copyright_no',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'Level',
            'table' => 'expert_service_academics',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'expert_service_academics',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'Department',
            'table' => 'expert_service_academics',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 11,
            'name' => 'Description of Supporting Documents',
            'table' => 'expert_service_academics',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Extension Program Project Activity
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Title of Extension Program',
            'table' => 'extension_services',
            'column' => 'title_of_extension_program',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Title of Extension Project',
            'table' => 'extension_services',
            'column' => 'title_of_extension_project',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Title of Extension Activity',
            'table' => 'extension_services',
            'column' => 'title_of_extension_activity',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Nature of Involvement',
            'table' => 'extension_services',
            'column' => 'nature_of_involvement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Source of Fund',
            'table' => 'extension_services',
            'column' => 'funding_agency',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Amount of Fund',
            'table' => 'extension_services',
            'column' => 'amount_of_funding',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Classification of Extension Activity',
            'table' => 'extension_services',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Partnership Levels',
            'table' => 'extension_services',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'From',
            'table' => 'extension_services',
            'column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'To',
            'table' => 'extension_services',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Status',
            'table' => 'extension_services',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Place/Venue',
            'table' => 'extension_services',
            'column' => 'place_or_venue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'No. of Trainees',
            'table' => 'extension_services',
            'column' => 'no_of_trainees_or_beneficiaries',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Classification of Trainees',
            'table' => 'extension_services',
            'column' => 'classification_of_trainees_or_beneficiaries',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Quality - Poor',
            'table' => 'extension_services',
            'column' => 'quality_poor',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Quality - Fair',
            'table' => 'extension_services',
            'column' => 'quality_fair',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Quality - Satisfactory',
            'table' => 'extension_services',
            'column' => 'quality_satisfactory',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Quality - Very Satisfactory',
            'table' => 'extension_services',
            'column' => 'quality_very_satisfactory',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Quality - Outstanding',
            'table' => 'extension_services',
            'column' => 'quality_outstanding',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Timeliness - Poor',
            'table' => 'extension_services',
            'column' => 'timeliness_poor',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Timeliness - Fair',
            'table' => 'extension_services',
            'column' => 'timeliness_fair',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Timeliness - Satisfactory',
            'table' => 'extension_services',
            'column' => 'timeliness_satisfactory',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Timeliness - Very Satisfactory',
            'table' => 'extension_services',
            'column' => 'timeliness_very_satisfactory',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Timeliness - Outstanding',
            'table' => 'extension_services',
            'column' => 'timeliness_outstanding',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Number of Hours',
            'table' => 'extension_services',
            'column' => 'total_no_of_hours',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'extension_services',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Department',
            'table' => 'extension_services',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 12,
            'name' => 'Description of Supporting Documents',
            'table' => 'extension_services',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Partnerships
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Title',
            'table' => 'partnerships',
            'column' => 'title_of_partnership',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Name of Organization',
            'table' => 'partnerships',
            'column' => 'name_of_partner',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Type of Partner Institution',
            'table' => 'partnerships',
            'column' => 'partnership_type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Nature of Collaboration',
            'table' => 'partnerships',
            'column' => 'collab_nature',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Deliverable/Desired Output',
            'table' => 'partnerships',
            'column' => 'deliverable',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Target Beneficiaries',
            'table' => 'partnerships',
            'column' => 'beneficiaries',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Level',
            'table' => 'partnerships',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'From',
            'table' => 'partnerships',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'To',
            'table' => 'partnerships',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Name of Contact Person',
            'table' => 'partnerships',
            'column' => 'name_of_contact_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Tel. No. of Contact Person',
            'table' => 'partnerships',
            'column' => 'telephone_number',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Address of Contact Person',
            'table' => 'partnerships',
            'column' => 'address_of_contact_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'partnerships',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Department',
            'table' => 'partnerships',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 13,
            'name' => 'Description of Supporting Documents',
            'table' => 'partnerships',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Faculty involvement in inter-country mobility
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Classification of Person Involved',
            'table' => 'mobilities',
            'column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Type',
            'table' => 'mobilities',
            'column' => 'type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Classification of Mobility',
            'table' => 'mobilities',
            'column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Nature of Engagement',
            'table' => 'mobilities',
            'column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Host Institution/ Organization/ Agency',
            'table' => 'mobilities',
            'column' => 'host_name',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table' => 'mobilities',
            'column' => 'host_address',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Description of Inter-Country Mobility',
            'table' => 'mobilities',
            'column' => 'mobility_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'From',
            'table' => 'mobilities',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'To',
            'table' => 'mobilities',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'mobilities',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Department',
            'table' => 'mobilities',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 14,
            'name' => 'Description of Supporting Documents',
            'table' => 'mobilities',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //IM, Reference/book
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Category',
            'table' => 'references',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Level',
            'table' => 'references',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Title',
            'table' => 'references',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Author/s/Compiler/s',
            'table' => 'references',
            'column' => 'authors_compilers',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Editor Name',
            'table' => 'references',
            'column' => 'editor_name',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Editor Profession',
            'table' => 'references',
            'column' => 'editor_profession',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Volume No.',
            'table' => 'references',
            'column' => 'volume_no',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Issue No.',
            'table' => 'references',
            'column' => 'issue_no',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Date of Publication',
            'table' => 'references',
            'column' => 'date_published',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Copyright Registration',
            'table' => 'references',
            'column' => 'copyright_regi_no',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Date Started',
            'table' => 'references',
            'column' => 'date_started',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Date Completed',
            'table' => 'references',
            'column' => 'date_completed',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'references',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Department',
            'table' => 'references',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 15,
            'name' => 'Description of Supporting Documents',
            'table' => 'references',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //syllabi
        ReportColumn::insert([
            'report_category_id' => 16,
            'name' => 'Course Code',
            'table' => 'syllabi',
            'column' => 'course_code',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 16,
            'name' => 'Course Title',
            'table' => 'syllabi',
            'column' => 'course_title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 16,
            'name' => 'Date Developed/Revised/Reviewed/Enhanced',
            'table' => 'syllabi',
            'column' => 'date_finished',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 16,
            'name' => 'Assigned Task',
            'table' => 'syllabi',
            'column' => 'assigned_task',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 16,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'syllabi',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 16,
            'name' => 'Department',
            'table' => 'syllabi',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 16,
            'name' => 'Description of Supporting Documents',
            'table' => 'syllabi',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);


        // Request
        ReportColumn::insert([
            'report_category_id' => 17,
            'name' => 'Number of Written Request Acten Upon',
            'table' => 'requests',
            'column' => 'no_of_request',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 17,
            'name' => 'Brief Description of Request',
            'table' => 'requests',
            'column' => 'description_of_request',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 17,
            'name' => 'Average Days/ Time or Processing',
            'table' => 'requests',
            'column' => 'processing_time',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 17,
            'name' => 'Category',
            'table' => 'requests',
            'column' => 'category',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 17,
            'name' => 'Proof of Compliance',
            'table' => 'requests',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Student Awards and Recognition
        ReportColumn::insert([
            'report_category_id' => 18,
            'name' => 'Student Name',
            'table' => 'student_awards',
            'column' => 'name_of_student',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 18,
            'name' => 'Name of Award',
            'table' => 'student_awards',
            'column' => 'name_of_award',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 18,
            'name' => 'Certifying Body',
            'table' => 'student_awards',
            'column' => 'certifying_body',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 18,
            'name' => 'Place',
            'table' => 'student_awards',
            'column' => 'place',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 18,
            'name' => 'Date',
            'table' => 'student_awards',
            'column' => 'date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 18,
            'name' => 'Level',
            'table' => 'student_awards',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 18,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'student_awards',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Student trainings and seminars
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Title',
            'table' => 'student_trainings',
            'column' => 'title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'No. of Student Attendees',
            'table' => 'student_trainings',
            'column' => 'no_of_students',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Classification',
            'table' => 'student_trainings',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Nature',
            'table' => 'student_trainings',
            'column' => 'nature',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Budget',
            'table' => 'student_trainings',
            'column' => 'budget',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Source of Fund',
            'table' => 'student_trainings',
            'column' => 'source_of_fund',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Organizer',
            'table' => 'student_trainings',
            'column' => 'organization',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Level',
            'table' => 'student_trainings',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Venue',
            'table' => 'student_trainings',
            'column' => 'venue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'From',
            'table' => 'student_trainings',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'To',
            'table' => 'student_trainings',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Total No. of Hours.',
            'table' => 'student_trainings',
            'column' => 'total_hours',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 19,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'student_trainings',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Viable Demonstration PRoject
        ReportColumn::insert([
            'report_category_id' => 20,
            'name' => 'Name of Viable Demonstration Projects',
            'table' => 'viable_projects',
            'column' => 'name',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 20,
            'name' => 'Revenues',
            'table' => 'viable_projects',
            'column' => 'revenue',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 20,
            'name' => 'Cost',
            'table' => 'viable_projects',
            'column' => 'cost',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 20,
            'name' => 'Date Started',
            'table' => 'viable_projects',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 20,
            'name' => 'Internal Rate of Return',
            'table' => 'viable_projects',
            'column' => 'rate_of_return',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 20,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'viable_projects',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Awards of CBC from ROs
        ReportColumn::insert([
            'report_category_id' => 21,
            'name' => 'Name of Award',
            'table' => 'college_department_awards',
            'column' => 'name_of_award',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 21,
            'name' => 'Certifying Body',
            'table' => 'college_department_awards',
            'column' => 'certifying_body',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 21,
            'name' => 'Place',
            'table' => 'college_department_awards',
            'column' => 'place',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 21,
            'name' => 'Date',
            'table' => 'college_department_awards',
            'column' => 'date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 21,
            'name' => 'Level',
            'table' => 'college_department_awards',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 21,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'college_department_awards',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // community relation and Outreach Program
        ReportColumn::insert([
            'report_category_id' => 22,
            'name' => 'Title of the Program',
            'table' => 'outreach_programs',
            'column' => 'title_of_the_program',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 22,
            'name' => 'Date',
            'table' => 'outreach_programs',
            'column' => 'date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 22,
            'name' => 'Place',
            'table' => 'outreach_programs',
            'column' => 'place',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 22,
            'name' => 'Level',
            'table' => 'outreach_programs',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 22,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'outreach_programs',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        // Technical Extensions
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Title of the Program',
            'table' => 'technical_extensions',
            'column' => 'program_title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Title of the Project',
            'table' => 'technical_extensions',
            'column' => 'project_title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Title of the Activity',
            'table' => 'technical_extensions',
            'column' => 'activity_title',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Name of the Adoptor',
            'table' => 'technical_extensions',
            'column' => 'name_of_adoptor',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Classification',
            'table' => 'technical_extensions',
            'column' => 'classification_of_adoptor',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Nature of Business Enterprise',
            'table' => 'technical_extensions',
            'column' => 'nature_of_business_enterprise',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Adoptors have established profitable businesses in the last three years?',
            'table' => 'technical_extensions',
            'column' => 'has_businesses',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Extension project by the university or borrowed from other institutions?',
            'table' => 'technical_extensions',
            'column' => 'is_borrowed',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Total Profit/ Income of the Adoptors',
            'table' => 'technical_extensions',
            'column' => 'total_profit',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 23,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'technical_extensions',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);


        //Special Tasks Admin
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Brief Description of Accomplishment',
            'table' => 'admin_special_tasks',
            'column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Output',
            'table' => 'admin_special_tasks',
            'column' => 'output',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Outcome',
            'table' => 'admin_special_tasks',
            'column' => 'outcome',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Participation/Significant Contribution',
            'table' => 'admin_special_tasks',
            'column' => 'participation',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Special Order',
            'table' => 'admin_special_tasks',
            'column' => 'special_order',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Level',
            'table' => 'admin_special_tasks',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Nature of Accomplishment',
            'table' => 'admin_special_tasks',
            'column' => 'nature_of_accomplishment',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'From',
            'table' => 'admin_special_tasks',
            'column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'To',
            'table' => 'admin_special_tasks',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 29,
            'name' => 'Proof of Compliance',
            'table' => 'admin_special_tasks',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Special Tasks
        ReportColumn::insert([
            'report_category_id' => 30,
            'name' => 'Final Output',
            'table' => 'special_tasks',
            'column' => 'final_output',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 30,
            'name' => 'Target',
            'table' => 'special_tasks',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 30,
            'name' => 'Actual',
            'table' => 'special_tasks',
            'column' => 'actual_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 30,
            'name' => 'Description of Accomplishment',
            'table' => 'special_tasks',
            'column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 30,
            'name' => 'Status',
            'table' => 'special_tasks',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 30,
            'name' => 'Remarks',
            'table' => 'special_tasks',
            'column' => 'remarks',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 30,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'special_tasks',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);
        //Special Tasks
        ReportColumn::insert([
            'report_category_id' => 31,
            'name' => 'Final Output',
            'table' => 'special_tasks',
            'column' => 'final_output',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 31,
            'name' => 'Target',
            'table' => 'special_tasks',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 31,
            'name' => 'Actual',
            'table' => 'special_tasks',
            'column' => 'actual_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 31,
            'name' => 'Description of Accomplishment',
            'table' => 'special_tasks',
            'column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 31,
            'name' => 'Status',
            'table' => 'special_tasks',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 31,
            'name' => 'Remarks',
            'table' => 'special_tasks',
            'column' => 'remarks',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 31,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'special_tasks',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Special Tasks
        ReportColumn::insert([
            'report_category_id' => 32,
            'name' => 'Final Output',
            'table' => 'special_tasks',
            'column' => 'final_output',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 32,
            'name' => 'Target',
            'table' => 'special_tasks',
            'column' => 'target_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 32,
            'name' => 'Actual',
            'table' => 'special_tasks',
            'column' => 'actual_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 32,
            'name' => 'Description of Accomplishment',
            'table' => 'special_tasks',
            'column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 32,
            'name' => 'Status',
            'table' => 'special_tasks',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 32,
            'name' => 'Remarks',
            'table' => 'special_tasks',
            'column' => 'remarks',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 32,
            'name' => 'Description of Supporting Documents Submitted',
            'table' => 'special_tasks',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        ReportColumn::insert([
            'report_category_id' => 33,
            'name' => 'Brief Description of Activity',
            'table' => 'attendance_functions',
            'column' => 'activity_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 33,
            'name' => 'Classification',
            'table' => 'attendance_functions',
            'column' => 'classification',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 33,
            'name' => 'Date Started',
            'table' => 'attendance_functions',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 33,
            'name' => 'Date Completed',
            'table' => 'attendance_functions',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 33,
            'name' => 'Status of Attendance',
            'table' => 'attendance_functions',
            'column' => 'status',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 33,
            'name' => 'Proof of Attendance',
            'table' => 'attendance_functions',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Involvement in Intra-Country Mobility
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Classification of Person Involved',
            'table' => 'intra_mobilities',
            'column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Type',
            'table' => 'intra_mobilities',
            'column' => 'type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Classification of Mobility',
            'table' => 'intra_mobilities',
            'column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Nature of Engagement',
            'table' => 'intra_mobilities',
            'column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Host Institution/ Organization/ Agency',
            'table' => 'intra_mobilities',
            'column' => 'host_name',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table' => 'intra_mobilities',
            'column' => 'host_address',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Description of Inter-Country Mobility',
            'table' => 'intra_mobilities',
            'column' => 'mobility_description',
            'is_active' => 0,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'From',
            'table' => 'intra_mobilities',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'To',
            'table' => 'intra_mobilities',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'intra_mobilities',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Department',
            'table' => 'intra_mobilities',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 34,
            'name' => 'Description of Supporting Documents',
            'table' => 'intra_mobilities',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Inter-Country Mobility by College/Department
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Classification of Person Involved',
            'table' => 'mobilities',
            'column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Type',
            'table' => 'mobilities',
            'column' => 'type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Classification of Mobility',
            'table' => 'mobilities',
            'column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Nature of Engagement',
            'table' => 'mobilities',
            'column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Host Institution/ Organization/ Agency',
            'table' => 'mobilities',
            'column' => 'host_name',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table' => 'mobilities',
            'column' => 'host_address',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Description of Inter-Country Mobility',
            'table' => 'mobilities',
            'column' => 'mobility_description',
            'is_active' => 0,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'From',
            'table' => 'mobilities',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'To',
            'table' => 'mobilities',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'mobilities',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Department',
            'table' => 'mobilities',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 35,
            'name' => 'Description of Supporting Documents',
            'table' => 'mobilities',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Intra-Country Mobility by College/Department
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Classification of Person Involved',
            'table' => 'intra_mobilities',
            'column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Type',
            'table' => 'intra_mobilities',
            'column' => 'type',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Classification of Mobility',
            'table' => 'intra_mobilities',
            'column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Nature of Engagement',
            'table' => 'intra_mobilities',
            'column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Host Institution/ Organization/ Agency',
            'table' => 'intra_mobilities',
            'column' => 'host_name',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table' => 'intra_mobilities',
            'column' => 'host_address',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Description of Inter-Country Mobility',
            'table' => 'intra_mobilities',
            'column' => 'mobility_description',
            'is_active' => 0,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'From',
            'table' => 'intra_mobilities',
            'column' => 'start_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'To',
            'table' => 'intra_mobilities',
            'column' => 'end_date',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'intra_mobilities',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Department',
            'table' => 'intra_mobilities',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 36,
            'name' => 'Description of Supporting Documents',
            'table' => 'intra_mobilities',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Community Engagement Conducted by the College/Department
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => 'List of Active Linkages/Partnerships Covered by MOA',
            'table' => 'community_engagements',
            'column' => 'active_linkages',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => 'Classification of Agro-industrial Technology',
            'table' => 'community_engagements',
            'column' => 'classification_of_agro',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => 'Inclusive Period',
            'table' => 'community_engagements',
            'column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => '-',
            'table' => 'community_engagements',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => 'Partnership Coverage (LGU, Industry, SMEs (Small & Medium Enteprises), NGOs, and Pos)',
            'table' => 'community_engagements',
            'column' => 'partnership_coverage',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => '-',
            'table' => 'community_engagements',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'community_engagements',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => 'Department',
            'table' => 'community_engagements',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 37,
            'name' => 'Description of Supporting Documents',
            'table' => 'community_engagements',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Other Accomplishments Beyond the Mandatory Requirements
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => 'Brief Description of Accomplishment',
            'table' => 'other_accomplishments',
            'column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => 'Inclusive Date',
            'table' => 'other_accomplishments',
            'column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => '-',
            'table' => 'other_accomplishments',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => 'Place',
            'table' => 'other_accomplishments',
            'column' => 'place',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => 'Level',
            'table' => 'other_accomplishments',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'other_accomplishments',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => 'Department',
            'table' => 'other_accomplishments',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 38,
            'name' => 'Description of Supporting Documents',
            'table' => 'other_accomplishments',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //Other Accomplishments (College and Department)
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => 'Brief Description of Accomplishment',
            'table' => 'other_accomplishments',
            'column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => 'Inclusive Date',
            'table' => 'other_accomplishments',
            'column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => '-',
            'table' => 'other_accomplishments',
            'column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => 'Place',
            'table' => 'other_accomplishments',
            'column' => 'place',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => 'Level',
            'table' => 'other_accomplishments',
            'column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => 'College/Branch/Campus/Office',
            'table' => 'other_accomplishments',
            'column' => 'college_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => 'Department',
            'table' => 'other_accomplishments',
            'column' => 'department_id',
            'is_active' => 1,
            'order' => 1,
        ]);
        ReportColumn::insert([
            'report_category_id' => 39,
            'name' => 'Description of Supporting Documents',
            'table' => 'other_accomplishments',
            'column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);
    }
}
