<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authentication\RolePermission;

class RolePermissionSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*****************REPORTS**************************/

        //Faculty role permissions
        RolePermission::create(['role_id' => '1', 'permission_id' => '20']);
        RolePermission::create(['role_id' => '1', 'permission_id' => '21']);

        //Chairperson role permissions
        RolePermission::create(['role_id' => '5', 'permission_id' => '22']);

        //Dean role permissions
        RolePermission::create(['role_id' => '6', 'permission_id' => '23']);

        //Dean role permissions
        RolePermission::create(['role_id' => '7', 'permission_id' => '24']);

        //IPQMSO role permissions
        RolePermission::create(['role_id' => '8', 'permission_id' => '25']);
        RolePermission::create(['role_id' => '8', 'permission_id' => '26']);
    }
}