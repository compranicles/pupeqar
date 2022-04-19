<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\Sector;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        College::truncate();

        $db_ext = DB::connection('mysql_external');
        $allDepartments = $db_ext->select(" EXEC GetDepartment");
        $sectors = Sector::all(); 
        $sectorHRISCodes = Sector::pluck('hris_code')->all();

        foreach ($allDepartments as $row) {
            if($row->IsActive == "Y"){
                if($row->Level == "1"){
                    if($row->RootID == "0"){
                        if(in_array($row->DepartmentID, $sectorHRISCodes)){
                            $sectorId = Sector::where('hris_code', $row->DepartmentID)->pluck('id')->first();
                            College::insert([
                                'name' => $row->Department,
                                'code' => $row->DepartmentCode,
                                'hris_code' => $row->DepartmentID,
                                'sector_id' => $sectorId,
                            ]);
                        }
                        else{
                            College::insert([
                                'name' => $row->Department,
                                'code' => $row->DepartmentCode,
                                'hris_code' => $row->DepartmentID,
                                'sector_id' => 0
                            ]);
                        }
                    }
                    else{
                        if(in_array($row->RootID, $sectorHRISCodes)){
                            $sectorId = Sector::where('hris_code', $row->RootID)->pluck('id')->first();
                            College::insert([
                                'name' => $row->Department,
                                'code' => $row->DepartmentCode,
                                'hris_code' => $row->DepartmentID,
                                'sector_id' => $sectorId
                            ]);
                        }
                    }
                }
            }
        }

    }
}
