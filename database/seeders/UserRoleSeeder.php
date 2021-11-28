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
        UserRole::truncate();
        //Super admin user role
        UserRole::create(['user_id' => '1', 'role_id' => '9']);
        UserRole::create(['user_id' => '2', 'role_id' => '9']);

        //Faculty user role
        UserRole::create(['user_id' => '3', 'role_id' => '1']);
        UserRole::create(['user_id' => '4', 'role_id' => '1']);

        //Faculty with designation user role
        UserRole::create(['user_id' => '5', 'role_id' => '2']);
        UserRole::create(['user_id' => '6', 'role_id' => '2']);

        //Admin employee user role
        UserRole::create(['user_id' => '7', 'role_id' => '3']);
        UserRole::create(['user_id' => '8', 'role_id' => '3']);

        //Admin with teaching load user role
        UserRole::create(['user_id' => '9', 'role_id' => '4']);
        UserRole::create(['user_id' => '10', 'role_id' => '4']);

        //Chairperson user role
        UserRole::create(['user_id' => '11', 'role_id' => '5']);
        UserRole::create(['user_id' => '12', 'role_id' => '5']);

        //Director/Dean user role
        UserRole::create(['user_id' => '13', 'role_id' => '6']);
        UserRole::create(['user_id' => '14', 'role_id' => '6']);

        //VP/Sector head user role
        UserRole::create(['user_id' => '15', 'role_id' => '7']);
        UserRole::create(['user_id' => '16', 'role_id' => '7']);

        //IPQMSO user role
        UserRole::create(['user_id' => '17', 'role_id' => '8']);
        UserRole::create(['user_id' => '18', 'role_id' => '8']);
    }
}
