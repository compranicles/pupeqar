<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        Role::insert(['name' => 'Faculty Employee']);
        Role::insert(['name' => 'Faculty with designation', 'deleted_at' => date("Y-m-d H:i:s")]); //Inactive
        Role::insert(['name' => 'Admin Employee']);
        Role::insert(['name' => 'Admin with teaching load', 'deleted_at' => date("Y-m-d H:i:s")]); //Inactive
        Role::insert(['name' => 'Chairperson']);
        Role::insert(['name' => 'Director/Dean']);
        Role::insert(['name' => 'VP/Sector Head']); //7
        Role::insert(['name' => 'IPO']);
        Role::insert(['name' => 'Super Admin']);
        Role::insert(['name' => 'Researcher']);
        Role::insert(['name' => 'Extensionist']); //11
    }
}
