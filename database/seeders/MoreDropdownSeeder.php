<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\DropdownOption;

class MoreDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Research Agenda
        DropdownOption::create([
            'dropdown_id' => 3,
            'name' => 'Peace and Security',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => 3,
            'name' => 'Protection and Rehabilitation towards Sustainable Development',
            'order' => 6,
            'is_active' => 1,
        ]);
        
        // Involvement
        DropdownOption::create([ 
            'dropdown_id' => 4,
            'name' => 'Independent Researcher',
            'order' => 1,
            'is_active' => 1,
        ]);
        
        // pRESEENTATION
        DropdownOption::create([
            'dropdown_id' => 8,
            'name' => 'Local',
            'order' => 1,
            'is_active' => 1,
        ]);

        // Consultant Level
        DropdownOption::create([
            'dropdown_id' => 16,
            'name' => 'Provincial/City/Municipal',
            'order' => 4,
            'is_active' => 1,
        ]);

        // CONFERENCE Level
        DropdownOption::create([
            'dropdown_id' => 18,
            'name' => 'Provincial/City/Municipal',
            'order' => 4,
            'is_active' => 1,
        ]);
        // academic Level
        DropdownOption::create([
            'dropdown_id' => 22,
            'name' => 'Provincial/City/Municipal',
            'order' => 4,
            'is_active' => 1,
        ]);
        // extension services Level
        DropdownOption::create([
            'dropdown_id' => 23,
            'name' => 'Provincial/City/Municipal',
            'order' => 4,
            'is_active' => 1,
        ]);
        // deliverable/des
        DropdownOption::create([
            'dropdown_id' => 32,
            'name' => 'Education and Communication',
            'order' => 4,
            'is_active' => 1,
        ]);
        //classification of beneficiaries
        DropdownOption::create([
            'dropdown_id' => 29,
            'name' => 'Administrative Employee',
            'order' => 1,
            'is_active' => 1,
        ]);
    }
}
