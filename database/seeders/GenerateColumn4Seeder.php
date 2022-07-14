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
        /* ADMIN Technical Ext. */
        GenerateColumn::insert([
            'name' => 'Title of the Program',
            'table_id' => 33,
            'report_column' => 'program_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Title of the Project',
            'table_id' => 33,
            'report_column' => 'project_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Title of the Activity',
            'table_id' => 33,
            'report_column' => 'activity_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Name of the Adoptor',
            'table_id' => 33,
            'report_column' => 'name_of_adoptor',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Adoptor',
            'table_id' => 33,
            'report_column' => 'classification_of_adoptor',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Business Enterprise',
            'table_id' => 33,
            'report_column' => 'nature_of_business_enterprise',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Adoptors have established profitable businesses in the last three years',
            'table_id' => 33,
            'report_column' => 'has_businesses',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Extension project by the university or borrowed from other institutions',
            'table_id' => 33,
            'report_column' => 'is_borrowed',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Total Profit/Income of the Adoptors',
            'table_id' => 33,
            'report_column' => 'total_profit',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 33,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        /* ADMIN Community Relations & Outreach Program */
        GenerateColumn::insert([
            'name' => 'Title of the Program',
            'table_id' => 37,
            'report_column' => 'title_of_the_program',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 37,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 37,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 37,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 37,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        /* ADMIN Viable Demo Project */
        GenerateColumn::insert([
            'name' => 'Name of Viable Demonstration Project',
            'table_id' => 35,
            'report_column' => 'name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Revenue',
            'table_id' => 35,
            'report_column' => 'revenue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Cost',
            'table_id' => 35,
            'report_column' => 'cost',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Started',
            'table_id' => 35,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Internal Rate of Return',
            'table_id' => 35,
            'report_column' => 'rate_of_return',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 35,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        // ADMIN Community Engagement
        GenerateColumn::insert([
            'name' => 'List of Active Linkages/Partnerships Covered by MOA',
            'table_id' => 34,
            'report_column' => 'active_linkages',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Agro-industrial Technology',
            'table_id' => 34,
            'report_column' => 'classification_of_agro',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Inclusive Period',
            'table_id' => 34,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => '-',
            'table_id' => 34,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Partnership Coverage (LGU, Industry, SMEs (Small & Medium Enteprises), NGOs, and Pos)',
            'table_id' => 34,
            'report_column' => 'partnership_coverage',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 34,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //ADMIN Involvement in Inter-country Mobility
        GenerateColumn::insert([
            'name' => 'Classification of Person Involved (Faculty, Admin, Students)',
            'table_id' => 38,
            'report_column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Type (Inbound/ Outbound)',
            'table_id' => 38,
            'report_column' => 'type',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Engagement*',
            'table_id' => 38,
            'report_column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Mobility **',
            'table_id' => 38,
            'report_column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Host Institution/ Organization/ Agency',
            'table_id' => 38,
            'report_column' => 'host_name',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table_id' => 38,
            'report_column' => 'host_address',
            'is_active' => 1,
            'order' => 6,
        ]);
        GenerateColumn::insert([
            'name' => 'Collaborating Country and Institution/Organization/Agency',
            'table_id' => 38,
            'report_column' => 'collaborating_country',
            'is_active' => 1,
            'order' => 7,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Inter-Country Mobility',
            'table_id' => 38,
            'report_column' => 'mobility_description',
            'is_active' => 0,
            'order' => 8,
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 38,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 9,
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 38,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 10,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 38,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 11,
        ]);

        //ADMIN Involvement in Intra-country Mobility
        GenerateColumn::insert([
            'name' => 'Classification of Person Involved (Faculty, Admin, Students)',
            'table_id' => 39,
            'report_column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Type (Inbound/ Outbound)',
            'table_id' => 39,
            'report_column' => 'type',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Engagement*',
            'table_id' => 39,
            'report_column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Mobility **',
            'table_id' => 39,
            'report_column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Host Institution/ Organization/ Agency',
            'table_id' => 39,
            'report_column' => 'host_name',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table_id' => 39,
            'report_column' => 'host_address',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Collaborating Country and Institution/Organization/Agency',
            'table_id' => 39,
            'report_column' => 'collaborating_country',
            'is_active' => 1,
            'order' => 6,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Intra-Country Mobility',
            'table_id' => 39,
            'report_column' => 'mobility_description',
            'is_active' => 0,
            'order' => 7,
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 39,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 8,
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 39,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 9,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 39,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 10,
        ]);

        /* Academic Awards Reputable Org */
        GenerateColumn::insert([
            'name' => 'Name of Award',
            'table_id' => 78,
            'report_column' => 'name_of_award',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Certifying Body',
            'table_id' => 78,
            'report_column' => 'certifying_body',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 78,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 78,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 78,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 78,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //ADMIN H. Other Accomplishments
        GenerateColumn::insert([
            'name' => 'Brief Description of Accomplishment',
            'table_id' => 40,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Inclusive Date',
            'table_id' => 40,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => '-',
            'table_id' => 40,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 40,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 40,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 40,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 5,
        ]);

        /* ACADEMIC A. Technical Ext. */
        GenerateColumn::insert([
            'name' => 'Title of the Program',
            'table_id' => 75,
            'report_column' => 'program_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Title of the Project',
            'table_id' => 75,
            'report_column' => 'project_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Title of the Activity',
            'table_id' => 75,
            'report_column' => 'activity_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Name of the Adoptor',
            'table_id' => 75,
            'report_column' => 'name_of_adoptor',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Adoptor',
            'table_id' => 75,
            'report_column' => 'classification_of_adoptor',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Business Enterprise',
            'table_id' => 75,
            'report_column' => 'nature_of_business_enterprise',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Adoptors have established profitable businesses in the last three years',
            'table_id' => 75,
            'report_column' => 'has_businesses',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Extension project by the university or borrowed from other institutions',
            'table_id' => 75,
            'report_column' => 'is_borrowed',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Total Profit/Income of the Adoptors',
            'table_id' => 75,
            'report_column' => 'total_profit',
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

        // ACADEMIC Community Engagement
        GenerateColumn::insert([
            'name' => 'List of Active Linkages/Partnerships Covered by MOA',
            'table_id' => 76,
            'report_column' => 'active_linkages',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Agro-industrial Technology',
            'table_id' => 76,
            'report_column' => 'classification_of_agro',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Inclusive Period',
            'table_id' => 76,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => '-',
            'table_id' => 76,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Partnership Coverage (LGU, Industry, SMEs (Small & Medium Enteprises), NGOs, and Pos)',
            'table_id' => 76,
            'report_column' => 'partnership_coverage',
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

        //ACADEMIC F. Inter-country Mobility
            GenerateColumn::insert([
            'name' => 'Classification of Person Involved (Faculty, Admin, Students)',
            'table_id' => 80,
            'report_column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Type (Inbound/ Outbound)',
            'table_id' => 80,
            'report_column' => 'type',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Engagement*',
            'table_id' => 80,
            'report_column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Mobility **',
            'table_id' => 80,
            'report_column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Host Institution/ Organization/ Agency',
            'table_id' => 80,
            'report_column' => 'host_name',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table_id' => 80,
            'report_column' => 'host_address',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Collaborating Country and Institution/Organization/Agency',
            'table_id' => 80,
            'report_column' => 'collaborating_country',
            'is_active' => 1,
            'order' => 6,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Inter-Country Mobility',
            'table_id' => 80,
            'report_column' => 'mobility_description',
            'is_active' => 0,
            'order' => 7,
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 80,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 8,
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 80,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 9,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 38,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 10,
        ]);

        //ACADEMIC G. Intra-country Mobility
        GenerateColumn::insert([
            'name' => 'Classification of Person Involved (Faculty, Admin, Students)',
            'table_id' => 81,
            'report_column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Type (Inbound/ Outbound)',
            'table_id' => 81,
            'report_column' => 'type',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Engagement*',
            'table_id' => 81,
            'report_column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Mobility **',
            'table_id' => 81,
            'report_column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Host Institution/ Organization/ Agency',
            'table_id' => 81,
            'report_column' => 'host_name',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table_id' => 81,
            'report_column' => 'host_address',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Collaborating Country and Institution/Organization/Agency',
            'table_id' => 81,
            'report_column' => 'collaborating_country',
            'is_active' => 1,
            'order' => 6,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Intra-Country Mobility',
            'table_id' => 81,
            'report_column' => 'mobility_description',
            'is_active' => 0,
            'order' => 7,
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 81,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 8,
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 81,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 9,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 81,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 10,
        ]);

        //ACADEMIC H. Other Accomplishments
        GenerateColumn::insert([
            'name' => 'Brief Description of Accomplishment',
            'table_id' => 82,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Inclusive Date',
            'table_id' => 82,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => '-',
            'table_id' => 82,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 82,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 82,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 82,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 6,
        ]);

        /* COLLEGE/DEPARTMENT LEVEL ACCOMPLISHMENTS */
        //A. Technical Extensions
        GenerateColumn::insert([
            'name' => 'Title of the Program',
            'table_id' => 84,
            'report_column' => 'program_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Title of the Project',
            'table_id' => 84,
            'report_column' => 'project_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Title of the Activity',
            'table_id' => 84,
            'report_column' => 'activity_title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Name of the Adoptor',
            'table_id' => 84,
            'report_column' => 'name_of_adoptor',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Adoptor',
            'table_id' => 84,
            'report_column' => 'classification_of_adoptor',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Business Enterprise',
            'table_id' => 84,
            'report_column' => 'nature_of_business_enterprise',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Adoptors have established profitable businesses in the last three years',
            'table_id' => 84,
            'report_column' => 'has_businesses',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Extension project by the university or borrowed from other institutions',
            'table_id' => 84,
            'report_column' => 'is_borrowed',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Total Profit/Income of the Adoptors',
            'table_id' => 84,
            'report_column' => 'total_profit',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 84,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        // B. Community Engagement
        GenerateColumn::insert([
            'name' => 'List of Active Linkages/Partnerships Covered by MOA',
            'table_id' => 85,
            'report_column' => 'active_linkages',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Agro-industrial Technology',
            'table_id' => 85,
            'report_column' => 'classification_of_agro',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Inclusive Period',
            'table_id' => 85,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => '-',
            'table_id' => 85,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Partnership Coverage (LGU, Industry, SMEs (Small & Medium Enteprises), NGOs, and Pos)',
            'table_id' => 85,
            'report_column' => 'partnership_coverage',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 85,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        /* C. Viable Demo Project */
        GenerateColumn::insert([
            'name' => 'Name of Viable Demonstration Project',
            'table_id' => 86,
            'report_column' => 'name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Revenue',
            'table_id' => 86,
            'report_column' => 'revenue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Cost',
            'table_id' => 86,
            'report_column' => 'cost',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Started',
            'table_id' => 86,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Internal Rate of Return',
            'table_id' => 86,
            'report_column' => 'rate_of_return',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 86,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        /* D. Awards Reputable Org */
        GenerateColumn::insert([
            'name' => 'Name of Award',
            'table_id' => 87,
            'report_column' => 'name_of_award',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Certifying Body',
            'table_id' => 87,
            'report_column' => 'certifying_body',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 87,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 87,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 87,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 87,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        /* E. Community Relations & Outreach Program */
        GenerateColumn::insert([
            'name' => 'Title of the Program',
            'table_id' => 88,
            'report_column' => 'title_of_the_program',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 88,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date',
            'table_id' => 88,
            'report_column' => 'date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 88,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 88,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        // F. Inter-country Mobility
        GenerateColumn::insert([
            'name' => 'Classification of Person Involved (Faculty, Admin, Students)',
            'table_id' => 89,
            'report_column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Type (Inbound/ Outbound)',
            'table_id' => 89,
            'report_column' => 'type',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Engagement*',
            'table_id' => 89,
            'report_column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Mobility **',
            'table_id' => 89,
            'report_column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Host Institution/ Organization/ Agency',
            'table_id' => 89,
            'report_column' => 'host_name',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table_id' => 89,
            'report_column' => 'host_address',
            'is_active' => 1,
            'order' => 6,
        ]);
        GenerateColumn::insert([
            'name' => 'Collaborating Country and Institution/Organization/Agency',
            'table_id' => 89,
            'report_column' => 'collaborating_country',
            'is_active' => 1,
            'order' => 7,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Inter-Country Mobility',
            'table_id' => 89,
            'report_column' => 'mobility_description',
            'is_active' => 0,
            'order' => 8,
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 89,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 9,
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 89,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 10,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 89,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 11,
        ]);

        //G. Intra-country Mobility
        GenerateColumn::insert([
            'name' => 'Classification of Person Involved (Faculty, Admin, Students)',
            'table_id' => 90,
            'report_column' => 'classification_of_person',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Type (Inbound/ Outbound)',
            'table_id' => 90,
            'report_column' => 'type',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Nature of Engagement*',
            'table_id' => 90,
            'report_column' => 'nature_of_engagement',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Classification of Mobility **',
            'table_id' => 90,
            'report_column' => 'classification_of_mobility',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Host Institution/ Organization/ Agency',
            'table_id' => 90,
            'report_column' => 'host_name',
            'is_active' => 1,
            'order' => 5,
        ]);
        GenerateColumn::insert([
            'name' => 'Address of Host Institution/ Organization/ Agency',
            'table_id' => 90,
            'report_column' => 'host_address',
            'is_active' => 1,
            'order' => 6,
        ]);
        GenerateColumn::insert([
            'name' => 'Collaborating Country and Institution/Organization/Agency',
            'table_id' => 90,
            'report_column' => 'collaborating_country',
            'is_active' => 1,
            'order' => 7,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Intra-Country Mobility',
            'table_id' => 90,
            'report_column' => 'mobility_description',
            'is_active' => 0,
            'order' => 8,
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 90,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 9,
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 90,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 10,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 90,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 11,
        ]);

        //H. Other Accomplishments
        GenerateColumn::insert([
            'name' => 'Brief Description of Accomplishment',
            'table_id' => 91,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Inclusive Date',
            'table_id' => 91,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => '-',
            'table_id' => 91,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 91,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 91,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 91,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1,
        ]);

        //ACADEMIC IV. Other Accomplishments
        GenerateColumn::insert([
            'name' => 'Brief Description of Accomplishment',
            'table_id' => 72,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => 'Inclusive Date',
            'table_id' => 72,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1,
        ]);
        GenerateColumn::insert([
            'name' => '-',
            'table_id' => 72,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 2,
        ]);
        GenerateColumn::insert([
            'name' => 'Place',
            'table_id' => 72,
            'report_column' => 'place',
            'is_active' => 1,
            'order' => 3,
        ]);
        GenerateColumn::insert([
            'name' => 'Level',
            'table_id' => 72,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 4,
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 72,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 5,
        ]);

        //ADMIN Accomplshments Based on OPCR
        GenerateColumn::insert([
            'name' => 'Final Output - Commitment Measurable by Quality',
            'table_id' => 8,
            'report_column' => 'final_output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Target',
            'table_id' => 8,
            'report_column' => 'target_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Actual',
            'table_id' => 8,
            'report_column' => 'actual_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Accomplishment',
            'table_id' => 8,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status',
            'table_id' => 8,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Remarks',
            'table_id' => 8,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 8,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Efficiency OPCR
        GenerateColumn::insert([
            'name' => 'Final Output - Commitment Measurable by Efficiency',
            'table_id' => 9,
            'report_column' => 'final_output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Target',
            'table_id' => 9,
            'report_column' => 'target_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Actual',
            'table_id' => 9,
            'report_column' => 'actual_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Accomplishment',
            'table_id' => 9,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status',
            'table_id' => 9,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Remarks',
            'table_id' => 9,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 9,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Timeliness
        GenerateColumn::insert([
            'name' => 'Final Output - Commitment Measurable by Timeliness',
            'table_id' => 10,
            'report_column' => 'final_output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Target',
            'table_id' => 10,
            'report_column' => 'target_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Actual',
            'table_id' => 10,
            'report_column' => 'actual_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Accomplishment',
            'table_id' => 10,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status',
            'table_id' => 10,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Remarks',
            'table_id' => 10,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 10,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //ADMIN Attendance in University Function
        GenerateColumn::insert([
            'name' => 'Brief Description of Activity',
            'table_id' => 11,
            'report_column' => 'activity_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification',
            'table_id' => 11,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Started (mm/dd/yyyy)',
            'table_id' => 11,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Completed (mm/dd/yyyy)',
            'table_id' => 11,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status of Attendance (Attended/ Not Attended)',
            'table_id' => 11,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Proof of Attendance',
            'table_id' => 11,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);
    }
}
