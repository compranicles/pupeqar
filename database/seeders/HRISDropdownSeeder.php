<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class HRISDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Program Accreditation Level/ World Ranking/ COE or COD'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Level I',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Level II',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Level III',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Level IV',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'COD',
            'order' => 5,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'COE',
            'order' => 6,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Top 1000 University Ranking',
            'order' => 7,
            'is_active' => 1,
        ]);

        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Type of Support (Ongoing Advanced/Professional Study)'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Financial Aid',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Scholarship',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Tuition Fee Discount',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Self-Funded',
            'order' => 4,
            'is_active' => 1,
        ]);

        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Status (Ongoing Advanced/Professional Study)'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Currently Enrolled (New Student)',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Currently Enrolled (Old Student)',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Leave of Absence',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed Academic Requirement',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Passed Comprehensive Exam',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Currently Enrolled for Thesis Writing',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Currently Enrolled for Dissertation Writing',
            'order' => 4,
            'is_active' => 1,
        ]);

        
    }
}
