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
            'first_name' => 'Eru',
            'middle_name' => null,
            'last_name' => 'Eru',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'candy@mailinator.com',
            'password'=> Hash::make('candy@mailinator.com'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Candy',
            'middle_name' => null,
            'last_name' => 'Candy',
            'suffix' => null,
        ]);

        //Faculty
        User::create([
            'email' => 'cozy@mailinator.com',
            'password'=> Hash::make('cozy@mailinator.com'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Cozy',
            'middle_name' => null,
            'last_name' => 'Promise',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'kyhogo@mailinator.com',
            'password'=> Hash::make('kyhogo@mailinator.com'),
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Taylor',
            'middle_name' => null,
            'last_name' => 'Swift',
            'suffix' => null,
        ]);

        //Faculty with designation
        User::create([
            'email' => 'loey@mailinator.com',
            'password'=> Hash::make('loey@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Loey',
            'middle_name' => null,
            'last_name' => 'Well',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'miro@mailinator.com',
            'password'=> Hash::make('miro@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Miro',
            'middle_name' => null,
            'last_name' => 'Sales',
            'suffix' => null,
        ]);

        //Admin Employee
        User::create([
            'email' => 'harry@mailinator.com',
            'password'=> Hash::make('harry@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Harry',
            'middle_name' => null,
            'last_name' => 'Styles',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'mira@mailinator.com',
            'password'=> Hash::make('mira@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Mira',
            'middle_name' => null,
            'last_name' => 'Sola',
            'suffix' => null,
        ]);

        //Admin with Teaching Load
        User::create([
            'email' => 'gola@mailinator.com',
            'password'=> Hash::make('gola@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Gola',
            'middle_name' => null,
            'last_name' => 'Bamonos',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'sorita@mailinator.com',
            'password'=> Hash::make('sorita@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Sorita',
            'middle_name' => null,
            'last_name' => 'Sen',
            'suffix' => null,
        ]);

        //Chairperson
        User::create([
            'email' => 'lucas@mailinator.com',
            'password'=> Hash::make('lucas@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Lucas',
            'middle_name' => null,
            'last_name' => 'Ynes',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'miku@mailinator.com',
            'password'=> Hash::make('miku@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Miku',
            'middle_name' => null,
            'last_name' => 'Reyes',
            'suffix' => null,
        ]);

        //Director/Dean
        User::create([
            'email' => 'direk@mailinator.com',
            'password'=> Hash::make('direk@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Direk',
            'middle_name' => null,
            'last_name' => 'Juano',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'teresita@mailinator.com',
            'password'=> Hash::make('teresita@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Teresita',
            'middle_name' => null,
            'last_name' => 'Malo',
            'suffix' => null,
        ]);

        //VP/Sector head
        User::create([
            'email' => 'yulo@mailinator.com',
            'password'=> Hash::make('yulo@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Yulo',
            'middle_name' => null,
            'last_name' => 'Maki',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'ramen@mailinator.com',
            'password'=> Hash::make('ramen@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Ramen',
            'middle_name' => null,
            'last_name' => 'Noodles',
            'suffix' => null,
        ]);

        //IPQMSO
        User::create([
            'email' => 'goya@mailinator.com',
            'password'=> Hash::make('goya@mailinator.com'),
            'date_of_birth' => 2000-04-24,
            'first_name' => 'Goya',
            'middle_name' => null,
            'last_name' => 'Chocolate',
            'suffix' => null,
        ]);

        User::create([
            'email' => 'lego@mailinator.com',
            'password'=> Hash::make('lego@mailinator.com'),
            'date_of_birth' => 1998-04-24,
            'first_name' => 'Lego',
            'middle_name' => null,
            'last_name' => 'Blocks',
            'suffix' => null,
        ]);
    }
}
