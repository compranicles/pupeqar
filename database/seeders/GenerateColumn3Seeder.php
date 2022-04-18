<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\GenerateColumn;

class GenerateColumn3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Academic Special TASKS 1
        GenerateColumn::create([
            'name' => 'Final Output - Commitment Measurable by Quality',
            'table_id' => 60,
            'report_column' => 'output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Target',
            'table_id' => 60,
            'report_column' => 'target',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Actual',
            'table_id' => 60,
            'report_column' => 'actual',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Accomplishment',
            'table_id' => 60,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status',
            'table_id' => 60,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Remarks',
            'table_id' => 60,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 60,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Academic Special TASKS 2
        GenerateColumn::create([
            'name' => 'Final Output - Commitment Measurable by Efficiency',
            'table_id' => 61,
            'report_column' => 'output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Target',
            'table_id' => 61,
            'report_column' => 'target',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Actual',
            'table_id' => 61,
            'report_column' => 'actual',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Accomplishment',
            'table_id' => 61,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status',
            'table_id' => 61,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Remarks',
            'table_id' => 61,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 61,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Academic Special TASKS 3
        GenerateColumn::create([
            'name' => 'Final Output - Commitment Measurable by Timeliness',
            'table_id' => 62,
            'report_column' => 'output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Target',
            'table_id' => 62,
            'report_column' => 'target',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Actual',
            'table_id' => 62,
            'report_column' => 'actual',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Accomplishment',
            'table_id' => 62,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status',
            'table_id' => 62,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Remarks',
            'table_id' => 62,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 62,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Attendance in University Function Academic
        GenerateColumn::create([
            'name' => 'Brief Description of Activity',
            'table_id' => 63,
            'report_column' => 'activity_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Date Started (mm/dd/yyyy)',
            'table_id' => 63,
            'report_column' => 'date_started',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Date Completed (mm/dd/yyyy)',
            'table_id' => 63,
            'report_column' => 'date_completed',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status of Attendance (Attended/ Not Attended)',
            'table_id' => 63,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Proof of Attendance',
            'table_id' => 63,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Admin Accomplishment Based on OPCR 1
        GenerateColumn::create([
            'name' => 'Final Output - Commitment Measurable by Quality',
            'table_id' => 8,
            'report_column' => 'output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Target',
            'table_id' => 8,
            'report_column' => 'target',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Actual',
            'table_id' => 8,
            'report_column' => 'actual',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Accomplishment',
            'table_id' => 8,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status',
            'table_id' => 8,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Remarks',
            'table_id' => 8,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 8,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Admin Accomplishment Based on OPCR 1
        GenerateColumn::create([
            'name' => 'Final Output - Commitment Measurable by Efficiency',
            'table_id' => 9,
            'report_column' => 'output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Target',
            'table_id' => 9,
            'report_column' => 'target',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Actual',
            'table_id' => 9,
            'report_column' => 'actual',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Accomplishment',
            'table_id' => 9,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status',
            'table_id' => 9,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Remarks',
            'table_id' => 9,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 9,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Admin Accomplishment Based on OPCR 1v
        GenerateColumn::create([
            'name' => 'Final Output - Commitment Measurable by Timeliness',
            'table_id' => 10,
            'report_column' => 'output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Target',
            'table_id' => 10,
            'report_column' => 'target',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Actual',
            'table_id' => 10,
            'report_column' => 'actual',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Accomplishment',
            'table_id' => 10,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status',
            'table_id' => 10,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Remarks',
            'table_id' => 10,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 10,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Attendance in University Function admin
        GenerateColumn::create([
            'name' => 'Brief Description of Activity',
            'table_id' => 11,
            'report_column' => 'activity_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Date Started (mm/dd/yyyy)',
            'table_id' => 11,
            'report_column' => 'date_started',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Date Completed (mm/dd/yyyy)',
            'table_id' => 11,
            'report_column' => 'date_completed',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Status of Attendance (Attended/ Not Attended)',
            'table_id' => 11,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Proof of Attendance',
            'table_id' => 11,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Special Tasks Admin
        GenerateColumn::create([
            'name' => 'Brief Description of Accomplishment',
            'table_id' => 13,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Output',
            'table_id' => 13,
            'report_column' => 'output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Outcome',
            'table_id' => 13,
            'report_column' => 'outcome',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Participation/Significant Contribution',
            'table_id' => 13,
            'report_column' => 'contribution',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Special Order',
            'table_id' => 13,
            'report_column' => 'special_order',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Level',
            'table_id' => 13,
            'report_column' => 'level',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Nature of Accomplishment',
            'table_id' => 13,
            'report_column' => 'nature',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'From (mm/dd/yyyy)',
            'table_id' => 13,
            'report_column' => 'from',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'To (mm/dd/yyyy)',
            'table_id' => 13,
            'report_column' => 'to',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::create([
            'name' => 'Proof of Compliance',
            'table_id' => 13,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);
    }
}