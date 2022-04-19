<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ResearchField;

class ResearchCompletedFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchField::insert([
            'research_form_id' => 2,
            'label' => 'Research Code',
            'name' => 'research_code',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 2,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 2,
            'label' => 'Status',
            'name' => 'status',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 7, 
            'required' => 1,
            'visibility' => 1,
            'order' => 2,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 2,
            'label' => 'Date Completed',
            'name' => 'completion_date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 3,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 2,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*Abstract/Draft Manuscript",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 4,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 2,
            'label' => 'Document Upload',
            'name' => 'document',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 10,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 5,
            'is_active' => 1,
        ]);
    }
}
