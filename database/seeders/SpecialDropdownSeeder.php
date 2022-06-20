<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class SpecialDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Level (ASP)'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'International',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'National',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Local',
            'order' => 1,
            'is_active' => 1,
        ]);

        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Commitment Measurable by: '
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Quality',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Efficiency',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Timeliness',
            'order' => 1,
            'is_active' => 1,
        ]);

        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Status (Special Tasks) '
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Ongoing',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Deferred',
            'order' => 1,
            'is_active' => 1,
        ]);


        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Status (Attendance in Functions) '
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Attended',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Not Attended',
            'order' => 1,
            'is_active' => 1,
        ]);

        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Classification (Attendance in Functions)'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'University',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'College/ Office',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Department/ Section',
            'order' => 1,
            'is_active' => 1,
        ]);
    }
}
