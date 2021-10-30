<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\ResearchForm;

class ResearchFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResearchForm::truncate();
        ResearchForm::insert([
            'label' => 'Research Registration',
            'table_name' => 'research',
            'is_active' => 1
        ]);
        ResearchForm::insert([
            'label' => 'Research Completed',
            'table_name' => 'research',
            'is_active' => 1
        ]);
        ResearchForm::insert([
            'label' => 'Research Publication',
            'table_name' => 'research_publications',
            'is_active' => 1
        ]);
        ResearchForm::insert([
            'label' => 'Research Presentation',
            'table_name' => 'research_presentations',
            'is_active' => 1
        ]);
        ResearchForm::insert([
            'label' => 'Research Citation',
            'table_name' => 'research_citations',
            'is_active' => 1
        ]);
        ResearchForm::insert([
            'label' => 'Research Utilization',
            'table_name' => 'research_utilizations',
            'is_active' => 1
        ]);
        ResearchForm::insert([
            'label' => 'Research Copyrighted',
            'table_name' => 'research_copyrights',
            'is_active' => 1
        ]);
    }
}
