<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class AcademicDevelopmentDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Reference - category
        $dropdownId  = Dropdown::insertGetId([
            'name' => ' Reference Category'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Instructional Material',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Module',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Monograph',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Textbook/Reference',
            'order' => 4,
            'is_active' => 1,
        ]);

        //Reference - level
        $dropdownId  = Dropdown::insertGetId([
            'name' => ' Reference Level'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'International',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'National',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Regional',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Provincial, City or Municipal',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Local-PUP',
            'order' => 5,
            'is_active' => 1,
        ]);

        //Syllabus - assigned task
        $dropdownId  = Dropdown::insertGetId([
            'name' => ' Syllabus Assigned Task'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'To develop',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'To enhance',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'To revise',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'To review',
            'order' => 4,
            'is_active' => 1,
        ]);
    }
}
