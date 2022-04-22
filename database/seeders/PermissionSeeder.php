<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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
        Schema::disableForeignKeyConstraints();
        Permission::truncate();

        /* ************************USERS********************************** */
        Permission::insert(['name' => 'edit user role', 'group' => '6AUTH']);
        Permission::insert(['name' => 'delete user record', 'group' => '6AUTH']); //3
        Permission::insert(['name' => 'view users', 'group' => '6AUTH']);
        
        /* ************************AUTHENTICATION********************************** */
        Permission::insert(['name' => 'manage roles', 'group' => '6AUTH']);
        Permission::insert(['name' => 'manage permissions', 'group' => '6AUTH']); //5

        /* ************************MAINTENANCES********************************** */
        Permission::insert(['name' => 'manage colleges, branches, and offices', 'group' => '7HRIS']);
        Permission::insert(['name' => 'manage sectors', 'group' => '7HRIS']);
        Permission::insert(['name' => 'manage departments', 'group' => '7HRIS']);
        Permission::insert(['name' => 'manage currencies', 'group' => '8MAINT']);
        Permission::insert(['name' => 'manage dropdowns', 'group' => '8MAINT']);
        Permission::insert(['name' => 'manage research forms', 'group' => '9FORM']);
        Permission::insert(['name' => 'manage invention forms', 'group' => '9FORM']);
        Permission::insert(['name' => 'manage extension program forms', 'group' => '9FORM']); // 13

        /* ************************CONTENT********************************** */
        Permission::insert(['name' => 'manage announcements', 'group' => '10CONT']); //14


        /* ************************RESEARCH********************************** */

        Permission::insert(['name' => 'view all faculty research', 'group' => '1RES']);
        Permission::insert(['name' => 'manage faculty research registration', 'group' => '1RES']);
        Permission::insert(['name' => 'manage faculty research completion', 'group' => '1RES']);
        Permission::insert(['name' => 'manage faculty research presentation', 'group' => '1RES']);
        Permission::insert(['name' => 'manage faculty research publication', 'group' => '1RES']);
        Permission::insert(['name' => 'manage faculty research copyright', 'group' => '1RES']);
        Permission::insert(['name' => 'manage faculty research utilization', 'group' => '1RES']);
        Permission::insert(['name' => 'manage faculty research citation', 'group' => '1RES']);
        Permission::insert(['name' => 'defer research', 'group' => '1RES']); //23

        /* ************************INVENTION, INNOVATION, CREATIVE WORKS********************************** */
        Permission::insert(['name' => 'manage invention, innovation, and creative works', 'group' => '1RES']); //24

        /* ************************EXTENSION PROGRAMS********************************** */
        //EXPERT SERVICES
        Permission::insert(['name' => 'manage expert service in academics', 'group' => '2EXT']);
        Permission::insert(['name' => 'manage expert service in conferences', 'group' => '2EXT']);
        Permission::insert(['name' => 'manage expert service as consultant', 'group' => '2EXT']);
        Permission::insert(['name' => 'manage extension services', 'group' => '2EXT']);
        Permission::insert(['name' => 'manage inter-country mobility', 'group' => '2EXT']);
        Permission::insert(['name' => 'manage partnerships/linkages/networks', 'group' => '2EXT']); 
        Permission::insert(['name' => 'manage community relations and outreach programs', 'group' => '2EXT']); //31
        
        /* ************************ACADEMIC DEVELOPMENT********************************** */
        Permission::insert(['name' => 'manage reference, textbook, module, monographs, IMs', 'group' => '3ACAD']);
        Permission::insert(['name' => 'manage course syllabus', 'group' => '3ACAD']); //33
        
        Permission::insert(['name' => 'manage awards received by college, department, or office', 'group' => '3ACAD']);
        Permission::insert(['name' => 'manage student awards and recognition', 'group' => '3ACAD']);
        Permission::insert(['name' => 'manage attended seminars and trainings by students', 'group' => '3ACAD']);
        Permission::insert(['name' => 'manage technical extension', 'group' => '3ACAD']);
        Permission::insert(['name' => 'manage viable demonstration projects', 'group' => '3ACAD']); //38

        Permission::insert(['name' => 'manage request and queries', 'group' => '4REQ']); //39
        
        /* ************************SUBMISSIONS MODULE********************************** */
        //40
        Permission::insert(['name' => 'finalize submissions', 'group' => '5SUBM']);
        Permission::insert(['name' => 'manage submitted accomplishments', 'group' => '5SUBM']); // My Accomplishments tab
        //42
        Permission::insert(['name' => 'receive individual reports (for consolidation by department)', 'group' => '5SUBM']); //To Receive-Department/s tab
        Permission::insert(['name' => 'manage consolidated reports (by department)', 'group' => '5SUBM']); //[Department]-Accomplishments tab
        //44
        Permission::insert(['name' => 'receive consolidated reports by department (for consolidation by college)', 'group' => '5SUBM']); //To Receive-College/Branch/Campus/Office/s tab
        Permission::insert(['name' => 'manage consolidated reports (by college)', 'group' => '5SUBM']); //[College]-Accomplishments tab
        //46
        Permission::insert(['name' => 'receive consolidated reports by college (for final consolidation)', 'group' => '5SUBM']); //To Receive-Sector tab
        Permission::insert(['name' => 'manage consolidated reports (by all colleges)', 'group' => '5SUBM']); //[Sector Office]-Accomplishments tab
        //48
        Permission::insert(['name' => 'receive consolidated reports by all colleges (for final consolidation)', 'group' => '5SUBM']); //To Receive-IPQMSO tab
        Permission::insert(['name' => 'manage consolidated reports (final)', 'group' => '5SUBM']); //All Accomplishments tab
        //50
        Permission::insert(['name' => 'receive individual reports (for consolidation by research)', 'group' => '5SUBM']); //To Receive-Research tab
        Permission::insert(['name' => 'manage consolidated reports (by research)', 'group' => '5SUBM']); //[Department]-Accomplishments tab
        //52
        Permission::insert(['name' => 'receive individual reports (for consolidation by extension)', 'group' => '5SUBM']); //To Receive-Extensionist tab
        Permission::insert(['name' => 'manage consolidated reports (by extension)', 'group' => '5SUBM']); //[Department]-Accomplishments tab

        /* ************************MAINTENANCES (CONTINUATION)********************************** */
        Permission::insert(['name' => 'manage quarter and year', 'group' => '8MAINT']);
        Permission::insert(['name' => 'manage HRIS forms', 'group' => '9FORM']);
        Permission::insert(['name' => 'manage IPCR forms', 'group' => '9FORM']); //55
        Permission::insert(['name' => 'manage academic module forms', 'group' => '9FORM']); 
        Permission::insert(['name' => 'manage report types', 'group' => '8MAINT']); 
        Permission::insert(['name' => 'manage report generate types', 'group' => '8MAINT']); //58
        Schema::enableForeignKeyConstraints();
    }
}
