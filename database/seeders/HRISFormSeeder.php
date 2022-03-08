<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\HRISForm;

class HRISFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HRISForm::truncate();

        HRISForm::insert([
            'label' => 'Ongoing Advanced/Professional Study',
            'table_name' => '',
            'is_active' => 1
        ]);
        
        HRISForm::insert([
            'label' => 'Outstanding Achievements/Awards',
            'table_name' => '',
            'is_active' => 1
        ]);

        HRISForm::insert([
            'label' => 'Officership/ Membership in Professional Organization/s',
            'table_name' => '',
            'is_active' => 1
        ]);

        HRISForm::insert([
            'label' => 'Attendance in Relevant Development Program',
            'table_name' => '',
            'is_active' => 1
        ]);

        HRISForm::insert([
            'label' => 'Attendance in Training/s',
            'table_name' => '',
            'is_active' => 1
        ]);
    }
}
