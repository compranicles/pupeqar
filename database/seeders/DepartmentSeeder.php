<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Department::truncate();
        Department::create(['name' => 'Accountancy', 'college_id' => 1]);
        Department::create(['name' => 'Financial Management', 'college_id' => 1]);
        Department::create(['name' => 'Management Accounting', 'college_id' => 1]);//3

        Department::create(['name' => 'Architecture', 'college_id' => 2]);
        Department::create(['name' => 'Environmental Planning', 'college_id' => 2]);
        Department::create(['name' => 'Interior Design', 'college_id' => 2]);//6

        Department::create(['name' => 'English, Foreign Languages and Linguistics', 'college_id' => 3]);
        Department::create(['name' => 'Filipinolohiya', 'college_id' => 3]);
        Department::create(['name' => 'Humanities and Philosophy', 'college_id' => 3]);
        Department::create(['name' => 'Performing Arts', 'college_id' => 3]);//10

        Department::create(['name' => 'Entrepreneurship', 'college_id' => 4]);
        Department::create(['name' => 'Human Resource Management', 'college_id' => 4]);
        Department::create(['name' => 'Marketing Management', 'college_id' => 4]);
        Department::create(['name' => 'Office Administration', 'college_id' => 4]);
        Department::create(['name' => 'CBA Graduate Programs', 'college_id' => 4]); //15

        //CBA Graduate Programs?
        Department::create(['name' => 'Advertising and Public Relations', 'college_id' => 5]);
        Department::create(['name' => 'Broadcast Communication', 'college_id' => 5]);
        Department::create(['name' => 'Communication Research', 'college_id' => 5]);
        Department::create(['name' => 'Journalism', 'college_id' => 5]); //19

        Department::create(['name' => 'Computer Science', 'college_id' => 6]);
        Department::create(['name' => 'Information Technology', 'college_id' => 6]); //21

        Department::create(['name' => 'Elementary Education and Secondary Education', 'college_id' => 7]);
        Department::create(['name' => 'Business Teacher Education', 'college_id' => 7]);
        Department::create(['name' => 'Library Science', 'college_id' => 7]);
        Department::create(['name' => 'COED Graduate Programs', 'college_id' => 7]); //25

        //COED Graduate Programs?

        Department::create(['name' => 'Civil Engineering', 'college_id' => 8]);
        Department::create(['name' => 'Computer Engineering', 'college_id' => 8]);
        Department::create(['name' => 'Electrical Engineering', 'college_id' => 8]);
        Department::create(['name' => 'Electronics Engineering', 'college_id' => 8]);
        Department::create(['name' => 'Engineering Sciences', 'college_id' => 8]);
        Department::create(['name' => 'Industrial Engineering', 'college_id' => 8]);
        Department::create(['name' => 'Mechanical Engineering', 'college_id' => 8]);
        Department::create(['name' => 'Railway Engineering', 'college_id' => 8]); //33

        Department::create(['name' => 'Professional Programs', 'college_id' => 9]);
        Department::create(['name' => 'Service Physical Education', 'college_id' => 9]);
        Department::create(['name' => 'Sports Science', 'college_id' => 9]); //36


        Department::create(['name' => 'College of Law (CoL)', 'college_id' => 10]); //37

        Department::create(['name' => 'Political Economy', 'college_id' => 11]);
        Department::create(['name' => 'Political Science and International Studies', 'college_id' => 11]);
        Department::create(['name' => 'Public Administration and Governance', 'college_id' => 11]);
        Department::create(['name' => 'CPSPA Graduate Programs', 'college_id' => 11]); //41

        //CPSPA Graduate Programs?

        Department::create(['name' => 'Cooperatives and Social Development', 'college_id' => 12]);
        Department::create(['name' => 'Economics', 'college_id' => 12]);
        Department::create(['name' => 'History', 'college_id' => 12]);
        Department::create(['name' => 'Psychology', 'college_id' => 12]);
        Department::create(['name' => 'Sociology', 'college_id' => 12]); //46

        Department::create(['name' => 'Biology', 'college_id' => 13]);
        Department::create(['name' => 'Food Technology', 'college_id' => 13]);
        Department::create(['name' => 'Math and Statistics', 'college_id' => 13]);
        Department::create(['name' => 'Nutrition and Dietetics', 'college_id' => 13]);
        Department::create(['name' => 'Physical Science', 'college_id' => 13]); //51

        Department::create(['name' => 'Hospitality Management', 'college_id' => 14]);
        Department::create(['name' => 'Tourism and Transportation Management', 'college_id' => 14]); //53

        Department::create(['name' => 'Graduate School (GS)', 'college_id' => 15]); //54

        Department::create(['name' => 'Civil and Railway Engineering Technology', 'college_id' => 16]);
        Department::create(['name' => 'Electronics Engineering Technology', 'college_id' => 16]);
        Department::create(['name' => 'Electrical and Mechanical Engineering Technology', 'college_id' => 16]);
        Department::create(['name' => 'Office Management and Information Technology', 'college_id' => 16]); //58

        Department::create(['name' => 'National Service Training Program (NSTP) Office', 'college_id' => 17]);
        Department::create(['name' => 'Ninoy Aquino Library and Learning Resources Center (NALLRC)', 'college_id' => 18]);
        Department::create(['name' => 'Open University System (OUS)', 'college_id' => 19]);
        Department::create(['name' => 'Graduate School (GS)', 'college_id' => 20]);
        Department::create(['name' => 'Laboratory High School', 'college_id' => 21]);
        Department::create(['name' => 'Alfonso, Cavite', 'college_id' => 22]);
        Department::create(['name' => 'Bansud, Oriental Mindoro', 'college_id' => 23]);
        Department::create(['name' => 'Bataan', 'college_id' => 24]);
        Department::create(['name' => 'Biñan, Laguna', 'college_id' => 25]);
        Department::create(['name' => 'Cabiao, Nueva Ecija', 'college_id' => 26]);
        Department::create(['name' => 'Calauan, Laguna', 'college_id' => 27]);
        Department::create(['name' => 'General Luna, Quezon', 'college_id' => 28]);
        Department::create(['name' => 'Lopez, Quezon', 'college_id' => 29]);
        Department::create(['name' => 'Maragondon, Cavite', 'college_id' => 30]);
        Department::create(['name' => 'Mulanay, Quezon', 'college_id' => 31]);
        Department::create(['name' => 'Parañaque', 'college_id' => 32]);
        Department::create(['name' => 'Pulilan, Bulacan', 'college_id' => 33]);
        Department::create(['name' => 'Quezon City', 'college_id' => 34]);
        Department::create(['name' => 'Ragay', 'college_id' => 35]);
        Department::create(['name' => 'Sablayan, Mindoro', 'college_id' => 36]);
        Department::create(['name' => 'San Juan', 'college_id' => 37]);
        Department::create(['name' => 'San Pedro, Laguna', 'college_id' => 38]);
        Department::create(['name' => 'Sta. Maria, Bulacan', 'college_id' => 39]);
        Department::create(['name' => 'Sta. Rosa, Laguna', 'college_id' => 40]);
        Department::create(['name' => 'Sto. Tomas, Batangas', 'college_id' => 41]);
        Department::create(['name' => 'Taguig', 'college_id' => 42]);
        Department::create(['name' => 'Unisan', 'college_id' => 43]);     //85
    }
}
