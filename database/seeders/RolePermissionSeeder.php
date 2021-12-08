<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authentication\RolePermission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Super admin role permissions
        RolePermission::truncate();
        for ($i = 1; $i <= 15; $i++) {
            RolePermission::create(['role_id' => '9', 'permission_id' => $i]);
        }

        //Faculty role permissions
        for ($f = 16; $f <= 31; $f++) {
            RolePermission::create(['role_id' => '1', 'permission_id' => $f]);
            RolePermission::create(['role_id' => '2', 'permission_id' => $f]);
        }

        RolePermission::create(['role_id' => '1', 'permission_id' => 33]);
        RolePermission::create(['role_id' => '1', 'permission_id' => 34]);
        RolePermission::create(['role_id' => '2', 'permission_id' => 33]);
        RolePermission::create(['role_id' => '2', 'permission_id' => 34]);

        //Admin role permissions
        for ($a = 16; $a <= 34; $a++) {
            RolePermission::create(['role_id' => '3', 'permission_id' => $a]);
            RolePermission::create(['role_id' => '4', 'permission_id' => $a]);
        }

        RolePermission::create(['role_id' => '3', 'permission_id' => 35]);
        RolePermission::create(['role_id' => '3', 'permission_id' => 39]);
        RolePermission::create(['role_id' => '4', 'permission_id' => 35]);
        RolePermission::create(['role_id' => '4', 'permission_id' => 39]);

        //Chairperson role permissions
        RolePermission::create(['role_id' => '5', 'permission_id' => 32]);
        for ($c = 35; $c <= 39; $c++) {
            RolePermission::create(['role_id' => '5', 'permission_id' => $c]);
        }

        RolePermission::create(['role_id' => '6', 'permission_id' => 32]);
        for ($d = 35; $d <= 39; $d++) {
            RolePermission::create(['role_id' => '6', 'permission_id' => $d]);
        }

        


        


    }
}