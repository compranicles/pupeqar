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
        for ($f = 16; $f <= 39; $f++) {
            RolePermission::create(['role_id' => '1', 'permission_id' => $f]);
        }





        


    }
}