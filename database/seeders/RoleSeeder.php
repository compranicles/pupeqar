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
        Role::insert(['name' => 'Chairperson/Chief']);
        Role::insert(['name' => 'Director/Dean']);
        Role::insert(['name' => 'VP/Sector Head']); //7
        Role::insert(['name' => 'IPO Staff']);
        Role::insert(['name' => 'Super Admin']);
        Role::insert(['name' => 'Researcher']);
        Role::insert(['name' => 'Extensionist']); //11
        Role::insert(['name' => 'Associate/Assistant Dean/Director']); //12
        Role::insert(['name' => 'Assistant to VP']); //13
    }
}
