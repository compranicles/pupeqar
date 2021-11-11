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
        User::create([
            'email' => 'mugomec@mailinator.com',
            'password'=> Hash::make('12345678'),
            'role_id' => 3,
            'date_of_birth' => 1992-04-24,
            'first_name' => 'Earl Janiel',
            'middle_name' => 'Fernando',
            'last_name' => 'Compra',
            'suffix' => null,
        ]);
    }
}
