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
        Permission::insert(['name' => 'view users']);
        Permission::insert(['name' => 'edit user role']);
        Permission::insert(['name' => 'delete user record']);
        Permission::insert(['name' => 'add users', 'deleted_at' => date("Y-m-d H:i:s")]); //4

        /* ************************AUTHENTICATION********************************** */
        Permission::insert(['name' => 'manage roles']);
        Permission::insert(['name' => 'manage permissions']); //6

        /* ************************MAINTENANCES********************************** */
        Permission::insert(['name' => 'manage colleges, branches, and offices']);
        Permission::insert(['name' => 'manage departments']);
        Permission::insert(['name' => 'manage currencies']);
        Permission::insert(['name' => 'manage dropdowns']);
        Permission::insert(['name' => 'manage research forms']);
        Permission::insert(['name' => 'manage invention forms']);
        Permission::insert(['name' => 'manage extension program forms']);
        Permission::insert(['name' => 'manage extension program forms']); //14

        /* ************************CONTENT********************************** */
        Permission::insert(['name' => 'manage announcements']); //15


        /* ************************RESEARCH********************************** */

        Permission::insert(['name' => 'view all faculty research']);
        Permission::insert(['name' => 'manage faculty research registration']);
        Permission::insert(['name' => 'manage faculty research completion']);
        Permission::insert(['name' => 'manage faculty research presentation']);
        Permission::insert(['name' => 'manage faculty research publication']);
        Permission::insert(['name' => 'manage faculty research copyright']);
        Permission::insert(['name' => 'manage faculty research utilization']);
        Permission::insert(['name' => 'manage faculty research citation']);
        Permission::insert(['name' => 'defer research']); //24

        /* ************************INVENTION, INNOVATION, CREATIVE WORKS********************************** */
        Permission::insert(['name' => 'manage invention, innovation, and creative works']); //25

        /* ************************EXTENSION PROGRAMS********************************** */
        //EXPERT SERVICES
        Permission::insert(['name' => 'manage expert service in academics']);
        Permission::insert(['name' => 'manage expert service in conferences']);
        Permission::insert(['name' => 'manage expert service as consultant']);
        Permission::insert(['name' => 'manage extension services']);
        Permission::insert(['name' => 'manage inter-country mobility']);
        Permission::insert(['name' => 'manage partnerships/linkages/networks']); 
        Permission::insert(['name' => 'manage community relations and outreach programs']); //32
        
        /* ************************ACADEMIC DEVELOPMENT********************************** */
        Permission::insert(['name' => 'manage reference, textbook, module, monographs, IMs']);
        Permission::insert(['name' => 'manage course syllabus']); //34
        
        Permission::insert(['name' => 'manage awards received by college, department, or office']);
        Permission::insert(['name' => 'manage student awards and recognition']);
        Permission::insert(['name' => 'manage attended seminars and trainings by students']);
        Permission::insert(['name' => 'manage technical extension']);
        Permission::insert(['name' => 'manage viable demonstration projects']); //39

        Permission::insert(['name' => 'manage request and queries']); //40
        
        /* ************************SUBMISSIONS MODULE********************************** */
        //41
        Permission::insert(['name' => 'finalize submissions']);
        Permission::insert(['name' => 'manage submitted accomplishments']); // My Accomplishments tab
        //43
        Permission::insert(['name' => 'receive individual reports (for consolidation by department)']); //To Receive-Department/s tab
        Permission::insert(['name' => 'manage consolidated reports (by department)']); //[Department]-Accomplishments tab
        //45
        Permission::insert(['name' => 'receive consolidated reports by department (for consolidation by college)']); //To Receive-College/Branch/Campus/Office/s tab
        Permission::insert(['name' => 'manage consolidated reports (by college)']); //[College]-Accomplishments tab
        //47
        Permission::insert(['name' => 'receive consolidated reports by college (for final consolidation)']); //To Receive-Sector tab
        Permission::insert(['name' => 'manage consolidated reports (by all colleges)']); //[Sector Office]-Accomplishments tab
        //49
        Permission::insert(['name' => 'receive consolidated reports by all colleges (for final consolidation)']); //To Receive-IPQMSO tab
        Permission::insert(['name' => 'manage consolidated reports (final)']); //All Accomplishments tab
        //51
        Permission::insert(['name' => 'receive individual reports (for consolidation by research)']); //To Receive-Research tab
        Permission::insert(['name' => 'manage consolidated reports (by research)']); //[Department]-Accomplishments tab
        //53
        Permission::insert(['name' => 'receive individual reports (for consolidation by extension)']); //To Receive-Extensionist tab
        Permission::insert(['name' => 'manage consolidated reports (by extension)']); //[Department]-Accomplishments tab
    }
}
