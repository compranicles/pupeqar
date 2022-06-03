<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\IPCRField;

class AdminSpecialTasksFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IPCRField::truncate();
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
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
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Output',
            'name' => 'output',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Outcome',
            'name' => 'outcome',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Participation/Significant Contribution',
            'name' => 'participation',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Special Order',
            'name' => 'special_order',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Level',
            'name' => 'level',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 49, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Nature of Accomplishment',
            'name' => 'nature_of_accomplishment',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'From (mm/dd/yyyy)',
            'name' => 'from',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'To (mm/dd/yyyy)',
            'name' => 'to',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Proof of Compliance',
            'name' => 'description',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'College/Branch/Campus/Office to commit the accomplishment',
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
        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
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

        IPCRField::insert([
            'i_p_c_r_form_id' => 2,
            'label' => 'Proof Upload',
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
