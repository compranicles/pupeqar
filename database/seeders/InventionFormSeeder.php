<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\InventionForm;

class InventionFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InventionForm::truncate();
        InventionForm::insert([
            'label' => 'Faculty Invention, Innovation and Creative Works Commitment',
            'table_name' => 'inventions',
            'is_active' => 1
        ]);
    }
}
