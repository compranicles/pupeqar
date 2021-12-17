<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\AcademicDevelopmentField;

class ViableProjectFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 5,
            'label' => 'Name of Viable Demonstration Project',
            'name' => 'name',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 5,
            'label' => 'Revenue',
            'name' => 'revenue',
            'placeholder' => '0.00',
            'size' => 'col-md-6',
            'field_type_id' => 3,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 5,
            'label' => 'Cost',
            'name' => 'cost',
            'placeholder' => '0.00',
            'size' => 'col-md-6',
            'field_type_id' => 3,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 5,
            'label' => 'Date Started',
            'name' => 'start_date',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 5,
            'label' => 'Internal Rate of Return',
            'name' => 'rate_of_return',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 15,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 5,
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
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 5,
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
