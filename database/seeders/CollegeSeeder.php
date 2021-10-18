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
        //
        College::create(['name' => 'Accountancy and Finance (CAF)']);
        College::create(['name' => 'Architecture, Design and the Built Environment (CADBE)']);
        College::create(['name' => 'Arts and Letters (CAL)']);
        College::create(['name' => 'Business Administration (CBA)']);
        College::create(['name' => 'Communication (COC)']);
        College::create(['name' => 'Computer and Information Sciences (CCIS)']);
        College::create(['name' => 'Education (COED)']);
        College::create(['name' => 'Engineering (CE)']);
        College::create(['name' => 'Human Kinetics (CHK)']);
        College::create(['name' => 'Law (CL)']);
        College::create(['name' => 'Political Science and Public Administration (CPSPA)']);
        College::create(['name' => 'Social Sciences and Development (CSSD)']);
        College::create(['name' => 'Science (CS)']);
        College::create(['name' => 'Tourism, Hospitality and Transportation Management (CTHTM)']);
    }
}
