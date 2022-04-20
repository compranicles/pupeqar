<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\AcademicDevelopmentField;

class CollegeDepartmentAwardFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 6,
            'label' => 'Name of Award',
            'name' => 'name_of_award',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 6,
            'label' => 'Certifying Body',
            'name' => 'certifying_body',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 6,
            'label' => 'Place',
            'name' => 'place',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 6,
            'label' => 'Date',
            'name' => 'date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 6,
            'label' => 'Level',
            'name' => 'level',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 43, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 6,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*Citation/Certificate of Award/Other similar supporting documents, *Pictures/Documentation/Other similar supporting documents",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 4,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 6,
            'label' => 'Document Upload',
            'name' => 'document',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 10,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 5,
            'is_active' => 1,
        ]);
    }
}
