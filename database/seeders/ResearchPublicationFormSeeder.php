<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ResearchField;

class ResearchPublicationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchField::insert([
            'research_form_id' => 3,
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
            'research_form_id' => 3,
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


        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Publisher',
            'name' => 'publisher',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Journal Name',
            'name' => 'journal_name',
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
            'research_form_id' => 3,
            'label' => 'Editor',
            'name' => 'editor',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Level',
            'name' => 'level',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 8, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Date Published',
            'name' => 'publish_date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'ISSN/ISBN',
            'name' => 'issn',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Page No.',
            'name' => 'page',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Vol No.',
            'name' => 'volume',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Issue No.',
            'name' => 'issue',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Indexing Platform',
            'name' => 'indexing_platform',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 9, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ResearchField::insert([
            'research_form_id' => 3,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*Actual Page(s) with Journal Details, *Journal Title Page and Table of Contents",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ResearchField::insert([
            'research_form_id' => 3,
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
