<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ExtensionProgramField;

class ExtensionServiceFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
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

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Status',
            'name' => 'status',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 24, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Nature of Involvement',
            'name' => 'nature-of-involvement',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 25, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Classification',
            'name' => 'classification',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 26, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Type',
            'name' => 'type',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 27, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Title of Extension Program',
            'name' => 'title-of-extension-program',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Title of Extension Project',
            'name' => 'title-of-extension-project',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Title of Extension Activity',
            'name' => 'title-of-extension-activity',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Funding Agency',
            'name' => 'funding-agency',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Amount of Funding',
            'name' => 'amount-of-funding',
            'placeholder' => '0.00',
            'size' => 'col-md-3',
            'field_type_id' => 3,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Type of Funding',
            'name' => 'type-of-funding',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 28, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'From',
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

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'To',
            'name' => 'to',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'No. of Trainess/Beneficiaries',
            'name' => 'no-of-trainees-or-beneficiaries',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Total No. of Hours',
            'name' => 'total-no-of-hours',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 11,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Classification of Trainees/Beneficiaries',
            'name' => 'classification-of-trainees-or-beneficiaries',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 29, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Place/Venue',
            'name' => 'place-or-venue',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Keywords',
            'name' => 'keywords',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Poor',
            'name' => 'quality-poor',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Fair',
            'name' => 'quality-fair',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Satisfactory',
            'name' => 'quality-satisfactory',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Very Satisfactory',
            'name' => 'quality-very-satisfactory',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Outstanding',
            'name' => 'quality-outstanding',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Poor',
            'name' => 'timeliness-poor',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Fair',
            'name' => 'timeliness-fair',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Satisfactory',
            'name' => 'timeliness-satisfactory',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Very Satisfactory',
            'name' => 'timeliness-very-satisfactory',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
            'label' => 'Outstanding',
            'name' => 'timeliness-outstanding',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
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

        ExtensionProgramField::create([
            'extension_programs_form_id' => 4,
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
