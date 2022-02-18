<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\AcademicDevelopmentField;

class StudentAwardFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 3,
            'label' => 'Name of Student',
            'name' => 'name_of_student',
            'placeholder' => 'Surname, First Name, Middle Initial',
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 3,
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
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 3,
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
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 3,
            'label' => 'Place/Venue',
            'name' => 'place',
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
            'academic_development_form_id' => 3,
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
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 3,
            'label' => 'Level',
            'name' => 'level',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 40, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 3,
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
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 3,
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
