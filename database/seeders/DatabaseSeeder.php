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
            UserSeeder::class,
            UserRoleSeeder::class,
            RolePermissionSeeder::class,

            // Field types
            FieldTypeSeeder::class,
            
            //other maintenance
            SectorSeeder::class,
            CollegeSeeder::class,
            DepartmentSeeder::class,
            CurrencySeeder::class,

            //Dropdown Seeders
            DropdownSeeder::class,

            //Form Seeder
            ResearchFormSeeder::class,
            InventionFormSeeder::class,
            ExtensionFormSeeder::class,
            AcademicDevelopmentFormSeeder::class,
            IPCRFormSeeder::class,


            //FIELD Seeder
            FieldSeeder::class,

            //Reports
            ReportTypeSeeder::class,
            ReportCategorySeeder::class,
            ReportColumnSeeder::class,
        ]);
    }
}
