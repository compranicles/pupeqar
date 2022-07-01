<?php

namespace App\Http\Controllers\Reports\Consolidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Chairperson,
    Dean,
    FacultyExtensionist,
    FacultyResearcher,
    Report,
    SectorHead,
    Authentication\UserRole,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
    Maintenance\Sector,
};
use App\Services\ManageConsolidatedReportAuthorizationService;

class SectorConsolidatedController extends Controller
{
    public function index($id){
        $authorize = (new ManageConsolidatedReportAuthorizationService())->authorizeManageConsolidatedReportsBySector();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $departments = [];
        $colleges = [];
        $sectors = [];
        $departmentsResearch = [];
        $departmentsExtension = [];
        $currentQuarterYear = Quarter::find(1);
        $quarter = $currentQuarterYear->current_quarter;
        $year = $currentQuarterYear->current_year;

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

        $sector_accomps =
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
                ->where('reports.report_year', $year)
                ->where('reports.report_quarter', $quarter)
                ->where('reports.sector_id', $id)
                ->orderBy('reports.updated_at', 'DESC')
                ->get();

        //get_department_and_college_name
        $college_names = [];
        $department_names = [];
        foreach($sector_accomps as $row){
            $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
            // $temp_college_name = College::where('id', $row->college_id)->first();
            $row->report_details = json_decode($row->report_details, false);
            $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();

            // $temp_department_name = $temp_college_name->department()->where('id', $row->department_id)->pluck('name')->first();
            // dd($temp_department_name);
            if($temp_college_name == null)
                $college_names[$row->id] = '-';
            else
                $college_names[$row->id] = $temp_college_name->name;
            if($temp_department_name == null)
                $department_names[$row->id] = '-';
            else
            $department_names[$row->id] = $temp_department_name->name;
        }

        //SectorDetails
        $sector = Sector::find($id);

        return view(
                    'reports.consolidate.sector',
                    compact('roles', 'departments', 'colleges', 'sector_accomps', 'sector', 'department_names', 'college_names', 'sectors', 'departmentsResearch','departmentsExtension', 'quarter', 'year')
                );
    }

    public function sectorReportYearFilter($sector, $year, $quarter) {
        $authorize = (new ManageConsolidatedReportAuthorizationService())->authorizeManageConsolidatedReportsBySector();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }

        if ($year == "default") {
            return redirect()->route('reports.consolidate.sector');
        } else{
        }
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

        $sector_accomps =
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
                ->where('reports.report_year', $year)
                ->where('reports.report_quarter', $quarter)
                ->where('reports.sector_id', $sector)->get();

        //get_department_and_college_name
        $college_names = [];
        $department_names = [];
        foreach($sector_accomps as $row){
            $temp_college_name = College::select('name')->where('id', $row->college_id)->first();
            // $temp_college_name = College::where('id', $row->college_id)->first();
            $row->report_details = json_decode($row->report_details, false);
            $temp_department_name = Department::select('name')->where('id', $row->department_id)->first();

            // $temp_department_name = $temp_college_name->department()->where('id', $row->department_id)->pluck('name')->first();
            // dd($temp_department_name);
            if($temp_college_name == null)
                $college_names[$row->id] = '-';
            else
                $college_names[$row->id] = $temp_college_name->name;
            if($temp_department_name == null)
                $department_names[$row->id] = '-';
            else
            $department_names[$row->id] = $temp_department_name->name;
        }

        //SectorDetails
        $sector = Sector::find($sector);

        $collegesOfSector = College::where('sector_id', $sector)->get();

        return view(
                    'reports.consolidate.sector',
                    compact('roles', 'departments', 'colleges', 'sector_accomps', 'sector', 'department_names', 'college_names', 'sectors', 'collegesOfSector', 'departmentsResearch','departmentsExtension', 'quarter', 'year')
                );
    }
}
