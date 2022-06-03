<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class IPCRDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Request Category'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Simple',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Complex',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Highly Technical',
            'order' => 1,
            'is_active' => 1,
        ]);

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
    }
}
