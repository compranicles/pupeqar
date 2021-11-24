<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class ExtensionDropdownSeeder extends Seeder
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
            'name' => 'Expert Service as Consultant Classification'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Education',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Technology',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Arts & Sports',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Professional/Scientific',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Organizational Development/Management',
            'order' => 5,
            'is_active' => 1,
        ]);

        //category
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service as Consultant Category'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Technology Transfer',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Knowledge Transfer',
            'order' => 2,
            'is_active' => 1,
        ]);

        //level
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service as Consultant Level'
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
            'name' => 'Local',
            'order' => 4,
            'is_active' => 1,
        ]);

        /*************************EXPERT SERVICE IN CONFERENCE************************/
        //Nature of services rendered
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service In Conference Nature'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Trainer',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Coordinator',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Lecturer',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Resource/Guest Speaker',
            'order' => 4,
            'is_active' => 1,
        ]);

        //level
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service In Conference Level'
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
            'name' => 'Local',
            'order' => 4,
            'is_active' => 1,
        ]);

        /*************************EXPERT SERVICE IN ACADEMIC************************/
        //Nature of services rendered
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service In Academic Classification'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Academic Journal',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Books Publication',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Newsletter',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Creative Works',
            'order' => 4,
            'is_active' => 1,
        ]);
        
        //Nature of services rendered
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service In Academic Nature of Services'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Member',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Officer',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Consultant',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Reviewer',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Editor',
            'order' => 5,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Technical Panel',
            'order' => 6,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Coordinator',
            'order' => 7,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Others',
            'order' => 8,
            'is_active' => 1,
        ]);

        //Indexing
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service In Academic Indexing'
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
            'name' => 'PASUC Accredited Journals',
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

        //level
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Expert Service In Academic Level'
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
            'name' => 'Local',
            'order' => 4,
            'is_active' => 1,
        ]);

        /*************************EXTENSION SERVICES************************/
        //Level
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Extension Services Level'
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
            'name' => 'Local',
            'order' => 4,
            'is_active' => 1,
        ]);
        
        //Status
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Extension Services Status'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'New Program',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'New Project',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'New Activity',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Completed',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Ongoing',
            'order' => 5,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Deferred',
            'order' => 6,
            'is_active' => 1,
        ]);

        //Nature of involvement
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Extension Services Nature of Involvement'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Facilitator',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Resource Speaker',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Organizer',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Extensionist',
            'order' => 4,
            'is_active' => 1,
        ]);

        //Classification
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Extension Services Classification'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Livelihood Development',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Health',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Educational and Cultural Exchange',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Technology Transfer',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Knowledge Transfer',
            'order' => 5,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Local Governance',
            'order' => 6,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'IT',
            'order' => 7,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Others',
            'order' => 8,
            'is_active' => 1,
        ]);

        //Type
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Extension Services Type'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Training',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Technical/Advisory Services',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Outreach',
            'order' => 3,
            'is_active' => 1,
        ]);

        //Type of Funding
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Extension Services Type of Funding'
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
            'order' => 3,
            'is_active' => 1,
        ]);

        //Classification of Trainees/Beneficiaries
        $dropdownId  = Dropdown::insertGetId([
            'name' => 'Extension Services Classification of Trainees/Beneficiaries'
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Faculty',
            'order' => 1,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Students',
            'order' => 2,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Community',
            'order' => 3,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'HEI Administrators',
            'order' => 4,
            'is_active' => 1,
        ]);
        DropdownOption::create([
            'dropdown_id' => $dropdownId,
            'name' => 'Others',
            'order' => 5,
            'is_active' => 1,
        ]);
    }
}