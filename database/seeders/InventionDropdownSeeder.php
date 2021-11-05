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
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Invention',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Innovation',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Creative Works',
            'order' => 2,
            'is_active' => 1,
        ]);
        
        //funding type
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Invention Type of Funding'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'University Funded',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Self Funded',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Externally Funded',
            'order' => 2,
            'is_active' => 1,
        ]);

        //status
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Invention Status'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'New Commitment',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Ongoing',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Deferred',
            'order' => 2,
            'is_active' => 1,
        ]);
    }
}
