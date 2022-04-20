<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\IPCRField;

class RequestFormSeeder extends Seeder
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
            'i_p_c_r_form_id' => 1,
            'label' => 'Number of Written Request Acted Upon',
            'name' => 'no_of_request',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 2,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 1,
            'label' => 'Brief Description of Request',
            'name' => 'description_of_request',
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
            'i_p_c_r_form_id' => 1,
            'label' => 'Average Days/Time of Processing',
            'name' => 'processing_time',
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
            'i_p_c_r_form_id' => 1,
            'label' => 'Category',
            'name' => 'category',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 48, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        IPCRField::insert([
            'i_p_c_r_form_id' => 1,
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
            'i_p_c_r_form_id' => 1,
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
            'i_p_c_r_form_id' => 1,
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
            'i_p_c_r_form_id' => 1,
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
