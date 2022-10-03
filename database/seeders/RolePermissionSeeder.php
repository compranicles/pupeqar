<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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
        Schema::enableForeignKeyConstraints();

        /***** Super admin role permissions *****/
        RolePermission::truncate();
        for ($i = 1; $i <= 14; $i++) {
            RolePermission::insert(['role_id' => '9', 'permission_id' => $i]);
        }
        RolePermission::insert(['role_id' => '9', 'permission_id' => 54]);
        RolePermission::insert(['role_id' => '9', 'permission_id' => 55]);
        RolePermission::insert(['role_id' => '9', 'permission_id' => 56]);
        RolePermission::insert(['role_id' => '9', 'permission_id' => 57]);
        RolePermission::insert(['role_id' => '9', 'permission_id' => 58]);
        RolePermission::insert(['role_id' => '9', 'permission_id' => 59]);

        /***** Faculty role permissions *****/
        for ($f = 15; $f <= 30; $f++) {
            RolePermission::insert(['role_id' => '1', 'permission_id' => $f]);
        }
        RolePermission::insert(['role_id' => '1', 'permission_id' => 32]);
        RolePermission::insert(['role_id' => '1', 'permission_id' => 33]);
        RolePermission::insert(['role_id' => '1', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '1', 'permission_id' => 60]);
        RolePermission::insert(['role_id' => '1', 'permission_id' => 62]);
        RolePermission::insert(['role_id' => '1', 'permission_id' => 63]);
        RolePermission::insert(['role_id' => '1', 'permission_id' => 65]);
        
        /***** Admin role permissions *****/
        for ($a = 15; $a <= 30; $a++) {
            RolePermission::insert(['role_id' => '3', 'permission_id' => $a]);
            RolePermission::insert(['role_id' => '3', 'permission_id' => 32]);
            RolePermission::insert(['role_id' => '3', 'permission_id' => 33]);
        }
        // RolePermission::insert(['role_id' => '3', 'permission_id' => 39]);
        RolePermission::insert(['role_id' => '3', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '3', 'permission_id' => 61]);
        RolePermission::insert(['role_id' => '3', 'permission_id' => 62]);
        RolePermission::insert(['role_id' => '3', 'permission_id' => 63]);
        RolePermission::insert(['role_id' => '3', 'permission_id' => 65]);

        /***** Faculty Researcher role permissions *****/
        RolePermission::insert(['role_id' => '10', 'permission_id' => 50]);
        RolePermission::insert(['role_id' => '10', 'permission_id' => 51]);

        /***** Faculty Extensionist role permissions *****/
        RolePermission::insert(['role_id' => '11', 'permission_id' => 52]);
        RolePermission::insert(['role_id' => '11', 'permission_id' => 53]);

        /***** Chairperson role permissions *****/
        RolePermission::insert(['role_id' => '5', 'permission_id' => 29]);
        RolePermission::insert(['role_id' => '5', 'permission_id' => 31]);
        for ($c = 34; $c <= 39; $c++) {
            RolePermission::insert(['role_id' => '5', 'permission_id' => $c]);
        }
        RolePermission::insert(['role_id' => '5', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '5', 'permission_id' => 42]);
        RolePermission::insert(['role_id' => '5', 'permission_id' => 43]);
        RolePermission::insert(['role_id' => '5', 'permission_id' => 63]);
        RolePermission::insert(['role_id' => '5', 'permission_id' => 64]);
        RolePermission::insert(['role_id' => '5', 'permission_id' => 66]);
        RolePermission::insert(['role_id' => '5', 'permission_id' => 69]);

        /***** Dean/Director role permissions *****/
        RolePermission::insert(['role_id' => '6', 'permission_id' => 29]);
        RolePermission::insert(['role_id' => '6', 'permission_id' => 31]);
        for ($d = 34; $d <= 39; $d++) {
            RolePermission::insert(['role_id' => '6', 'permission_id' => $d]);
        }
        RolePermission::insert(['role_id' => '6', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '6', 'permission_id' => 44]);
        RolePermission::insert(['role_id' => '6', 'permission_id' => 45]);
        RolePermission::insert(['role_id' => '6', 'permission_id' => 63]);
        RolePermission::insert(['role_id' => '6', 'permission_id' => 64]);
        RolePermission::insert(['role_id' => '6', 'permission_id' => 66]);
        RolePermission::insert(['role_id' => '6', 'permission_id' => 68]);

        /***** Sector role permissions *****/
        RolePermission::insert(['role_id' => '7', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '7', 'permission_id' => 46]);
        RolePermission::insert(['role_id' => '7', 'permission_id' => 47]);

        /***** IPQMSO role permissions *****/
        RolePermission::insert(['role_id' => '8', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '8', 'permission_id' => 48]);
        RolePermission::insert(['role_id' => '8', 'permission_id' => 49]);
        RolePermission::insert(['role_id' => '8', 'permission_id' => 67]);

        /***** Associate/Assistant Dean/Director *****/
        RolePermission::insert(['role_id' => '12', 'permission_id' => 29]);
        RolePermission::insert(['role_id' => '12', 'permission_id' => 31]);
        for ($d = 34; $d <= 39; $d++) {
            RolePermission::insert(['role_id' => '12', 'permission_id' => $d]);
        }
        RolePermission::insert(['role_id' => '12', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '12', 'permission_id' => 44]);
        RolePermission::insert(['role_id' => '12', 'permission_id' => 45]);
        RolePermission::insert(['role_id' => '12', 'permission_id' => 63]);
        RolePermission::insert(['role_id' => '12', 'permission_id' => 64]);
        RolePermission::insert(['role_id' => '12', 'permission_id' => 66]);
        RolePermission::insert(['role_id' => '12', 'permission_id' => 68]);

        /***** Assistant to VP *****/
        RolePermission::insert(['role_id' => '13', 'permission_id' => 41]);
        RolePermission::insert(['role_id' => '13', 'permission_id' => 46]);
        RolePermission::insert(['role_id' => '13', 'permission_id' => 47]);

        Schema::disableForeignKeyConstraints();

    }
}
