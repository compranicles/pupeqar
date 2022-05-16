<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\GenerateColumn;

class GenerateColumn4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Technical Ext. */
        // GenerateColumn::insert([
        //     'name' => 'Title of the Program',
        //     'table_id' => 70,
        //     'report_column' => 'program_title',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Title of the Project',
        //     'table_id' => 70,
        //     'report_column' => 'project_title',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Title of the Activity',
        //     'table_id' => 70,
        //     'report_column' => 'activity_title',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Name of the Adoptor',
        //     'table_id' => 70,
        //     'report_column' => 'Name of the Adoptor',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Classification of Adoptor',
        //     'table_id' => 70,
        //     'report_column' => 'classification_of_adoptor',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Nature of Business Enterprise',
        //     'table_id' => 70,
        //     'report_column' => 'nature_of_business_enterprise',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Adoptors have established profitable businesses in the last three years',
        //     'table_id' => 70,
        //     'report_column' => 'has_businesses',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Extension project by the university or borrowed from other institutions',
        //     'table_id' => 70,
        //     'report_column' => 'is_borrowed',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Total Profit/Income of the Adoptors',
        //     'table_id' => 70,
        //     'report_column' => 'total_profit',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Description of Supporting Documents',
        //     'table_id' => 70,
        //     'report_column' => 'description',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);

        /* Community Relations & Outreach Program */
        GenerateColumn::insert([
            'name' => 'Title of the Program',
            'table_id' => 70,
            'report_column' => 'title_of_the_program',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 70,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 70,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 70,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 70,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        /* Viable Demo Project */
        GenerateColumn::insert([
            'name' => 'Name of Viable Demonstration Project',
            'table_id' => 71,
            'report_column' => 'name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Revenue',
            'table_id' => 71,
            'report_column' => 'revenue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Cost',
            'table_id' => 71,
            'report_column' => 'cost',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Started',
            'table_id' => 71,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Internal Rate of Return',
            'table_id' => 71,
            'report_column' => 'rate_of_return',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 71,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);
        /* Awards Reputable Org */
        GenerateColumn::insert([
            'name' => 'Name of Award',
            'table_id' => 72,
            'report_column' => 'name_of_award',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Certifying Body',
            'table_id' => 72,
            'report_column' => 'certifying_body',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 72,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 72,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 72,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 72,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);


        // College Level
        /* Technical Ext. */
        // GenerateColumn::insert([
        //     'name' => 'Title of the Program',
        //     'table_id' => 74,
        //     'report_column' => 'program_title',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Title of the Project',
        //     'table_id' => 74,
        //     'report_column' => 'project_title',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Title of the Activity',
        //     'table_id' => 74,
        //     'report_column' => 'activity_title',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Name of the Adoptor',
        //     'table_id' => 74,
        //     'report_column' => 'Name of the Adoptor',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Classification of Adoptor',
        //     'table_id' => 74,
        //     'report_column' => 'classification_of_adoptor',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Nature of Business Enterprise',
        //     'table_id' => 74,
        //     'report_column' => 'nature_of_business_enterprise',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Adoptors have established profitable businesses in the last three years',
        //     'table_id' => 74,
        //     'report_column' => 'has_businesses',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Extension project by the university or borrowed from other institutions',
        //     'table_id' => 74,
        //     'report_column' => 'is_borrowed',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Total Profit/Income of the Adoptors',
        //     'table_id' => 74,
        //     'report_column' => 'total_profit',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);
        // GenerateColumn::insert([
        //     'name' => 'Description of Supporting Documents',
        //     'table_id' => 74,
        //     'report_column' => 'description',
        //     'is_active' => 1,
        //     'order' => 1
        // ]);

        /* Community Relations & Outreach Program */
        GenerateColumn::insert([
            'name' => 'Title of the Program',
            'table_id' => 74,
            'report_column' => 'title_of_the_program',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 74,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 74,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 74,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 74,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        /* Viable Demo Project */
        GenerateColumn::insert([
            'name' => 'Name of Viable Demonstration Project',
            'table_id' => 75,
            'report_column' => 'name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Revenue',
            'table_id' => 75,
            'report_column' => 'revenue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Cost',
            'table_id' => 75,
            'report_column' => 'cost',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Started',
            'table_id' => 75,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Internal Rate of Return',
            'table_id' => 75,
            'report_column' => 'rate_of_return',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 75,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);
        /* Awards Reputable Org */
        GenerateColumn::insert([
            'name' => 'Name of Award',
            'table_id' => 76,
            'report_column' => 'name_of_award',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Certifying Body',
            'table_id' => 76,
            'report_column' => 'certifying_body',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 76,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 76,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 76,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 76,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);
    }
}
