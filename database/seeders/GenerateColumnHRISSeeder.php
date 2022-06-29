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
        GenerateColumn::insert([
            'name' => 'Degree/Program',
            'table_id' => 2,
            'report_column' => 'degree',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Name of School',
            'table_id' => 2,
            'report_column' => 'school_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Program Accreditation Level/ World Ranking/ COE or COD*',
            'table_id' => 2,
            'report_column' => 'program_level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Type of Support**',
            'table_id' => 2,
            'report_column' => 'support_type',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Name of Sponsor/Agency/Organization',
            'table_id' => 2,
            'report_column' => 'sponsor_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Amount',
            'table_id' => 2,
            'report_column' => 'amount',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 2,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 2,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status***',
            'table_id' => 2,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Number of Units Earned',
            'table_id' => 2,
            'report_column' => 'units_earned',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Number of Units Currently Enrolled',
            'table_id' => 2,
            'report_column' => 'units_enrolled',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 2,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //ACADEMIC Ongoing Advanced Prof. Study
        GenerateColumn::insert([
            'name' => 'Degree/Program',
            'table_id' => 42,
            'report_column' => 'degree',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Name of School',
            'table_id' => 42,
            'report_column' => 'school_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Program Accreditation Level/ World Ranking/ COE or COD*',
            'table_id' => 42,
            'report_column' => 'program_level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Type of Support**',
            'table_id' => 42,
            'report_column' => 'support_type',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Name of Sponsor/Agency/Organization',
            'table_id' => 42,
            'report_column' => 'sponsor_name',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Amount',
            'table_id' => 42,
            'report_column' => 'amount',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 42,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 42,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status***',
            'table_id' => 42,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Number of Units Earned',
            'table_id' => 42,
            'report_column' => 'units_earned',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Number of Units Currently Enrolled',
            'table_id' => 42,
            'report_column' => 'units_enrolled',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents',
            'table_id' => 42,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //outstanding achievement/awards
        //admin
        GenerateColumn::insert([
            'name' => 'Awards of distinction received in recognition of achievement in relevant areas of specialization/profession and/or assignment of Administrative Employee concerned',
            'table_id' => 4,
            'report_column' => 'award',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 4,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Award Giving Body',
            'table_id' => 4,
            'report_column' => 'awarded_by',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level**',
            'table_id' => 4,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Venue',
            'table_id' => 4,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 4,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 4,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 4,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //ACADEMIC Outstanding Achievement/Awards
        GenerateColumn::insert([
            'name' => 'Awards of distinction received in recognition of achievement in relevant areas of specialization/profession and/or assignment of Administrative Employee concerned',
            'table_id' => 44,
            'report_column' => 'award',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 44,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Award Giving Body',
            'table_id' => 44,
            'report_column' => 'awarded_by',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level**',
            'table_id' => 44,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Venue',
            'table_id' => 44,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 44,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 44,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 44,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //officership/membership in professional organizations
        //academic
        GenerateColumn::insert([
            'name' => 'Name of the Organization',
            'table_id' => 45,
            'report_column' => 'organization',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 45,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Position',
            'table_id' => 45,
            'report_column' => 'position',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level**',
            'table_id' => 45,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Organization Address',
            'table_id' => 45,
            'report_column' => 'organization_address',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 45,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 45,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 45,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //officership/membership in professional organizations
        //ADMIN
        GenerateColumn::insert([
            'name' => 'Name of the Organization',
            'table_id' => 5,
            'report_column' => 'organization',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 5,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Position',
            'table_id' => 5,
            'report_column' => 'position',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level**',
            'table_id' => 5,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Organization Address',
            'table_id' => 5,
            'report_column' => 'organization_address',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 5,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 5,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 5,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Seminar/Webinars
        //Academic
        GenerateColumn::insert([
            'name' => 'Title',
            'table_id' => 46,
            'report_column' => 'title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 46,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Nature**',
            'table_id' => 46,
            'report_column' => 'nature',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Budget',
            'table_id' => 46,
            'report_column' => 'budget',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Source of Fund***',
            'table_id' => 46,
            'report_column' => 'fund_source',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Organizer',
            'table_id' => 46,
            'report_column' => 'organizer',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level****',
            'table_id' => 46,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Venue',
            'table_id' => 46,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 46,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 46,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Total No. of Hours',
            'table_id' => 46,
            'report_column' => 'total_hours',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 46,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Seminar/Webinars Attendance
        //ADMIN
        GenerateColumn::insert([
            'name' => 'Title',
            'table_id' => 6,
            'report_column' => 'title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 6,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Nature**',
            'table_id' => 6,
            'report_column' => 'nature',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Budget',
            'table_id' => 6,
            'report_column' => 'budget',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Source of Fund***',
            'table_id' => 6,
            'report_column' => 'fund_source',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Organizer',
            'table_id' => 6,
            'report_column' => 'organizer',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level****',
            'table_id' => 6,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Venue',
            'table_id' => 6,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 6,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 6,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Total No. of Hours',
            'table_id' => 6,
            'report_column' => 'total_hours',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 6,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //ACADEMIC Attendance in Trainings
        GenerateColumn::insert([
            'name' => 'Title',
            'table_id' => 47,
            'report_column' => 'title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 47,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Nature**',
            'table_id' => 47,
            'report_column' => 'nature',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Budget',
            'table_id' => 47,
            'report_column' => 'budget',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Source of Fund***',
            'table_id' => 47,
            'report_column' => 'fund_source',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Organizer',
            'table_id' => 47,
            'report_column' => 'organizer',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level****',
            'table_id' => 47,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Venue',
            'table_id' => 47,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 47,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 47,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Total No. of Hours',
            'table_id' => 47,
            'report_column' => 'total_hours',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 47,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //ADMIN Attendance in Trainings
        GenerateColumn::insert([
            'name' => 'Title',
            'table_id' => 7,
            'report_column' => 'title',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification*',
            'table_id' => 7,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Nature**',
            'table_id' => 7,
            'report_column' => 'nature',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Budget',
            'table_id' => 7,
            'report_column' => 'budget',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Source of Fund***',
            'table_id' => 7,
            'report_column' => 'fund_source',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Organizer',
            'table_id' => 7,
            'report_column' => 'organizer',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Level****',
            'table_id' => 7,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Venue',
            'table_id' => 7,
            'report_column' => 'venue',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 7,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 7,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Total No. of Hours',
            'table_id' => 7,
            'report_column' => 'total_hours',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted (MOA/MOU, Certificate of Recognitions/Appreciations)',
            'table_id' => 7,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);
    }
}
