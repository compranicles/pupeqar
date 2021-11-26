<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormBuilder\AcademicDevelopmentForm;

class AcademicDevelopmentFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicDevelopmentForm::truncate();
        AcademicDevelopmentForm::insert([
            'label' => 'Reference/Textbook/Module/Monographs/Instructional Materials',
            'table_name' => 'references',
            'is_active' => 1
        ]);
        
        AcademicDevelopmentForm::insert([
            'label' => 'Course Syllabus',
            'table_name' => 'syllabi',
            'is_active' => 1
        ]);
    }
}
