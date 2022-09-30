<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\IndividualAccomplishmentReportExport;
use App\Exports\DepartmentConsolidatedAccomplishmentReportExport;
use App\Exports\DepartmentLevelConsolidatedExport;
use App\Exports\CollegeConsolidatedAccomplishmentReportExport;
use App\Exports\CollegeLevelConsolidatedExport;
use App\Exports\IPOAccomplishmentReportExport;
use App\Exports\SectorAccomplishmentReportExport;
use App\Models\{
    Chairperson,
    Dean,
    FacultyExtensionist,
    FacultyResearcher,
    Report,
    SectorHead,
    User,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\GenerateColumn,
    Maintenance\GenerateTable,
    Maintenance\Sector,
};
use Maatwebsite\Excel\Facades\Excel;

class GenerateController extends Controller
{
    public function index($id, Request $request){
        $data = '';
        $source_type = '';
        if($request->input("type") == "academic"){
            if($request->input("level") == "individual"){
                // $source_type = "individual";
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.last_name as name')->first();
            }
            elseif($request->input("level") == "department"){
                // $source_type = "department";
                $department_id = $id;
                $data = Department::where('id', $department_id)->first();
            }
            elseif($request->input("level") == "college"){
                // $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
            }
            elseif($request->input("level") == "sector"){
                // $source_type = "sector";
                $data = Sector::where('id', $request->sector)->first();
            }
        }
        elseif($request->input("type") == "admin"){
            if($request->input("level") == "individual"){
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.last_name as name')->first();
            }
            elseif($request->input("level") == "department"){
                $department_id = $id;
                $data = Department::where('id', $department_id)->first();
            }
            elseif($request->input("level") == "college"){
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
            }
            elseif($request->input("level") == "sector"){
                // $source_type = "sector";
                $data = Sector::where('id', $request->sector)->first();
            }
        }
        elseif($request->input("type") == "chair_chief"){
            if($request->input("level") == "department_wide"){
                // $source_type = "individual";
                $data = Department::where('id', $request->input("department_id"))->first();
            }
        }
        elseif($request->input("type") == "dean_director"){
            if($request->input("level") == "college_wide"){
                // $source_type = "individual";
                $college_id = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
                        ->join('colleges', 'colleges.id', 'deans.college_id')
                        ->whereNull('deans.deleted_at')->pluck('deans.college_id')->first();
                $data = College::where('id', $request->input("college_id"))->first();
            }
        }

        $level = $request->input("level");
        $type = $request->input('type');
        $yearGenerate = $request->input('year_generate');
        $quarterGenerate = $request->input('quarter_generate');
        $college = College::where('id', $request->input('cbco'))->first();
        $fileSuffix = '';

        // dd($level);
        if ($level == "individual") {
            if ($type == "admin" || $type == "academic") {
                $cbco = $request->input('cbco');
                $fileSuffix = strtoupper($request->input("type")).'-QAR-'.$college->code.'-'.$data->name.'-Q'.$quarterGenerate.'-Y'.$yearGenerate;
                $departmentIDs = Department::where('college_id', $cbco)->pluck('id')->all();
                /* */
                $director = Dean::join('users', 'users.id', 'deans.user_id')->where('deans.college_id', $cbco)->first('users.*');
                $getCollege = College::where('colleges.id', $cbco)->first('colleges.*');
                $sectorHead = SectorHead::join('users', 'users.id', 'sector_heads.user_id')->where('sector_heads.sector_id', $getCollege->sector_id)->first('users.*');
                $getSector = Sector::where('id', $getCollege->sector_id)->first();
                return Excel::download(new IndividualAccomplishmentReportExport(
                    $level,
                    $type,
                    $yearGenerate,
                    $quarterGenerate,
                    $cbco,
                    $userID = $id,
                    $getCollege,
                    $getSector,
                    $director,
                    $sectorHead,
                ),
                    $fileSuffix.'.xlsx');
            }
        } 
        elseif ($level == "department_wide") {
            $fileSuffix = 'DEPT-WIDE-QAR-'.$data->code.'-Q'.$request->input('dw_quarter').'-Y'.$request->input('dw_year');
            return Excel::download(new DepartmentLevelConsolidatedExport(
                $level,
                $type,
                $quarterGenerateLevel = $request->input('dw_quarter'),
                $yearGenerateLevel = $request->input('dw_year'),
                $departmentID = $data->id,
                $departmentName = $data->name,
                ),
                $fileSuffix.'.xlsx');
        } elseif ($level == "college_wide") {
            $fileSuffix = 'COLLEGE-WIDE-QAR-'.$data->code.'-Q'.$request->input('cw_quarter').'-'.$request->input('cw_year');
            return Excel::download(new CollegeLevelConsolidatedExport(
                $level,
                $type,
                $quarterGenerateLevel = $request->input('cw_quarter'),
                $yearGenerateLevel = $request->input('cw_year'),
                $collegeID = $data->id,
                $collegeName = $data->name,
                ),
                $fileSuffix.'.xlsx');
        }
        elseif ($level == "department") {
            if ($request->input("type") == "academic")
                $fileSuffix = 'DEPT-QAR-'.$data->code.'-Q'.$quarterGenerate.'-Y'.$yearGenerate;
            elseif ($request->input("type") == "admin")
                $fileSuffix = 'SECTION-QAR-'.$data->code.'-Q'.$quarterGenerate.'-Y'.$yearGenerate;
            $facultyResearcher = FacultyResearcher::join('users', 'users.id', 'faculty_researchers.user_id')->where('faculty_researchers.college_id', $data->id)->first('users.*');
            $facultyExtensionist = FacultyExtensionist::join('users', 'users.id', 'faculty_extensionists.user_id')->where('faculty_extensionists.college_id', $data->id)->first('users.*');
            return Excel::download(new DepartmentConsolidatedAccomplishmentReportExport(
                $level,
                $type,
                $yearGenerate,
                $quarterGenerate,
                $departmentID = $data->id,
                $getDepartment = $data->name,
                $facultyResearcher,
                $facultyExtensionist,
                ),
                $fileSuffix.'.xlsx');

        }
        elseif ($level == "college") {
            if ($request->input("type") == "academic")
                $fileSuffix = 'COLLEGE-QAR-'.$data->code.'-Q'.$request->input('quarter_generate').'-Y'.$request->input('year_generate');
            elseif ($request->input("type") == "admin")
                $fileSuffix = 'OFFICE-QAR-'.$data->code.'-Q'.$request->input('quarter_generate').'-Y'.$request->input('year_generate');
            $departments = Department::where('college_id', $data->id)->pluck('id')->all();
            /* */
            return Excel::download(new CollegeConsolidatedAccomplishmentReportExport(
                $level,
                $type,
                $quarterGenerate = $request->input('quarter_generate'),
                $yearGenerate = $request->input('year_generate'),
                $collegeID = $data->id,
                $collegeName = $data->name,
                $facultyResearcher = FacultyResearcher::join('users', 'users.id', 'faculty_researchers.user_id')->where('faculty_researchers.college_id', $data->id)->first('users.*'),
                $facultyExtensionist = FacultyExtensionist::join('users', 'users.id', 'faculty_extensionists.user_id')->where('faculty_extensionists.college_id', $data->id)->first('users.*')
                ),
                $fileSuffix.'.xlsx');
        }
        elseif($level == "sector"){
            $sector = $data;
            $type = $request->type;
            $q1 = $request->from_quarter_generate;
            $q2 = $request->to_quarter_generate;
            $year = $request->yearGenerate;
            $asked = 'no one';

            $previousUrl = url()->previous();
            $url = explode('/', $previousUrl);
            if(in_array('sector', $url)){
                $asked = 'sector';
            }
            elseif(in_array('all', $url)){
                $asked = 'ipo';
                $q1 = $request->from_quarter_generate2;
                $q2 = $request->to_quarter_generate2;
                $year = $request->year_generate2;
            }
            $fileSuffix = strtoupper($type).'-SECTOR-QAR-'.$data->code.'-Q-'.$q1.'-Q'.$q2.'-Y'.$year;

            // dd($request->_previous->url);

            // if($request->routeIs('reports.consolidate.ipqmso')) {
            //     $asked = 'ipo';
            // }
            // elseif($request->routeIs('reports.consolidate.sector')) {
            //     $asked = 'sector';
            // }
            return Excel::download(new SectorAccomplishmentReportExport(
                $type,
                $q1,
                $q2,
                $year,
                $sector,
                $asked,
            ),
            $fileSuffix.'.xlsx');
        }
        elseif($level == 'ipo'){
            $type = $request->type;
            $q1 = $request->from_quarter_generate2;
            $q2 = $request->to_quarter_generate2;
            $year = $request->year_generate2;
            $fileSuffix = 'IPO-LEVEL-QAR-'.strtoupper($type).'-Q'.$q1.'-Q'.$q2.'-Y'.$year;

            return Excel::download(new IPOAccomplishmentReportExport(
                $type,
                $q1,
                $q2,
                $year,
            ),
            $fileSuffix.'.xlsx');
        }
    }

    public function documentView($reportID){
        $reportDocuments = json_decode(Report::where('id', $reportID)->pluck('report_documents')->first(), true);
        return view('reports.generate.document', compact('reportDocuments'));
    }
}
