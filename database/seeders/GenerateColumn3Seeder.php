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
        GenerateColumn::insert([
            'name' => 'Final Output - Commitment Measurable by Quality',
            'table_id' => 68,
            'report_column' => 'final_output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Target',
            'table_id' => 68,
            'report_column' => 'target_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Actual',
            'table_id' => 68,
            'report_column' => 'actual_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Accomplishment',
            'table_id' => 68,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status',
            'table_id' => 68,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Remarks',
            'table_id' => 68,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 68,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Academic Special TASKS 2
        GenerateColumn::insert([
            'name' => 'Final Output - Commitment Measurable by Efficiency',
            'table_id' => 69,
            'report_column' => 'final_output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Target',
            'table_id' => 69,
            'report_column' => 'target_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Actual',
            'table_id' => 69,
            'report_column' => 'actual_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Accomplishment',
            'table_id' => 69,
            'report_column' => 'accomplishment_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status',
            'table_id' => 69,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Remarks',
            'table_id' => 69,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 69,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Academic Special TASKS 3
        GenerateColumn::insert([
            'name' => 'Final Output - Commitment Measurable by Timeliness',
            'table_id' => 70,
            'report_column' => 'final_output',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Target',
            'table_id' => 70,
            'report_column' => 'target_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Actual',
            'table_id' => 70,
            'report_column' => 'actual_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Accomplishment',
            'table_id' => 70,
            'report_column' => 'accomp_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status',
            'table_id' => 70,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Remarks',
            'table_id' => 70,
            'report_column' => 'remarks',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Description of Supporting Documents Submitted',
            'table_id' => 70,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);

        //Attendance in University Function Academic
        GenerateColumn::insert([
            'name' => 'Brief Description of Activity',
            'table_id' => 71,
            'report_column' => 'activity_description',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Classification',
            'table_id' => 71,
            'report_column' => 'classification',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Started (mm/dd/yyyy)',
            'table_id' => 71,
            'report_column' => 'start_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Date Completed (mm/dd/yyyy)',
            'table_id' => 71,
            'report_column' => 'end_date',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Status of Attendance (Attended/ Not Attended)',
            'table_id' => 71,
            'report_column' => 'status',
            'is_active' => 1,
            'order' => 1
        ]);
        GenerateColumn::insert([
            'name' => 'Proof of Attendance',
            'table_id' => 71,
            'report_column' => 'description',
            'is_active' => 1,
            'order' => 1
        ]);
    }
}
