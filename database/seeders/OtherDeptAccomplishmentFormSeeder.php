<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ExtensionProgramField;

class OtherDeptAccomplishmentFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExtensionProgramField::insert([
            'extension_program_form_id' => 11,
            'label' => 'Brief Description of Accomplishment',
            'name' => 'accomplishment_description',
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
            'extension_program_form_id' => 11,
            'label' => 'Inclusive Date',
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
            'extension_program_form_id' => 11,
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
            'extension_program_form_id' => 11,
            'label' => 'Place',
            'name' => 'place',
            'placeholder' => null,
            'size' => 'col-md-8',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::insert([
            'extension_program_form_id' => 11,
            'label' => 'Level',
            'name' => 'level',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 23, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        
        ExtensionProgramField::insert([
            'extension_program_form_id' => 11,
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
            'extension_program_form_id' => 11,
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
            'extension_program_form_id' => 11,
            'label' => 'Proof of Compliance',
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
            'extension_program_form_id' => 11,
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
