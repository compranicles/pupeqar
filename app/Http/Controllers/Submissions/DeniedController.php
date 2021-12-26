<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Dean;
use App\Models\Report;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Authentication\UserRole;

class DeniedController extends Controller
{
    public function index() {
        
        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $higher_denied_accomplishments = '';
        $department_id = '';
        $college_id = '';
        if(in_array(5, $roles)){
            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
            $departmentHeadOf = Chairperson::select('chairpeople.*', 'departments.name as department_name')->
                join('departments', 'chairpeople.department_id', 'departments.id')->where('user_id', auth()->id())->first();
            $higher_denied_accomplishments = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->where('department_id', $departmentHeadOf->department_id)->where('chairperson_approval', 1)
                ->where('dean_approval', 0)->get();
        }
        if(in_array(6, $roles)){
            $collegeHeadOf = Dean::select('deans.*', 'colleges.name as college_name')->
                join('colleges', 'deans.college_id', 'colleges.id')->where('user_id', auth()->id())->first();
            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
            $reportsDenied = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->where('reports.college_id', $collegeHeadOf->college_id)->where('chairperson_approval', 1)->where('dean_approval', 1)
                ->where('sector_approval', 0)->get();
        }
        if(in_array(7, $roles)){
            $reportsDenied = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('colleges', 'reports.college_id', 'colleges.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->where('chairperson_approval', 1)->where('dean_approval', 1)
                ->where('sector_approval', 1)->where('ipqmso_approval', 0)->get();
        }
        if(in_array(8, $roles)){

        }

        $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->where('reports.chairperson_approval', 0)->orWhere('reports.dean_approval', 0)
                    ->orWhere('reports.sector_approval', 0)->orWhere('reports.ipqmso_approval', 0)->get();


        return view('submissions.denied.index', compact('reported_accomplishments', 'roles', 'department_id', 'college_id', 'higher_denied_accomplishments'));
    }
}
