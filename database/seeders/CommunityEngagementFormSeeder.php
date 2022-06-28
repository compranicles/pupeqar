<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ExtensionProgramField;

class CommunityEngagementFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
            'label' => 'List of Active Linkages/Partnerships Covered by MOA',
            'name' => 'active_linkages',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
            'label' => 'Classification of Agro-industrial Technology',
            'name' => 'classification_of_agro',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
            'label' => 'Inclusive Period',
            'name' => 'from',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
            'label' => '-',
            'name' => 'to',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
            'label' => 'Partnership Coverage (LGU, Industry, SMEs (Small & Medium Enteprises), NGOs, and Pos)',
            'name' => 'partnership_coverage',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
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
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
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
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 9,
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
