<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Dean;
use App\Models\User;
use App\Models\Report;
use App\Models\SectorHead;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\FacultyResearcher;
use Illuminate\Support\Facades\DB;
use App\Models\FacultyExtensionist;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;
use App\Models\Maintenance\ReportCategory;

//MYACCOMPLISHMENTS VIEW
class MySubmissionController extends Controller
{
    public function index(){
        $currentMonth = date('m');

        if ($currentMonth <= 3 && $currentMonth >= 1) 
            $quarter = 1;
        if ($currentMonth <= 6 && $currentMonth >= 4)
            $quarter = 2;
        if ($currentMonth <= 9 && $currentMonth >= 7)
            $quarter = 3;
        if ($currentMonth <= 12 && $currentMonth >= 10) 
            $quarter = 4;

        $year = "default";
        $user = User::find(auth()->id());
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $departments = [];
        $colleges = [];
        $sectors = [];
        $departmentsResearch = [];
        $departmentsExtension = [];
        
        if(in_array(5, $roles)){
            $departments = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
        }
        if(in_array(6, $roles)){
            $colleges = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
                            ->join('colleges', 'colleges.id', 'deans.college_id')->get();
        }
        if(in_array(7, $roles)){
            $sectors = SectorHead::where('sector_heads.user_id', auth()->id())->select('sector_heads.sector_id', 'sectors.code')
                        ->join('sectors', 'sectors.id', 'sector_heads.sector_id')->get();
        }
        if(in_array(10, $roles)){
            $departmentsResearch = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())
                                        ->select('faculty_researchers.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
        }
        if(in_array(11, $roles)){
            $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                        ->select('faculty_extensionists.department_id', 'departments.code')
                                        ->join('departments', 'departments.id', 'faculty_extensionists.department_id')->get();
        }

        $report_categories = ReportCategory::all();
        $my_accomplishments = 
            Report::select(
                            'reports.*', 
                            'report_categories.name as report_category', 
                        )
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->whereYear('reports.updated_at', date('Y'))
                ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter)
                ->where('reports.user_id', auth()->id())
                ->orderBy('reports.updated_at')
                ->get(); //get my individual accomplishment

        //get_department_and_college_name
        $college_names = [];
        $department_names = [];
        foreach($my_accomplishments as $row){
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

        //Get distinct colleges from the colleges that had been reported with repeatedly
        $collegeList = College::get();

        return view(   
            'submissions.myaccomplishments.index', 
            compact( 
                'roles', 
                'colleges', 
                'departments', 
                'my_accomplishments', 
                'college_names', 
                'department_names', 
                'sectors', 'departmentsResearch','departmentsExtension',
                'year', 'quarter', 'report_categories',
                'user',
                'collegeList'
            ));
        
    }

    public function submissionYearFilter($year, $quarter) {
        $report_categories = ReportCategory::all();
        if ($year == "default") {
            return redirect()->route('submissions.myaccomp.index');
        }
        else {
            $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
            $departments = [];
            $colleges = [];
            $sectors = [];
            $departmentsResearch = [];
            $departmentsExtension = [];
            
            if(in_array(5, $roles)){
                $departments = Chairperson::where('chairpeople.user_id', auth()->id())->select('chairpeople.department_id', 'departments.name', 'departments.code')
                                            ->join('departments', 'departments.id', 'chairpeople.department_id')->get();
            }
            if(in_array(6, $roles)){
                $colleges = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.name', 'colleges.code')
                                ->join('colleges', 'colleges.id', 'deans.college_id')->get();
            }
            if(in_array(7, $roles)){
                $sectors = SectorHead::where('sector_heads.user_id', auth()->id())->select('sector_heads.sector_id', 'sectors.code')
                            ->join('sectors', 'sectors.id', 'sector_heads.sector_id')->get();
            }
            if(in_array(10, $roles)){
                $departmentsResearch = FacultyResearcher::where('faculty_researchers.user_id', auth()->id())
                                            ->select('faculty_researchers.department_id', 'departments.code')
                                            ->join('departments', 'departments.id', 'faculty_researchers.department_id')->get();
            }
            if(in_array(11, $roles)){
                $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                            ->select('faculty_extensionists.department_id', 'departments.code')
                                            ->join('departments', 'departments.id', 'faculty_extensionists.department_id')->get();
            }

            $my_accomplishments = 
            Report::select(
                            'reports.*', 
                            'report_categories.name as report_category', 
                            )
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->whereYear('reports.updated_at', $year)
                ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter)
                ->where('reports.user_id', auth()->id())->get(); //get my individual accomplishment

            //get_department_and_college_name
            $college_names = [];
            $department_names = [];
            foreach($my_accomplishments as $row){
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
            }
        return view('submissions.myaccomplishments.index', compact('roles', 'colleges', 'departments', 'my_accomplishments', 'college_names', 'department_names', 'sectors', 'departmentsResearch','departmentsExtension', 'year', 'quarter', 'report_categories'));
    }
}
