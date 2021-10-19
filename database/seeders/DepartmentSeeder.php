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
        Department::create(['name' => 'Accountancy', 'college_id' => 1]);
        Department::create(['name' => 'Architecture', 'college_id' => 2]);
        Department::create(['name' => 'Philosophy', 'college_id' => 3]);
        Department::create(['name' => 'Entrepreneurship', 'college_id' => 4]);
        Department::create(['name' => 'Journalism', 'college_id' => 5]);
        Department::create(['name' => 'Information Technology', 'college_id' => 6]);
        Department::create(['name' => 'Elementary Education', 'college_id' => 7]);
        Department::create(['name' => 'Civil Engineering', 'college_id' => 8]);
        Department::create(['name' => 'Physical Education', 'college_id' => 9]);
        Department::create(['name' => 'Juris Doctor', 'college_id' => 10]);
        Department::create(['name' => 'Public Administration', 'college_id' => 11]);
        Department::create(['name' => 'History', 'college_id' => 12]);
        Department::create(['name' => 'Physics', 'college_id' => 13]);
        Department::create(['name' => 'Tourism Management', 'college_id' => 14]);
    }
}
