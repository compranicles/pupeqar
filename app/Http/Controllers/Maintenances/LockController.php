<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LockController extends Controller
{
    public static function isLocked($id, $reference){
        if(Report::where('report_reference_id', $id)->where('report_category_id', $reference)->where('user_id', auth()->id())->exists()){
            $data = Report::where('report_reference_id', $id)
                        ->where('report_category_id', $reference)
                        ->where('user_id', auth()->id())
                        ->select(
                            DB::raw('reports.*, QUARTER(reports.updated_at) as quarter, YEAR(reports.updated_at) as year')
                        )
                        ->first();
            $currentMonth = date('m');
            if ($currentMonth <= 3 && $currentMonth >= 1) 
                $quarter = 1;
            if ($currentMonth <= 6 && $currentMonth >= 4)
                $quarter = 2;
            if ($currentMonth <= 9 && $currentMonth >= 7)
                $quarter = 3;
            if ($currentMonth <= 12 && $currentMonth >= 10) 
                $quarter = 4;

            $currentYear = date('Y');
            
            if($quarter == $data->quarter && $currentYear == $data->year){
                $flag = 1;
                
                if($data->report_category_id >= 1 && $data->report_category_id <= 7)
                    if($data->researcher_approval == "0")
                        $flag = 0;
                
                if($data->report_category_id == 12)
                    if($data->extensionist_approval == "0")
                        $flag = 0;

                if($data->chairperson_approval == "0")
                    $flag = 0;
                
                    
                if($data->dean_approval == "0")
                    $flag = 0;
                    
                    
                if($data->sector_approval == "0")
                    $flag = 0;
                    
                    
                if($data->ipqmso_approval == "0")
                    $flag = 0;
                
                if($flag == 1)
                    return true;
            }
            
        }

        return false;

    }
}
