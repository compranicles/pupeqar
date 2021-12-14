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
    }
}
