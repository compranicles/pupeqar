<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\GenerateColumn;

class GenerateColumnHRISSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Ongoing Advanced/Professional Study
        //Admin
        GenerateColumn::create([
            'name' => 'Degree/Program',
            'table_id' => 2,
            'report_column' => 'degree',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Name of School',
            'table_id' => 2,
            'report_column' => 'school_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Program Accreditation Level/ World Ranking/ COE or COD*',
            'table_id' => 2,
            'report_column' => 'program_level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Type of Support**',
            'table_id' => 2,
            'report_column' => 'support_type',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Name of Sponsor/Agency/Organization',
            'table_id' => 2,
            'report_column' => 'sponsor_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Amount',
            'table_id' => 2,
            'report_column' => 'amount',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 2,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 2,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status***',
            'table_id' => 2,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Number of Units Earned',
            'table_id' => 2,
            'report_column' => 'units_earned',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Number of Units Currently Enrolled',
            'table_id' => 2,
            'report_column' => 'units_enrolled',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents',
            'table_id' => 2,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Academic
        GenerateColumn::create([
            'name' => 'Degree/Program',
            'table_id' => 35,
            'report_column' => 'degree',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Name of School',
            'table_id' => 35,
            'report_column' => 'school_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Program Accreditation Level/ World Ranking/ COE or COD*',
            'table_id' => 35,
            'report_column' => 'program_level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Type of Support**',
            'table_id' => 35,
            'report_column' => 'support_type',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Name of Sponsor/Agency/Organization',
            'table_id' => 35,
            'report_column' => 'sponsor_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Amount',
            'table_id' => 35,
            'report_column' => 'amount',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 35,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 35,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status***',
            'table_id' => 35,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Number of Units Earned',
            'table_id' => 35,
            'report_column' => 'units_earned',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Number of Units Currently Enrolled',
            'table_id' => 35,
            'report_column' => 'units_enrolled',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents',
            'table_id' => 35,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //outstanding achievement/awards
        //admin
        GenerateColumn::create([
            'name' => 'Awards of distinction received in recognition of achievement in relevant areas of specialization/profession and/or assignment of Administrative Employee concerned',
            'table_id' => 4,
            'report_column' => 'award_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Classification*',
            'table_id' => 4,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Award Giving Body',
            'table_id' => 4,
            'report_column' => 'award_body',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Level**',
            'table_id' => 4,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Venue',
            'table_id' => 4,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 4,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 4,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 4,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);


        //academic
        GenerateColumn::create([
            'name' => 'Awards of distinction received in recognition of achievement in relevant areas of specialization/profession and/or assignment of faculty concerned',
            'table_id' => 37,
            'report_column' => 'award_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Classification*',
            'table_id' => 37,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Award Giving Body',
            'table_id' => 37,
            'report_column' => 'award_body',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Level**',
            'table_id' => 37,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Venue',
            'table_id' => 37,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 37,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 37,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 37,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //officership/membership in professional organizations
        GenerateColumn::create([
            'name' => 'Name of the Organization',
            'table_id' => 37,
            'report_column' => 'organization_name',
            'is_active' => 1,
            'order' => 1
        ]);

    }
}