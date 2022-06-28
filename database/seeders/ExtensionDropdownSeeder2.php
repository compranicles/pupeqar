<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class ExtensionDropdownSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Classification of Person involved 
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Mobility Classification of Person Involved'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Faculty Employee',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Administrative Employee',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Students',
            'order' => 1,
            'is_active' => 1,
        ]);

        //Type of mobility
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Type of Mobility'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Inbound',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Outbound',
            'order' => 1,
            'is_active' => 1,
        ]);
    }
}
