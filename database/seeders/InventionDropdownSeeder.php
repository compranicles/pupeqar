<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class InventionDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //classification
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Invention Classification'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Invention',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Innovation',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Creative Work',
            'order' => 3,
            'is_active' => 1,
        ]);
        
        //funding type
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Invention Type of Funding'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'University Funded',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Self Funded',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Externally Funded',
            'order' => 2,
            'is_active' => 1,
        ]);

        //status
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Invention Status'
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'New Commitment',
            'order' => 1,
            'is_active' => 0,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Ongoing',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::insert([
            'dropdown_id' => $dropdownId,
            'name' => 'Deferred',
            'order' => 2,
            'is_active' => 1,
        ]);
    }
}
