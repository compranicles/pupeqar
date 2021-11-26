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
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
            'label' => 'Nature of Engagement',
            'name' => 'nature_of_engagement',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 34, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
            'label' => 'Type',
            'name' => 'type',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 35, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
            'label' => 'Hosting Institution/ Organization/ Agency',
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
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
            'label' => 'Full Address',
            'name' => 'host_address',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
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
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
            'label' => 'From',
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
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
            'label' => 'To',
            'name' => 'end_date',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        ExtensionProgramField::create([
            'extension_programs_form_id' => 6,
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
            'extension_programs_form_id' => 6,
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
