<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Chairperson;
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
//4
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
//15
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
                'first_name' => 'Faculty Designate',
                'middle_name' => null,
                'last_name' => 'Faculty Designate',
                'suffix' => null,
            ]);
        }
//28
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
//41
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

        for ($at = 1; $at <= 11; $at++) {
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
//54
        //Chairperson
        $cp1 = User::create([
            'email' => 'lucas@mailinator.com',
            'password'=> Hash::make('lucas@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Chairperson-CAF-Accountancy',
            'middle_name' => null,
            'last_name' => 'Chairperson-CAF-Accountancy',
            'suffix' => null,
        ]);

        Chairperson::create([
            'user_id' => $cp1->id,
            'department_id' => 1,
            'college_id' => 1,
        ]);


        $cp2 = User::create([
            'email' => 'miku@mailinator.com',
            'password'=> Hash::make('miku@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Chairperson-Taguig',
            'middle_name' => null,
            'last_name' => 'Chairperson-Taguig',
            'suffix' => null,
        ]);

        Chairperson::create([
            'user_id' => $cp2->id,
            'department_id' => 42,
            'college_id' => 42,
        ]);
//56
        //Director/Dean
        User::create([
            'email' => 'direk@mailinator.com',
            'password'=> Hash::make('direk@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Dean-CAF',
            'middle_name' => null,
            'last_name' => 'Dean-CAF',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'teresita@mailinator.com',
            'password'=> Hash::make('teresita@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Dean-Taguig',
            'middle_name' => null,
            'last_name' => 'Dean-Taguig',
            'suffix' => null,
        ]);
//58
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
//60
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
//62
    }
}
