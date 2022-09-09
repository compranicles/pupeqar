<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Services\SingleAuthorizationService;

class QuarterController extends Controller
{
    public function index(){
        $authorize = (new SingleAuthorizationService())->authorizeManageQuarterAndYear();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $quarter = Quarter::find(1);
        return view('maintenances.quarter.index', compact('quarter')); 
    }

    public function update(Request $request){
        $authorize = (new SingleAuthorizationService())->authorizeManageQuarterAndYear();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        
        $quarter = Quarter::find(1);
        $data = [
            'current_quarter' => $request->current_quarter,
            'current_year' => $request->current_year,
            'deadline' => $request->deadline
        ];

        if(empty($quarter))
            Quarter::create($data);
        else
            $quarter->update($data);

        // Announcement::create([
        //     'subject' => "QAR Submission Deadline for Quarter ".$request->current_quarter." of Year ".$request->current_year."."
        // ]);

        return redirect()->route('maintenance.quarter.index')->with('success', 'Quarter and Year updated successfully');
    }
}
