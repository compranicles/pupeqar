<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ResearchField;

class ResearchPresentationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchField::create([
            'research_form_id' => 4,
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
            'research_form_id' => 4,
            'label' => 'Status',
            'name' => 'status',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 7, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::create([
            'research_form_id' => 4,
            'label' => 'Conference Title',
            'name' => 'conference_title',
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
            'research_form_id' => 4,
            'label' => 'Organizer',
            'name' => 'organizer',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::create([
            'research_form_id' => 4,
            'label' => 'Venue',
            'name' => 'venue',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::create([
            'research_form_id' => 4,
            'label' => 'Date Presented',
            'name' => 'date_presented',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::create([
            'research_form_id' => 4,
            'label' => 'Level',
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
            'research_form_id' => 4,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*Abstract, *Certificate of Presentation/Attendance, *Conference Proceedings, *Documentation of Paper Presentation",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::create([
            'research_form_id' => 4,
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
