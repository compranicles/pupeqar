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
        Department::create(['name' => 'Accountancy']);
        Department::create(['name' => 'Architecture']);
        Department::create(['name' => 'Philosophy']);
        Department::create(['name' => 'Entrepreneurship']);
        Department::create(['name' => 'Journalism']);
        Department::create(['name' => 'Information Technology']);
        Department::create(['name' => 'Elementary Education']);
        Department::create(['name' => 'Civil Engineering']);
        Department::create(['name' => 'Physical Education']);
        Department::create(['name' => 'Juris Doctor']);
        Department::create(['name' => 'Public Administration']);
        Department::create(['name' => 'History']);
        Department::create(['name' => 'Physics']);
        Department::create(['name' => 'Tourism Management']);
    }
}
