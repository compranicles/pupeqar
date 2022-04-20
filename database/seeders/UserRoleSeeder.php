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
//         //Super admin user role
//         UserRole::insert(['user_id' => '1', 'role_id' => '9']);
//         UserRole::insert(['user_id' => '2', 'role_id' => '9']);

//         //Faculty user role
//         for ($f = 3; $f <= 13; $f++) {
//             UserRole::insert(['user_id' => $f, 'role_id' => '1']);
//         }
// //15
//         //Faculty with designation user role
//         UserRole::insert(['user_id' => '16', 'role_id' => '2']);
//         UserRole::insert(['user_id' => '17', 'role_id' => '2']);
//         for ($fd = 18; $fd <= 28; $fd++) {
//             UserRole::insert(['user_id' => $fd, 'role_id' => '2']);
//         }
// //28
//         //Admin employee user role
//         UserRole::insert(['user_id' => '29', 'role_id' => '3']);
//         UserRole::insert(['user_id' => '30', 'role_id' => '3']);
//         for ($a = 31; $a <= 41; $a++) {
//             UserRole::insert(['user_id' => $a, 'role_id' => '3']);
//         }
// //41
//         //Admin with teaching load user role
//         UserRole::insert(['user_id' => '42', 'role_id' => '4']);
//         UserRole::insert(['user_id' => '43', 'role_id' => '4']);
//         for ($at = 44; $at <= 54; $at++) {
//             UserRole::insert(['user_id' => $at, 'role_id' => '4']);
//         }
// //54
//         //Chairperson user role
//         UserRole::insert(['user_id' => '55', 'role_id' => '5']);
//         UserRole::insert(['user_id' => '56', 'role_id' => '5']);
// //56
//         //Director/Dean user role
//         UserRole::insert(['user_id' => '57', 'role_id' => '6']);
//         UserRole::insert(['user_id' => '58', 'role_id' => '6']);

//         //VP/Sector head user role
//         UserRole::insert(['user_id' => '59', 'role_id' => '7']);
//         UserRole::insert(['user_id' => '60', 'role_id' => '7']);

//         //IPQMSO user role
//         UserRole::insert(['user_id' => '61', 'role_id' => '8']);
//         UserRole::insert(['user_id' => '62', 'role_id' => '8']);

//         //FacultyExtensionist User ROles
//         UserRole::insert(['user_id' => '63', 'role_id' => '11']);
//         UserRole::insert(['user_id' => '64', 'role_id' => '11']);

//         //FacultyResearchers UserRoles
//         UserRole::insert(['user_id' => '65', 'role_id' => '10']);
//         UserRole::insert(['user_id' => '66', 'role_id' => '10']);
    }
}
