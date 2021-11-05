<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\FormTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            FieldTypeSeeder::class,
            ResearchDropdownSeeder::class,
            ResearchFormSeeder::class,
            CollegeSeeder::class,
            DepartmentSeeder::class,
            ResearchRegistrationFormSeeder::class,
            ResearchCompletedFormSeeder::class,
            ResearchPublicationFormSeeder::class,
            ResearchPresentationFormSeeder::class,
            ResearchCitationFormSeeder::class,
            ResearchUtilizationFormSeeder::class,
            ResearchCopyrightedFormSeeder::class,
            InventionDropdownSeeder::class,
            InventionFormSeeder::class,
            InventionSeeder::class,
        ]);
    }
}
