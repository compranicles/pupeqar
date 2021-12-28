<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\InventionField;

class InventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InventionField::truncate();
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Classification',
            'name' => 'classification',
            'placeholder' => null,
            'size' => 'col-md-2',
            'field_type_id' => 5,
            'dropdown_id' => 11, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Nature (IT Products, Equipments, Machinery, etc.)',
            'name' => 'nature',
            'placeholder' => null,
            'size' => 'col-md-4',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
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
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Name of Collaborators',
            'name' => 'collaborator',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Type of Funding',
            'name' => 'funding_type',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 12, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Amount of Funding',
            'name' => 'funding_amount',
            'placeholder' => '0.00',
            'size' => 'col-md-3',
            'field_type_id' => 3,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Funding Agency',
            'name' => 'funding_agency',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);

        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Status',
            'name' => 'status',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 5,
            'dropdown_id' => 13, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Project Date Started',
            'name' => 'start_date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Project Date Ended',
            'name' => 'end_date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Utilization of Invention',
            'name' => 'utilization',
            'placeholder' => null,
            'size' => 'col-md-6',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Copyright/Patent No.',
            'name' => 'copyright_number',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 1,
            'dropdown_id' => null, 
            'required' => 1,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Date of Issue',
            'name' => 'issue_date',
            'placeholder' => null,
            'size' => 'col-md-3',
            'field_type_id' => 4,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'College/Campus/Branch/Office to commit the accomplishment',
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
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Department to commit the accomplishment',
            'name' => 'department_id',
            'placeholder' => "*Certificate of Patent, *Full Paper of Invention, *Certificate of Manufacturers/Fabricators",
            'size' => 'col-md-6',
            'field_type_id' => 13,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
            'label' => 'Description of Supporting Documents',
            'name' => 'description',
            'placeholder' => null,
            'size' => 'col-md-12',
            'field_type_id' => 8,
            'dropdown_id' => null, 
            'required' => 0,
            'visibility' => 1,
            'order' => 1,
            'is_active' => 1,
        ]);
        InventionField::create([
            'invention_form_id' => 1,
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
