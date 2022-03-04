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
        HRISForm::insert([
            'label' => 'Ongoing Advanced/Professional Study',
            'table_name' => '',
            'is_active' => 1
        ]);
        
        // HRISForm::insert([
        //     'label' => 'Ongoing Advanced/Professional Study',
        //     'table_name' => '',
        //     'is_active' => 1
        // ]);
    }
}
