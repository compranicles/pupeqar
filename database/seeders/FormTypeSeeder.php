<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\FormType;

class FormTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FormType::create(['name' => 'QAR']);
        FormType::create(['name' => 'Non-QAR']);
    }
}
