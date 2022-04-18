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
            // authentication
            RoleSeeder::class,
            PermissionSeeder::class,
            // UserSeeder::class,
            // UserRoleSeeder::class,
            RolePermissionSeeder::class,

            //other maintenance
            // QuarterSeeder::class,
            SectorSeeder::class,
            CollegeSeeder::class,
            DepartmentSeeder::class,
            CurrencySeeder::class,

            // Field types
            FieldTypeSeeder::class,

            //Dropdown Seeders
            DropdownSeeder::class,

            //Form Seeder
            ResearchFormSeeder::class,
            InventionFormSeeder::class,
            ExtensionFormSeeder::class,
            AcademicDevelopmentFormSeeder::class,
            IPCRFormSeeder::class,
            HRISFormSeeder::class,
            
            
            //FIELD Seeder
            FieldSeeder::class,
            
            //Reports/submissions
            ReportTypeSeeder::class,
            ReportCategorySeeder::class,
            ReportColumnSeeder::class,

            //Document Description
            DocumentDescriptionSeeder::class,
            
            //Generate
            GenerateTypeSeeder::class,
            GenerateTableSeeder::class,
            GenerateColumnSeeder::class,
            GenerateColumn2Seeder::class,
            GenerateColumnHRISSeeder::class,
            GenerateColumn3Seeder::class,
            GenerateColumn4Seeder::class,
        ]);
        
        \App\Models\Research::truncate();
        // \App\Models\Research::factory()->count(12)->create();

        \App\Models\Invention::truncate();
        \App\Models\Invention::factory()->count(12)->create();

        \App\Models\Mobility::truncate();
        \App\Models\Mobility::factory()->count(12)->create();

        \App\Models\Partnership::truncate();
        \App\Models\Partnership::factory()->count(12)->create();

        // \App\Models\ExtensionService::truncate();
        // // \App\Models\ExtensionService::factory()->count(12)->create();

        \App\Models\ExpertServiceAcademic::truncate();
        \App\Models\ExpertServiceAcademic::factory()->count(12)->create();

        \App\Models\ExpertServiceConference::truncate();
        \App\Models\ExpertServiceConference::factory()->count(12)->create();

        \App\Models\ExpertServiceConsultant::truncate();
        \App\Models\ExpertServiceConsultant::factory()->count(12)->create();

        \App\Models\Reference::truncate();
        \App\Models\Reference::factory()->count(12)->create();

        \App\Models\Syllabus::truncate();
        \App\Models\Syllabus::factory()->count(12)->create();
    }
}
