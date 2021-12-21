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
        for ($f = 5; $f <= 15; $f++) {
            UserRole::create(['user_id' => $f, 'role_id' => '1']);
        }

        //Faculty with designation user role
        UserRole::create(['user_id' => '16', 'role_id' => '2']);
        UserRole::create(['user_id' => '17', 'role_id' => '2']);
        for ($fd = 18; $fd <= 28; $fd++) {
            UserRole::create(['user_id' => $fd, 'role_id' => '2']);
        }

        //Admin employee user role
        UserRole::create(['user_id' => '29', 'role_id' => '3']);
        UserRole::create(['user_id' => '30', 'role_id' => '3']);
        for ($a = 31; $a <= 41; $a++) {
            UserRole::create(['user_id' => $a, 'role_id' => '3']);
        }

        //Admin with teaching load user role
        UserRole::create(['user_id' => '42', 'role_id' => '4']);
        UserRole::create(['user_id' => '43', 'role_id' => '4']);
        for ($at = 44; $at <= 54; $at++) {
            UserRole::create(['user_id' => $at, 'role_id' => '4']);
        }

        //Chairperson user role
        UserRole::create(['user_id' => '55', 'role_id' => '5']);
        UserRole::create(['user_id' => '56', 'role_id' => '5']);

        //Director/Dean user role
        UserRole::create(['user_id' => '57', 'role_id' => '6']);
        UserRole::create(['user_id' => '58', 'role_id' => '6']);

        //VP/Sector head user role
        UserRole::create(['user_id' => '59', 'role_id' => '7']);
        UserRole::create(['user_id' => '60', 'role_id' => '7']);

        //IPQMSO user role
        UserRole::create(['user_id' => '17', 'role_id' => '8']);
        UserRole::create(['user_id' => '18', 'role_id' => '8']);
    }
}
