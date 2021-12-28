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
        $denied_by_me = '';
        $department_id = '';
        $college_id = '';
        if(in_array(1, $roles)||in_array(2, $roles)||in_array(3, $roles)||in_array(4, $roles) ){
            $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->where('reports.chairperson_approval', 0)->get();
        }
        if(in_array(5, $roles)){
            $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                    ->where('reports.chairperson_approval', 0)->where('reports.dean_approval', 0)->orWhere('reports.sector_approval', 0)->get();

            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();

            $departmentHeadOf = Chairperson::select('chairpeople.*', 'departments.name as department_name')->
                join('departments', 'chairpeople.department_id', 'departments.id')->where('user_id', auth()->id())->first();

            $denied_by_me = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category' , 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->join('users', 'users.id', 'reports.user_id')
                ->where('reports.college_id', $department_id)->
                where('reports.chairperson_approval', 0)->get();

            $higher_denied_accomplishments = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->where('department_id', $departmentHeadOf->department_id)->where('chairperson_approval', 1)
                ->where('dean_approval', 0)->get();
        }
        if(in_array(6, $roles)){
            $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->where('reports.chairperson_approval', 0)->where('reports.dean_approval', 0)
                    ->where('reports.sector_approval', 0)->orwhere('reports.ipqmso_approval', 0)->get();

            $collegeHeadOf = Dean::select('deans.*', 'colleges.name as college_name')->
                join('colleges', 'deans.college_id', 'colleges.id')->where('user_id', auth()->id())->first();

            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();

            $denied_by_me = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category' , 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->join('users', 'users.id', 'reports.user_id')
                ->where('reports.college_id', $college_id)->
                where('reports.dean_approval', 0)->get();

            $higher_denied_accomplishments = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->where('reports.college_id', $collegeHeadOf->college_id)->where('chairperson_approval', 1)->where('dean_approval', 1)
                ->where('sector_approval', 0)->get();
        }
        if(in_array(7, $roles)){
            $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->where('reports.chairperson_approval', 0)->where('reports.dean_approval', 0)
                    ->where('reports.sector_approval', 0)->where('reports.ipqmso_approval')->get();

            $denied_by_me = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category' , 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                    ->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->join('users', 'users.id', 'reports.user_id')->
                    where('reports.sector_approval', 0)->get();
            
            $higher_denied_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('departments', 'reports.department_id', 'departments.id')
                ->join('colleges', 'reports.college_id', 'colleges.id')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'reports.user_id', 'users.id')
                ->where('chairperson_approval', 1)->where('dean_approval', 1)
                ->where('sector_approval', 1)->where('ipqmso_approval', 0)->get();
        }

        return view('submissions.denied.index', compact('reported_accomplishments', 'roles', 'department_id', 'college_id', 'higher_denied_accomplishments', 'denied_by_me'));
    }
}
