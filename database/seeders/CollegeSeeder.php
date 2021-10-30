<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\College;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        College::truncate();
        College::create(['name' => 'College of Accountancy and Finance']);
        College::create(['name' => 'College of Architecture, Design and the Built Environment']);
        College::create(['name' => 'College of Arts and Letters']);
        College::create(['name' => 'College of Business Administration']);
        College::create(['name' => 'College of Communication']);
        College::create(['name' => 'College of Computer and Information Sciences']);
        College::create(['name' => 'College of Education']);
        College::create(['name' => 'College of Engineering']);
        College::create(['name' => 'College of Human Kinetics']);
        College::create(['name' => 'College of Law']);
        College::create(['name' => 'College of Political Science and Public Administration']);
        College::create(['name' => 'College of Social Sciences and Development']);
        College::create(['name' => 'College of Science']);
        College::create(['name' => 'College of Tourism, Hospitality and Transportation Management']);
    }
}
