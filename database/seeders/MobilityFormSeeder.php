<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ExtensionProgramField;

class MobilityFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => 'Classification of Person Involved',
            'name' => 'classification_of_person',
            'placeholder' => "If others, please specify.",
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 65, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => 'Type',
            'name' => 'type',
            'placeholder' => "If others, please specify.",
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 66, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => 'Classification of Mobility',
            'name' => 'classification_of_mobility',
            'placeholder' => "If others, please specify.",
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => 35, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => 'Nature of Engagement',
            'name' => 'nature_of_engagement',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => 34, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => 'Host Institution/ Organization/ Agency',
            'name' => 'host_name',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => 'Address of Host Institution/ Organization/ Agency/ Country',
            'name' => 'host_address',
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
            'extension_program_form_id' => 6,
            'label' => 'Collaborating Country and Institution/Organization/Agency',
            'name' => 'collaborating_country',
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
            'extension_program_form_id' => 6,
            'label' => 'Description of Inter-Country Mobility',
            'name' => 'mobility_description',
            'placeholder' => null,
            'size' => 'col-md-8',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => 'Inclusive Date',
            'name' => 'start_date',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
            'label' => '-',
            'name' => 'end_date',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
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
            'extension_program_form_id' => 6,
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
            'extension_program_form_id' => 6,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*Special Order (if applicable), *[For students] Proof of enrollment in the SUC (for both inbound and outbound students), *Documentation/Pictures/Other similar supporting documents, *MOA/MOU/Other similar supporting documents",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 6,
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
