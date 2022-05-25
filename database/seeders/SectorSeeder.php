<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Maintenance\Sector;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sector::truncate();
        
        $db_ext = DB::connection('mysql_external');
        $sectors = $db_ext->select(" EXEC GetDepartment 'Y'");

        foreach ($sectors as $sector){
            Sector::insert([
                'name' => $sector->Department,
                'code' => $sector->DepartmentCode,
                'hris_code' =>$sector->DepartmentID,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
