<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Super Admin
        User::create([
            'email' => 'mugomec@mailinator.com',
            'password'=> Hash::make('primogems#2022-letsgo'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Eru',
            'middle_name' => null,
            'last_name' => 'Eru',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'candy@mailinator.com',
            'password'=> Hash::make('primogems#2022-letsgo'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Candy',
            'middle_name' => null,
            'last_name' => 'Candy',
            'suffix' => null,
        ]);

        //Faculty
        User::create([
            'email' => 'cozy@mailinator.com',
            'password'=> Hash::make('facultyaccount#2022-letsgo'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Cozy',
            'middle_name' => null,
            'last_name' => 'Promise',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'kyhogo@mailinator.com',
            'password'=> Hash::make('facultyaccount#2022-letsgo'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Taylor',
            'middle_name' => null,
            'last_name' => 'Swift',
            'suffix' => null,
        ]);
    }
}
