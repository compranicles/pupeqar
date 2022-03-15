<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;

class LockController extends Controller
{
    public static function isLocked($id, $reference){
        $currentQuarterYear = Quarter::find(1);

        if(Report::where('report_reference_id', $id)
            ->where('report_category_id', $reference)
            ->where('user_id', auth()->id())
            ->where('report_year', $currentQuarterYear->current_year)
            ->where('report_quarter', $currentQuarterYear->current_quarter)->exists()){
            $data = Report::where('report_reference_id', $id)
                        ->where('report_category_id', $reference)
                        ->where('user_id', auth()->id())
                        ->where('report_year', $currentQuarterYear->current_year)
                        ->where('report_quarter', $currentQuarterYear->current_quarter)
                        ->first();
            
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

        return false;
    }
}
