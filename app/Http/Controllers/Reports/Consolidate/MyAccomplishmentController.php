<?php

namespace App\Http\Controllers\Reports\Consolidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Associate,
    Chairperson,
    Dean,
    Employee,
    FacultyExtensionist,
    FacultyResearcher,
    Report,
    SectorHead,
    User,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
    Maintenance\ReportCategory,
};
use App\Models\Authentication\UserRole;
use App\Services\ManageConsolidatedReportAuthorizationService;

class MyAccomplishmentController extends Controller
{
    public function index() {
        $authorize = (new ManageConsolidatedReportAuthorizationService())->authorizeManageConsolidatedIndividualReports();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $currentQuarterYear = Quarter::find(1);
        $quarter = $currentQuarterYear->current_quarter;
        $year = $currentQuarterYear->current_year;

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
                                        ->select('faculty_researchers.college_id', 'colleges.code')
                                        ->join('colleges', 'colleges.id', 'faculty_researchers.college_id')->get();
        }
        if(in_array(11, $roles)){
            $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                        ->select('faculty_extensionists.college_id', 'colleges.code')
                                        ->join('colleges', 'colleges.id', 'faculty_extensionists.college_id')->get();
        }
        if(in_array(12, $roles)){
            $colleges = Associate::where('associates.user_id', auth()->id())->select('associates.college_id', 'colleges.code')
                            ->join('colleges', 'colleges.id', 'associates.college_id')->get();
        }
        if(in_array(13, $roles)){
            $sectors = Associate::where('associates.user_id', auth()->id())->select('associates.sector_id', 'sectors.code')
                        ->join('sectors', 'sectors.id', 'associates.sector_id')->get();
        }

        $report_categories = ReportCategory::all();
        $my_accomplishments =
            Report::select(
                            'reports.*',
                            'report_categories.name as report_category',
                        )
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->where('reports.report_year', $year)
                ->where('reports.report_quarter', $quarter)
                ->where('reports.user_id', auth()->id())
                ->orderBy('reports.updated_at', 'DESC')
                ->get(); //get my individual accomplishment

        //get_department_and_college_name
        $college_names = [];
        $department_names = [];
        foreach($my_accomplishments as $row){
            $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
            $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();

            $row->report_details = json_decode($row->report_details, false);

            if($temp_college_name == null)
                $college_names[$row->id] = '-';
            else
                $college_names[$row->id] = $temp_college_name->name;
            if($temp_department_name == null)
                $department_names[$row->id] = '-';
            else
            $department_names[$row->id] = $temp_department_name->name;
        }

        //Get distinct colleges from the colleges that had been reported with repeatedly
        $collegeList = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        return view(
            'reports.consolidate.myaccomplishments',
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


    public function individualReportYearFilter($year, $quarter) {
        $authorize = (new ManageConsolidatedReportAuthorizationService())->authorizeManageConsolidatedIndividualReports();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $report_categories = ReportCategory::all();
        if ($year == "default") {
            return redirect()->route('submissions.myaccomp.index');
        }
        else {
            $user = User::find(auth()->id());
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
                                            ->select('faculty_researchers.college_id', 'colleges.code')
                                            ->join('colleges', 'colleges.id', 'faculty_researchers.college_id')->get();
            }
            if(in_array(11, $roles)){
                $departmentsExtension = FacultyExtensionist::where('faculty_extensionists.user_id', auth()->id())
                                            ->select('faculty_extensionists.college_id', 'colleges.code')
                                            ->join('colleges', 'colleges.id', 'faculty_extensionists.college_id')->get();
            }

            $my_accomplishments =
            Report::select(
                            'reports.*',
                            'report_categories.name as report_category',
                            )
                ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                ->where('reports.report_year', $year)
                ->where('reports.report_quarter', $quarter)
                ->where('reports.user_id', auth()->id())->get(); //get my individual accomplishment

            //get_department_and_college_name
            $college_names = [];
            $department_names = [];
            foreach($my_accomplishments as $row){
                $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
                $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();

                $row->report_details = json_decode($row->report_details, false);
                if($temp_college_name == null)
                    $college_names[$row->id] = '-';
                else
                    $college_names[$row->id] = $temp_college_name->name;
                if($temp_department_name == null)
                    $department_names[$row->id] = '-';
                else
                $department_names[$row->id] = $temp_department_name->name;


                }
            }

        //Get distinct colleges from the colleges that had been reported with repeatedly
        $collegeList = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        return view('reports.consolidate.myaccomplishments', compact('roles', 'colleges', 'departments', 'my_accomplishments', 'college_names', 'department_names', 'sectors', 'departmentsResearch','departmentsExtension', 'user', 'collegeList', 'year', 'quarter', 'report_categories'));
    }
}
