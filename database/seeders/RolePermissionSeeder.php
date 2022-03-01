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
        /***** Super admin role permissions *****/
        RolePermission::truncate();
        for ($i = 1; $i <= 15; $i++) {
            RolePermission::create(['role_id' => '9', 'permission_id' => $i]);
        }

        /***** Faculty role permissions *****/
        for ($f = 16; $f <= 31; $f++) {
            RolePermission::create(['role_id' => '1', 'permission_id' => $f]);
        }
        RolePermission::create(['role_id' => '1', 'permission_id' => 33]);
        RolePermission::create(['role_id' => '1', 'permission_id' => 34]);
        RolePermission::create(['role_id' => '1', 'permission_id' => 42]);
        
        /***** Admin role permissions *****/
        for ($a = 16; $a <= 34; $a++) {
            RolePermission::create(['role_id' => '3', 'permission_id' => $a]);
        }
        RolePermission::create(['role_id' => '3', 'permission_id' => 42]);

        /***** Faculty Researcher role permissions *****/
        RolePermission::create(['role_id' => '10', 'permission_id' => 51]);
        RolePermission::create(['role_id' => '10', 'permission_id' => 52]);

        /***** Faculty Extensionist role permissions *****/
        RolePermission::create(['role_id' => '11', 'permission_id' => 53]);
        RolePermission::create(['role_id' => '11', 'permission_id' => 54]);

        /***** Chairperson role permissions *****/
        RolePermission::create(['role_id' => '5', 'permission_id' => 32]);
        for ($c = 35; $c <= 39; $c++) {
            RolePermission::create(['role_id' => '5', 'permission_id' => $c]);
        }
        RolePermission::create(['role_id' => '5', 'permission_id' => 42]);
        RolePermission::create(['role_id' => '5', 'permission_id' => 43]);
        RolePermission::create(['role_id' => '5', 'permission_id' => 44]);

        /***** Dean/Director role permissions *****/
        RolePermission::create(['role_id' => '6', 'permission_id' => 32]);
        for ($d = 35; $d <= 42; $d++) {
            RolePermission::create(['role_id' => '6', 'permission_id' => $d]);
        }
        RolePermission::create(['role_id' => '6', 'permission_id' => 45]);
        RolePermission::create(['role_id' => '6', 'permission_id' => 46]);

        /***** Sector role permissions *****/
        RolePermission::create(['role_id' => '7', 'permission_id' => 47]);
        RolePermission::create(['role_id' => '7', 'permission_id' => 48]);

        /***** IPQMSO role permissions *****/
        RolePermission::create(['role_id' => '8', 'permission_id' => 49]);
        RolePermission::create(['role_id' => '8', 'permission_id' => 50]);


        


        


    }
}