<?php

namespace Database\Seeders;

use App\Models\Mobility;
use App\Models\Research;
use App\Models\Syllabus;
use App\Models\Invention;
use App\Models\Reference;
use App\Models\Partnership;
use Illuminate\Database\Seeder;
use App\Models\ExtensionService;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\FieldSeeder;
use Database\Seeders\SectorSeeder;
use Database\Seeders\CollegeSeeder;
use Database\Seeders\QuarterSeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\DropdownSeeder;
use Database\Seeders\FormTypeSeeder;
use Database\Seeders\HRISFormSeeder;
use Database\Seeders\IPCRFormSeeder;
use Database\Seeders\UserRoleSeeder;
use App\Models\ExpertServiceAcademic;
use Database\Seeders\FieldTypeSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ReportTypeSeeder;
use Illuminate\Support\Facades\Schema;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceConsultant;
use Database\Seeders\GenerateTypeSeeder;
use Database\Seeders\ReportColumnSeeder;
use Database\Seeders\ResearchFormSeeder;
use Database\Seeders\ExtensionFormSeeder;
use Database\Seeders\GenerateTableSeeder;
use Database\Seeders\InventionFormSeeder;
use Database\Seeders\GenerateColumnSeeder;
use Database\Seeders\ReportCategorySeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\GenerateColumn2Seeder;
use Database\Seeders\GenerateColumn3Seeder;
use Database\Seeders\GenerateColumn4Seeder;
use Database\Seeders\GenerateColumnHRISSeeder;
use Database\Seeders\DocumentDescriptionSeeder;
use Database\Seeders\AcademicDevelopmentFormSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        Schema::disableForeignKeyConstraints();
        $this->call([
            // authentication
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,

            //other maintenance
            QuarterSeeder::class,
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
        \App\Models\Report::truncate();
      
        \App\Models\Research::truncate();
        // \App\Models\Research::factory()->count(20)->create();

        // \App\Models\Invention::truncate();
        // \App\Models\Invention::factory()->count(20)->create();

        // \App\Models\Mobility::truncate();
        // \App\Models\Mobility::factory()->count(20)->create();

        // \App\Models\Partnership::truncate();
        // \App\Models\Partnership::factory()->count(20)->create();

        \App\Models\ExtensionService::truncate();
        // // \App\Models\ExtensionService::factory()->count(20)->create();

        // \App\Models\ExpertServiceAcademic::truncate();
        // \App\Models\ExpertServiceAcademic::factory()->count(20)->create();

        // \App\Models\ExpertServiceConference::truncate();
        // \App\Models\ExpertServiceConference::factory()->count(20)->create();

        // \App\Models\ExpertServiceConsultant::truncate();
        // \App\Models\ExpertServiceConsultant::factory()->count(20)->create();

        \App\Models\Reference::truncate();
        \App\Models\Reference::factory()->count(20)->create();

        // \App\Models\Syllabus::truncate();
        // \App\Models\Syllabus::factory()->count(20)->create();

        Schema::enableForeignKeyConstraints();
    }
}
