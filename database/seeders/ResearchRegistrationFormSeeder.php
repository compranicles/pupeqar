<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ResearchField;

class ResearchRegistrationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ResearchField::truncate();
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Classification',
            'name' => 'classification',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 5,
            'dropdown_id' => 1, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Category',
            'name' => 'category',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 5,
            'dropdown_id' => 2, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'University Research Agenda',
            'name' => 'agenda',
            'placeholder' => null,
            'size' => 'col-md-8',
            'field_type_id' => 5,
            'dropdown_id' => 3, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Title',
            'name' => 'title',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Researcher/s',
            'name' => 'researchers',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Keywords (at least five (5) keywords)',
            'name' => 'keywords',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Nature of Involvement',
            'name' => 'nature_of_involvement',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 4, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Type of Research',
            'name' => 'research_type',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 5, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Type of Funding',
            'name' => 'funding_type',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 6, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Amount of Funding',
            'name' => 'funding_amount',
            'placeholder' => '0.00',
            'size' => 'col-md-3',
            'field_type_id' => 3,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Funding Agency',
            'name' => 'funding_agency',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        
        // ResearchField::insert([
        //     'research_form_id' => 1,
        //     'label' => 'Date Completed',
        //     'name' => 'completion_date',
        //     'placeholder' => null,
        //     'size' => 'col-md-3',
        //     'field_type_id' => 4,
        //     'dropdown_id' => null, 
        //     'required' => 0,
        //     'visibility' => 1,
        //     'order' => 1,
        //     'is_active' => 1,
        // ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Status',
            'name' => 'status',
            'placeholder' => null,
            'size' => 'col-md-5',
            'field_type_id' => 5,
            'dropdown_id' => 7, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Actual Date Started',
            'name' => 'start_date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Target Date of Completion',
            'name' => 'target_date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Department to commit the accomplishment',
            'name' => 'department_id',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 13,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'College/Campus/Branch/Office to commit the accomplishment',
            'name' => 'college_id',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 12,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*[For externally-funded] MOA/MOU, *[For externally-funded] BOR Resolution, *[For On-going] Abstract, *[For On-going] Research Proposal, *[For On-going] Certification Issued by the Reviewer of the Research Instrument Used, *Ethics Clearance",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        
        ResearchField::insert([
            'research_form_id' => 1,
            'label' => 'Document Upload',
            'name' => 'document',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 10,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        
        
    }
}
