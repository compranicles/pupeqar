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
        Permission::create(['name' => 'add users', 'deleted_at' => date("Y-m-d H:i:s")]); //4

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

        Permission::create(['name' => 'manage request and queries']); //40
        
        /* ************************SUBMISSIONS MODULE********************************** */
        //41
        Permission::create(['name' => 'finalize submissions']);
        Permission::create(['name' => 'manage submitted accomplishments']); // My Accomplishments tab
        //43
        Permission::create(['name' => 'receive individual reports (for consolidation by department)']); //To Receive-Department/s tab
        Permission::create(['name' => 'manage consolidated reports (by department)']); //[Department]-Accomplishments tab
        //45
        Permission::create(['name' => 'receive consolidated reports by department (for consolidation by college)']); //To Receive-College/Branch/Campus/Office/s tab
        Permission::create(['name' => 'manage consolidated reports (by college)']); //[College]-Accomplishments tab
        //47
        Permission::create(['name' => 'receive consolidated reports by college (for final consolidation)']); //To Receive-Sector tab
        Permission::create(['name' => 'manage consolidated reports (by all colleges)']); //[Sector Office]-Accomplishments tab
        //49
        Permission::create(['name' => 'receive consolidated reports by all colleges (for final consolidation)']); //To Receive-IPQMSO tab
        Permission::create(['name' => 'manage consolidated reports (final)']); //All Accomplishments tab
        //51
        Permission::create(['name' => 'receive individual reports (for consolidation by research)']); //To Receive-Research tab
        Permission::create(['name' => 'manage consolidated reports (by research)']); //[Department]-Accomplishments tab
        //53
        Permission::create(['name' => 'receive individual reports (for consolidation by extension)']); //To Receive-Extensionist tab
        Permission::create(['name' => 'manage consolidated reports (by extension)']); //[Department]-Accomplishments tab
    }
}
