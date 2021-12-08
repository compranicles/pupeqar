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
        College::create(['name' => 'College of Accountancy and Finance (CAF)']);
        College::create(['name' => 'College of Architecture, Design and the Built Environment (CADBE)']);
        College::create(['name' => 'College of Arts and Letters (CAL)']);
        College::create(['name' => 'College of Business Administration (CBA)']);
        College::create(['name' => 'College of Communication (CoC)']);
        College::create(['name' => 'College of Computer and Information Sciences (CCIS)']);
        College::create(['name' => 'College of Education (CoEd)']);
        College::create(['name' => 'College of Engineering (CE)']);
        College::create(['name' => 'College of Human Kinetics (CHK)']);
        College::create(['name' => 'College of Law (CoL)']);
        College::create(['name' => 'College of Political Science and Public Administration (CPSPA)']);
        College::create(['name' => 'College of Social Sciences and Development (CSSD)']);
        College::create(['name' => 'College of Science (CS)']);
        College::create(['name' => 'College of Tourism, Hospitality and Transportation Management (CTHTM)']);
        College::create(['name' => 'Graduate School (GS)']);
        College::create(['name' => 'Institute of Technology (ITech)']);
        College::create(['name' => 'National Service Training Program (NSTP) Office']);
        College::create(['name' => 'Ninoy Aquino Library and Learning Resources Center (NALLRC)']);
        College::create(['name' => 'Open University System (OUS)']);
        College::create(['name' => 'Graduate School (GS)']);
        College::create(['name' => 'Laboratory High School']);
        College::create(['name' => 'Alfonso, Cavite']);
        College::create(['name' => 'Bansud, Oriental Mindoro']);
        College::create(['name' => 'Bataan']);
        College::create(['name' => 'Biñan, Laguna']);
        College::create(['name' => 'Cabiao, Nueva Ecija']);
        College::create(['name' => 'Calauan, Laguna']);
        College::create(['name' => 'General Luna, Quezon']);
        College::create(['name' => 'Lopez, Quezon']);
        College::create(['name' => 'Maragondon, Cavite']);
        College::create(['name' => 'Mulanay, Quezon']);
        College::create(['name' => 'Parañaque']);
        College::create(['name' => 'Pulilan, Bulacan']);
        College::create(['name' => 'Quezon City']);
        College::create(['name' => 'Ragay']);
        College::create(['name' => 'Sablayan, Mindoro']);
        College::create(['name' => 'San Juan']);
        College::create(['name' => 'San Pedro, Laguna']);
        College::create(['name' => 'Sta. Maria, Bulacan']);
        College::create(['name' => 'Sta. Rosa, Laguna']);
        College::create(['name' => 'Sto. Tomas, Batangas']);
        College::create(['name' => 'Taguig']);
        College::create(['name' => 'Unisan']);        
    }
}
