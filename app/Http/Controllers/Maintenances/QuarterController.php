<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;

class QuarterController extends Controller
{
    public function index(){
        $quarter = Quarter::find(1);
        return view('maintenances.quarter.index', compact('quarter')); 
    }

    public function update(Request $request){
        $quarter = Quarter::find(1);
        $data = [
            'current_quarter' => $request->current_quarter,
            'current_year' => $request->current_year
        ];
        if(empty($quarter))
            Quarter::create($data);
        else
            $quarter->update($data);
        return redirect()->route('maintenance.quarter.index')->with('success', 'Quarter and Year updated successfully');
    }
}
