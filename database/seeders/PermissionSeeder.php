<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Authentication\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();

        /* ************************USERS********************************** */
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit user role']);
        Permission::create(['name' => 'delete user record']);
        Permission::create(['name' => 'add users']); //4

        /* ************************AUTHENTICATION********************************** */
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']); //6

        /* ************************MAINTENANCES********************************** */
        Permission::create(['name' => 'manage colleges, branches, and offices']);
        Permission::create(['name' => 'manage departments']);
        Permission::create(['name' => 'manage currencies']);
        Permission::create(['name' => 'manage dropdowns']);
        Permission::create(['name' => 'manage research forms']);
        Permission::create(['name' => 'manage invention forms']);
        Permission::create(['name' => 'manage extension program forms']);
        Permission::create(['name' => 'manage extension program forms']); //14

        /* ************************CONTENT********************************** */
        Permission::create(['name' => 'manage announcements']); //15


        /* ************************RESEARCH********************************** */

        Permission::create(['name' => 'view all faculty research']);
        Permission::create(['name' => 'manage faculty research registration']);
        Permission::create(['name' => 'manage faculty research completion']);
        Permission::create(['name' => 'manage faculty research presentation']);
        Permission::create(['name' => 'manage faculty research publication']);
        Permission::create(['name' => 'manage faculty research copyright']);
        Permission::create(['name' => 'manage faculty research utilization']);
        Permission::create(['name' => 'manage faculty research citation']);
        Permission::create(['name' => 'defer research']); //24

        /* ************************INVENTION, INNOVATION, CREATIVE WORKS********************************** */
        Permission::create(['name' => 'manage invention, innovation, and creative works']); //25

        /* ************************EXTENSION PROGRAMS********************************** */
        //EXPERT SERVICES
        Permission::create(['name' => 'manage expert service in academics']);
        Permission::create(['name' => 'manage expert service in conferences']);
        Permission::create(['name' => 'manage expert service as consultant']);
        Permission::create(['name' => 'manage extension services']);
        Permission::create(['name' => 'manage inter-country mobility']);
        Permission::create(['name' => 'manage partnerships/linkages/networks']); 
        Permission::create(['name' => 'manage community relations and outreach programs']); //32
        
        /* ************************ACADEMIC DEVELOPMENT********************************** */
        Permission::create(['name' => 'manage reference, textbook, module, monographs, IMs']);
        Permission::create(['name' => 'manage course syllabus']); //34
        
        Permission::create(['name' => 'manage awards received by college, department, or office']);
        Permission::create(['name' => 'manage student awards and recognition']);
        Permission::create(['name' => 'manage attended seminars and trainings by students']);
        Permission::create(['name' => 'manage technical extension']);
        Permission::create(['name' => 'manage viable demonstration projects']); //39

        Permission::create(['name' => 'manage request and queries']);
        











    }
}
