<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Dean;
use App\Models\Report;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Authentication\UserRole;

class AcceptedController extends Controller
{
    public function index(){
         //role and department/ college id
         $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
         $approved_by_me = '';

         if(in_array(5, $roles)){
             $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();

             $approved_by_me = Report::select('reports.*', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->join('users', 'users.id', 'reports.user_id')
                ->where('reports.department_id', $department_id)->where('reports.chairperson_approval', 1)->get();
         }
         elseif(in_array(6, $roles)){
             $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
             $approved_by_me = Report::select('reports.*', 'report_categories.name as report_category', 'departments.name as department_name', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->join('users', 'users.id', 'reports.user_id')->join('departments', 'departments.id', 'reports.department_id')
                ->where('reports.college_id', $college_id)->where('reports.dean_approval', 1)->get();
         }
         elseif(in_array(7, $roles)){
             $approved_by_me = Report::select('reports.*', 'report_categories.name as report_category', 'colleges.name as college_name', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->join('users', 'users.id', 'reports.user_id')->join('departments', 'colleges.id', 'reports.college_id')
                ->where('reports.college_id', $college_id)->where('reports.sector_approval', 1)->get();
         }
         elseif(in_array(8, $roles)){
             $approved_by_me = Report::select('reports.*', 'report_categories.name as report_category', 'colleges.name as college_name', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->join('users', 'users.id', 'reports.user_id')->join('departments', 'colleges.id', 'reports.college_id')
                ->where('reports.college_id', $college_id)->where('reports.ipqmso_approval', 1)->get();
         }
         return view('submissions.approved.index', compact('roles', 'approved_by_me'));
    }
}
