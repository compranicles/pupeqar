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
                if($row->Lvl == "1"){
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
