<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\AcademicDevelopmentField;

class StudentTrainingFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
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
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Title',
            'name' => 'title',
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
            'academic_development_form_id' => 4,
            'label' => 'Classification',
            'name' => 'classification',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 46, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Nature',
            'name' => 'nature',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 47, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Budget',
            'name' => 'budget',
            'placeholder' => '0.00',
            'size' => 'col-md-6',
            'field_type_id' => 3,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Source of Fund',
            'name' => 'source_of_fund',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 41, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Organization',
            'name' => 'organization',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Level',
            'name' => 'level',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 42, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Venue',
            'name' => 'venue',
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
            'academic_development_form_id' => 4,
            'label' => 'From',
            'name' => 'start_date',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'To',
            'name' => 'end_date',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Total No. of Hours',
            'name' => 'total_hours',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 11,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => "*Certificate/Other similar supporting documents, *Terminal report (if applicable), *Documentation/Pictures/ Other similar supporting documents",
            'size' => 'col-md-12',
            'field_type_id' => 16,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 4,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::insert([
            'academic_development_form_id' => 4,
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
