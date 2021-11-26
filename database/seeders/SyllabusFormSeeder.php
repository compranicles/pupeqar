<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\AcademicDevelopmentField;

class SyllabusFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 2,
            'label' => 'Course Title',
            'name' => 'course_title',
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
            'academic_development_form_id' => 2,
            'label' => 'Assigned Task',
            'name' => 'assigned_task',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 5,
            'dropdown_id' => 38, 
            'required' => 1,
            'visibility' => 1,
            'order' => 2,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 2,
            'label' => 'Date You Finished the Assigned Task',
            'name' => 'date_finished',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 3,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 2,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 8,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 4,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 2,
            'label' => 'Document Upload',
            'name' => 'document',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 10,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 5,
            'is_active' => 1,
        ]);
    }
}
