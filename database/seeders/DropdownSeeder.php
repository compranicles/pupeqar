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
            IPCRDropdownSeeder::class,
            MoreDropdownSeeder::class,
            HRISDropdownSeeder::class,
            SpecialDropdownSeeder::class,
            ExtensionDropdownSeeder2::class,
        ]);

        //research discipline
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Discipline' //dropdown_id = 67
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Accounting and Finance',
            'order' => 1,
            'is_active' => 1,
        ]);

        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Arts and Sciences',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Business',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Computer Science and Information Technology',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Education',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Engineering and Architecture',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Political Science',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Public Administration and Law',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Social Sciences and Communication/ Technology',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Tourism, Hospitality, and Transportation Management',
            'order' => 1,
            'is_active' => 1,
        ]);
        
    }
}
