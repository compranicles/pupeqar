<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\AcademicDevelopmentField;

class TechnicalExtensionFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'MOA/MOU Code Number',
            'name' => 'moa_code',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Title of the Program',
            'name' => 'program_title',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Title of the Project',
            'name' => 'project_title',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Title of the Activity',
            'name' => 'activity_title',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Name of the Adoptor',
            'name' => 'name_of_adoptor',
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
            'academic_development_form_id' => 7,
            'label' => 'Classification of Adoptor',
            'name' => 'classification_of_adoptor',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 44, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Nature of Business Enterprise',
            'name' => 'nature_of_business_enterprise',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Adoptors have established profitable businesses in the last three years',
            'name' => 'has_businesses',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 14,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Extension project by the university or borrowed from other institutions',
            'name' => 'is_borrowed',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 5,
            'dropdown_id' => 45, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
            'label' => 'Total Profit/ Income of the Adoptors',
            'name' => 'total_profit',
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
            'academic_development_form_id' => 7,
            'label' => 'Description of Supporting Documents',
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
        AcademicDevelopmentField::create([
            'academic_development_form_id' => 7,
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
