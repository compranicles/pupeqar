<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authentication\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Super admin user role
        UserRole::create(['user_id' => '1', 'role_id' => '9']);
        UserRole::create(['user_id' => '2', 'role_id' => '9']);

        //Faculty user role
        UserRole::create(['user_id' => '3', 'role_id' => '1']);
        UserRole::create(['user_id' => '4', 'role_id' => '1']);
    }
}
