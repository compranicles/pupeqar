<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class ResearchDropdownSeeder extends Seeder
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
            'name' => 'Research Classification'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Program',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Project',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Study',
            'order' => 3,
            'is_active' => 1,
        ]);

        //category
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Category'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Research',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Book Chapter',
            'order' => 2,
            'is_active' => 1,
        ]);

        //uni research agenda
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'University Research Agenda'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Poverty Reduction',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Accelerating Infrastructure Development through Science and Technology',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Competitive Industry and Entrepreneurship',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Social and Cultural Development',
            'order' => 7,
            'is_active' => 0,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Environmental Conservation',
            'order' => 5,
            'is_active' => 1,
        ]);

        //nature of Involvement
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Nature of Involvement'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Lead Researcher',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Co-Lead Researcher',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Associate Lead Researcher',
            'order' => 4,
            'is_active' => 1,
        ]);

        //type of Research
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Type of Research'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Basic Research (GAD Related)',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Basic Research (Diversity and Inclusivity Related)',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Basic Research (GAD Related & Diversity and Inclusivity Related)',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Applied Research (GAD Related)',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Applied Research (Diversity and Inclusivity Related)',
            'order' => 5,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Applied Research (GAD Related & Diversity and Inclusivity Related)',
            'order' => 6,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Basic Research',
            'order' => 7,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Applied Research',
            'order' => 8,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Creative Work',
            'order' => 9,
            'is_active' => 1,
        ]);

        //type of funding
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Type of Funding'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'University Funded',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Self Funded',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Externally Funded',
            'order' => 1,
            'is_active' => 1,
        ]);
        
        //status
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Status'
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
            'order' => 3,
            'is_active' => 0,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed & Presented',
            'order' => 6,
            'is_active' => 0,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed & Published',
            'order' => 7,
            'is_active' => 0,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed, Presented & Published',
            'order' => 8,
            'is_active' => 0,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Deffered',
            'order' => 8,
            'is_active' => 0,
        ]);
        
        //research publication level
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Presentation Level'
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

        //research publication indexing
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Publication Indexing'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Scopus',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Web of Science',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'OASUC Accredited Journals',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'CHED Recognized Journals',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'International Refereed Journals',
            'order' => 5,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Excellence in Research for Australia',
            'order' => 6,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'ASEAN Citation Index',
            'order' => 7,
            'is_active' => 1,
        ]);

        //research level
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Research Level'
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
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Local',
            'order' => 2,
            'is_active' => 1,
        ]);

        
    }
}
