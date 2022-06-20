<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\IPCRField;

class SpecialTasksFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IPCRField::insert([
            'i_p_c_r_form_id' => 3,
            'label' => 'Commitment Measurable by:',
            'name' => 'commitment_measure',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 5,
            'dropdown_id' => 61, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 3,
            'label' => 'Final Output',
            'name' => 'final_output',
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
            'i_p_c_r_form_id' => 3,
            'label' => 'Target',
            'name' => 'target_date',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 3,
            'label' => 'Actual',
            'name' => 'actual_date',
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
            'i_p_c_r_form_id' => 3,
            'label' => 'Description of Accomplishment',
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
            'i_p_c_r_form_id' => 3,
            'label' => 'Status',
            'name' => 'status',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 62, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 3,
            'label' => 'Remarks',
            'name' => 'remarks',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        IPCRField::insert([
            'i_p_c_r_form_id' => 3,
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
            'i_p_c_r_form_id' => 3,
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
            'i_p_c_r_form_id' => 3,
            'label' => 'Description of Supporting Documents Submitted',
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
            'i_p_c_r_form_id' => 3,
            'label' => 'Supporting Documents Upload',
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
