<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authentication\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* ************************RESEARCH********************************** */

        Permission::create(['name' => 'view research']);
        Permission::create(['name' => 'add research']);
        Permission::create(['name' => 'edit research']);
        Permission::create(['name' => 'delete research']);

        Permission::create(['name' => 'view completed research']);
        Permission::create(['name' => 'complete research']);
        Permission::create(['name' => 'edit completed research']);
        Permission::create(['name' => 'delete completed research']);

        Permission::create(['name' => 'view presented research']);
        Permission::create(['name' => 'present research']);
        Permission::create(['name' => 'edit presented research']);
        Permission::create(['name' => 'delete presented research']);

        Permission::create(['name' => 'view published research']);
        Permission::create(['name' => 'publish research']);
        Permission::create(['name' => 'edit published research']);
        Permission::create(['name' => 'delete published research']);

        Permission::create(['name' => 'view research copyright']);
        Permission::create(['name' => 'add research copyright']);
        Permission::create(['name' => 'edit research copyright']);
        Permission::create(['name' => 'delete research copyright']);

        Permission::create(['name' => 'view research utilization']);
        Permission::create(['name' => 'add research utilization']);
        Permission::create(['name' => 'edit research utilization']);
        Permission::create(['name' => 'delete research utilization']);

        Permission::create(['name' => 'view research citation']);
        Permission::create(['name' => 'add research citation']);
        Permission::create(['name' => 'edit research citation']);
        Permission::create(['name' => 'delete research citation']);

        Permission::create(['name' => 'defer research']);

    }
}
