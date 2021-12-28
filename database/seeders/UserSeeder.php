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
        User::truncate();
        User::create([
            'email' => 'mugomec@mailinator.com',
            'password'=> Hash::make('mugomec@mailinator.com'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Super Admin',
            'middle_name' => null,
            'last_name' => 'Super Admin',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'candy@mailinator.com',
            'password'=> Hash::make('candy@mailinator.com'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Super Admin',
            'middle_name' => null,
            'last_name' => 'Super Admin',
            'suffix' => null,
        ]);

        //Faculty
        User::create([
            'email' => 'cozy@mailinator.com',
            'password'=> Hash::make('cozy@mailinator.com'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Faculty',
            'middle_name' => null,
            'last_name' => 'Faculty',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'kyhogo@mailinator.com',
            'password'=> Hash::make('kyhogo@mailinator.com'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Faculty',
            'middle_name' => null,
            'last_name' => 'Faculty',
            'suffix' => null,
        ]);

        for ($f = 1; $f <= 11; $f++) {
            User::create([
                'email' => 'faculty'.$f.'@mailinator.com',
                'password'=> Hash::make('faculty'.$f.'@mailinator.com'),
                'date_of_birth' => 1992-04-24,
                'first_name' => 'Faculty',
                'middle_name' => null,
                'last_name' => 'Faculty',
                'suffix' => null,
            ]);
        }

        //Faculty with designation
        User::create([
            'email' => 'loey@mailinator.com',
            'password'=> Hash::make('loey@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Faculty Designate',
            'middle_name' => null,
            'last_name' => 'Faculty Designate',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'miro@mailinator.com',
            'password'=> Hash::make('miro@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Faculty Designate',
            'middle_name' => null,
            'last_name' => 'Faculty Designate',
            'suffix' => null,
        ]);

        for ($i = 1; $i <= 11; $i++) {
            User::create([
                'email' => 'facultydesignate'.$i.'@mailinator.com',
                'password'=> Hash::make('facultydesignate'.$i.'@mailinator.com'),
                'date_of_birth' => 1992-04-24,
                'first_name' => 'Faculty with Designate',
                'middle_name' => null,
                'last_name' => 'Faculty with Designate',
                'suffix' => null,
            ]);
        }

        //Admin Employee
        User::create([
            'email' => 'harry@mailinator.com',
            'password'=> Hash::make('harry@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Admin Employee',
            'middle_name' => null,
            'last_name' => 'Admin Employee',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'mira@mailinator.com',
            'password'=> Hash::make('mira@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Admin Employee',
            'middle_name' => null,
            'last_name' => 'Admin Employee',
            'suffix' => null,
        ]);

        for ($a = 1; $a <= 11; $a++) {
            User::create([
                'email' => 'admin'.$a.'@mailinator.com',
                'password'=> Hash::make('admin'.$a.'@mailinator.com'),
                'date_of_birth' => 1992-04-24,
                'first_name' => 'Admin Employee',
                'middle_name' => null,
                'last_name' => 'Admin Employee',
                'suffix' => null,
            ]);
        }

        //Admin with Teaching Load
        User::create([
            'email' => 'gola@mailinator.com',
            'password'=> Hash::make('gola@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Admin Teaching',
            'middle_name' => null,
            'last_name' => 'Admin Teaching',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'sorita@mailinator.com',
            'password'=> Hash::make('sorita@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Admin Teaching',
            'middle_name' => null,
            'last_name' => 'Admin Teaching',
            'suffix' => null,
        ]);

        for ($at = 3; $at <= 11; $at++) {
            User::create([
                'email' => 'adminteaching'.$at.'@mailinator.com',
                'password'=> Hash::make('adminteaching'.$at.'@mailinator.com'),
                'date_of_birth' => 1992-04-24,
                'first_name' => 'Admin Teaching',
                'middle_name' => null,
                'last_name' => 'Admin Teaching',
                'suffix' => null,
            ]);
        }

        //Chairperson
        User::create([
            'email' => 'lucas@mailinator.com',
            'password'=> Hash::make('lucas@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Chairperson',
            'middle_name' => null,
            'last_name' => 'Chairperson',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'miku@mailinator.com',
            'password'=> Hash::make('miku@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Chairperson',
            'middle_name' => null,
            'last_name' => 'Chairperson',
            'suffix' => null,
        ]);

        //Director/Dean
        User::create([
            'email' => 'direk@mailinator.com',
            'password'=> Hash::make('direk@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Dean',
            'middle_name' => null,
            'last_name' => 'Dean',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'teresita@mailinator.com',
            'password'=> Hash::make('teresita@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Dean',
            'middle_name' => null,
            'last_name' => 'Dean',
            'suffix' => null,
        ]);

        //VP/Sector head
        User::create([
            'email' => 'yulo@mailinator.com',
            'password'=> Hash::make('yulo@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Sector Head',
            'middle_name' => null,
            'last_name' => 'Sector Head',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'ramen@mailinator.com',
            'password'=> Hash::make('ramen@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Sector Head',
            'middle_name' => null,
            'last_name' => 'Sector Head',
            'suffix' => null,
        ]);

        //IPQMSO
        User::create([
            'email' => 'goya@mailinator.com',
            'password'=> Hash::make('goya@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'IPQMSO',
            'middle_name' => null,
            'last_name' => 'IPQMSO',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'lego@mailinator.com',
            'password'=> Hash::make('lego@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'IPQMSO',
            'middle_name' => null,
            'last_name' => 'IPQMSO',
            'suffix' => null,
        ]);
    }
}
