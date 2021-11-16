<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authentication\Permission;

class PermissionSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* ************************COMMITTED WORKS********************************** */

        Permission::create(['name' => 'manage faculty committed works']); //20

        /* ************************REPORTS********************************** */

        Permission::create(['name' => 'manage faculty reports']);
        Permission::create(['name' => 'manage chairperson reports']);
        Permission::create(['name' => 'manage director/dean reports']);
        Permission::create(['name' => 'manage sector head reports']);
        Permission::create(['name' => 'manage IPQMSO reports']);
        Permission::create(['name' => 'view all reports']); //26


        
    }
}
