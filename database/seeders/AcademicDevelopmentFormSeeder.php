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
        AcademicDevelopmentForm::insert([
            'label' => 'Student Awards and Recognition',
            'table_name' => 'student_awards',
            'is_active' => 1
        ]);
        AcademicDevelopmentForm::insert([
            'label' => 'STUDENTS TRAININGS AND SEMINARS',
            'table_name' => 'student_trainings',
            'is_active' => 1
        ]);
        AcademicDevelopmentForm::insert([
            'label' => 'Viable Demonstration Projects',
            'table_name' => 'viable_projects',
            'is_active' => 1
        ]);
        AcademicDevelopmentForm::insert([
            'label' => 'Awards and Recognition Received by the College and Department',
            'table_name' => 'colnsionlege_department_awards',
            'is_active' => 1
        ]);
    }
}
