<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class DropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dropdown::truncate();
        DropdownOption::truncate();

        $this->call([
            ResearchDropdownSeeder::class,
            InventionDropdownSeeder::class,
            ExtensionDropdownSeeder::class,
            AcademicDevelopmentDropdownSeeder::class,
        ]);
    }
}
