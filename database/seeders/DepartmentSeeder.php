<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Department::truncate();

        $db_ext = DB::connection('mysql_external');
        $allDepartments = $db_ext->select(" EXEC GetDepartment");
        $collegeHRISCodes = College::pluck('id')->all();

        foreach ($allDepartments as $row) {
            if($row->IsActive == "Y"){
                if($row->Level == "1" && $row->RootID == '226'){
                    if(in_array($row->DepartmentID, $collegeHRISCodes)){
                        Department::insert([
                            'id' => $row->DepartmentID,
                            'name' => $row->Department,
                            'code' => $row->DepartmentCode,
                            'college_id' => College::where('id', $row->DepartmentID)->pluck('id')->first()
                        ]);
                    }
                }
                elseif($row->Level == '2'){
                    if(in_array($row->RootID, $collegeHRISCodes)){
                        Department::insert([
                            'id' => $row->DepartmentID,
                            'name' => $row->Department,
                            'code' => $row->DepartmentCode,
                            'college_id' => College::where('id', $row->RootID)->pluck('id')->first()
                        ]);
                    }
                }
            }
        }
    }
}
