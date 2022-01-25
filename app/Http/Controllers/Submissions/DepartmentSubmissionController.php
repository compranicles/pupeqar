<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Dean;
use App\Models\Report;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;

class DepartmentSubmissionController extends Controller
{
    public function index($id){
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $departments = [];
        $colleges = [];
        $currentMonth = date('m');
        $year = "default";
        if ($currentMonth <= 3 && $currentMonth >= 1) 
            $quarter = 1;
        if ($currentMonth <= 6 && $currentMonth >= 4)
            $quarter = 2;
        if ($currentMonth <= 9 && $currentMonth >= 7)
            $quarter = 3;
        if ($currentMonth <= 12 && $currentMonth >= 10) 
            $quarter = 4;

        // $sector_ids = [];
        
        if(in_array(5, $roles)){
            $departments = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.name')
                                        ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
        }
        if(in_array(6, $roles)){
            $colleges = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.name')
                            ->join('colleges', 'colleges.id', 'deans.college_id')->get();
        }
        // if(in_array(7, $roles)){

        // }

        $department_accomps = 
            Report::select(
                            'reports.*', 
                            'report_categories.name as report_category', 
                            'users.last_name', 
                            'users.first_name',
                            'users.middle_name', 
                            'users.suffix'
                          )
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->join('users', 'users.id', 'reports.user_id')
                ->where('reports.department_id', $id)->get();

        //get_department_and_college_name
        $college_names = [];
        $department_names = [];
        foreach($department_accomps as $row){
            $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
            $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();


            if($temp_college_name == null)
                $college_names[$row->id] = '-';
            else
                $college_names[$row->id] = $temp_college_name;
            if($temp_department_name == null)
                $department_names[$row->id] = '-';
            else
                $department_names[$row->id] = $temp_department_name;
        }
        

        //departmentdetails
        $department = Department::find($id);

        return view(
                    'submissions.departmentaccomplishments.index', 
                    compact('roles', 'departments', 'colleges', 'department_accomps', 'department' , 'department_names', 'college_names', 'quarter', 'year')
                );
    }
}
