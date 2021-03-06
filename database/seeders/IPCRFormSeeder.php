<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\IPCRForm;

class IPCRFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IPCRForm::truncate();
        IPCRForm::insert([
            'label' => 'Request & Queries Acted Upon',
            'table_name' => 'requests',
            'is_active' => 1
        ]);
        IPCRForm::insert([
            'label' => 'Special Task (Admin)',
            'table_name' => 'admin_special_tasks',
            'is_active' => 1
        ]);
        IPCRForm::insert([
            'label' => 'Special Task (Academic) / Accomplishments Based on OPCR (Admin)',
            'table_name' => 'special_tasks',
            'is_active' => 1
        ]);
        IPCRForm::insert([
            'label' => 'Attendance in University and College Functions',
            'table_name' => 'attendance_functions',
            'is_active' => 1
        ]);
    }
}
