<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Models\Maintenance\Sector;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
   public function index() { 
        $db_ext = DB::connection('mysql_external');
        // phpinfo();
        $departments = $db_ext->select("EXEC GetDepartment");
        // dd($departments);

        $departmentIDs = []; 
        $count = 0;

        foreach($departments as $row){
            $departmentIDs[$count] = $row->DepartmentID;
            $count++;
        }
        
        $sectorHRISCode = Sector::pluck('hris_code')->all();
        $allDepartments = $db_ext->select(" EXEC GetDepartment");
        dd(Sector::where('hris_code', $allDepartments[282]->DepartmentID)->pluck('id')->first());
    }
}
