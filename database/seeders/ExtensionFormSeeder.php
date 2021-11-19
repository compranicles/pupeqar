<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ExtensionProgramForm;


class ExtensionFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExtensionProgramForm::truncate();
        ExtensionProgramForm::insert([
            'label' => 'Expert Service Rendered as Consultant',
            'table_name' => 'expert_service_consultant',
            'is_active' => 1
        ]);
        ExtensionProgramForm::insert([
            'label' => 'Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional',
            'table_name' => 'expert_services_conference',
            'is_active' => 1
        ]);
        ExtensionProgramForm::insert([
            'label' => 'Expert Service Rendered in Academic',
            'table_name' => 'expert_services_academic',
            'is_active' => 1
        ]);
        ExtensionProgramForm::insert([
            'label' => 'Extension Service',
            'table_name' => 'extension_services',
            'is_active' => 1
        ]);
    }
}
