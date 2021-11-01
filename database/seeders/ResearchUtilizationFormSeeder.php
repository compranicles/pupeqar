<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ResearchField;

class ResearchUtilizationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchField::create([
            'research_form_id' => 6,
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

        ResearchField::create([
            'research_form_id' => 6,
            'label' => 'Agency/Organization that Utilized the Research Output',
            'name' => 'organization',
            'placeholder' => null,
            'size' => 'col-md-9',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::create([
            'research_form_id' => 6,
            'label' => 'Brief Description of Research Utilization',
            'name' => 'utilization_description',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::create([
            'research_form_id' => 6,
            'label' => 'Level of Utilization',
            'name' => 'level',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 10, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::create([
            'research_form_id' => 6,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 8,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::create([
            'research_form_id' => 6,
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
