<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //REsearch Field Seeder
            ResearchRegistrationFormSeeder::class,
            ResearchCompletedFormSeeder::class,
            ResearchPublicationFormSeeder::class,
            ResearchPresentationFormSeeder::class,
            ResearchCitationFormSeeder::class,
            ResearchUtilizationFormSeeder::class,
            ResearchCopyrightedFormSeeder::class,
            
            //Invention FIELD Seeders
            InventionSeeder::class,

            //Extension Program FIELD Seeeders
            ExtensionProgramFieldSeeder::class,
            
            //Academic Dvpt FIELD Seeders
            ReferenceFormSeeder::class,
            SyllabusFormSeeder::class,
            StudentAwardFormSeeder::class,
            StudentTrainingFormSeeder::class,
            ViableProjectFormSeeder::class,
            TechnicalExtensionFormSeeder::class,
            CollegeDepartmentAwardFormSeeder::class,

            //IPCR Field Seeders
            RequestFormSeeder::class,

            //HRIS Field Seeders
            HRISFieldSeeder::class,
        ]);
    }
}
