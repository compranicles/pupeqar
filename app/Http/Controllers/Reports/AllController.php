<?php

namespace App\Http\Controllers\Reports;

use App\Models\Dean;
use App\Models\Report;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;

class AllController extends Controller
{
    public function index(){
        $reportsToReview = Report::select('reports.*', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
        ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
        ->join('users', 'reports.user_id', 'users.id')
        ->where('chairperson_approval', 1)->where('dean_approval', 1)
        ->where('sector_approval', 1)->where('ipqmso_approval', 1)->get();

        $department_name = [];
        $college_name = [];
        foreach($reportsToReview as $report){
            if($report->department_id == null || $report->department_id == 0){
                $department_name[$report->id] = '-';
            }
            else{
                $department_name[$report->id] = Department::where('id', $report->department_id)->pluck('name')->first();
            }
            $college_name[$report->id] = College::where('id', $report->college_id)->pluck('name')->first();
        }

        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $department_id = '';
        $college_id = '';
        if(in_array(5, $roles)){
            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
        }
        // dd($department_id);
        if(in_array(6, $roles)){
            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
        }


        return view('reports.all.index', compact('reportsToReview', 'department_name', 'college_name', 'roles', 'department_id', 'college_id'));
    }
}
