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
use App\Models\{
    Chairperson,
    Dean,
    FacultyExtensionist,
    FacultyResearcher,
    Report,
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
        $reportFormat = $request->input("type_generate");
        $data = '';

        $source_type = '';
        if($request->input("type_generate") == "academic"){
            if($request->input("source_generate") == "department"){
                $source_type = "department";
                $department_id = $id;
                $data = Department::where('id', $department_id)->first();
            }
            elseif($request->input("source_generate") == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
            }
            elseif($request->input("source_generate") == "my"){
                $source_type = "individual";
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, '')) as name"))->first();
            }
        }
        elseif($request->input("type_generate") == "admin"){
            if($request->input("source_generate") == "department"){
                $source_type = "department";
                $department_id = $id;
                $data = Department::where('id', $department_id)->first();
            }
            elseif($request->input("source_generate") == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
            }
            elseif($request->input("source_generate") == "my"){
                $source_type = "individual";
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, '')) as name"))->first();   
            }
        }
        elseif($request->input("type_generate") == "department_level"){
            if($request->input("source_generate") == "my"){
                $source_type = "individual";
                $department_id = Chairperson::where('chairpeople.user_id', auth()->id())
                        ->join('departments', 'departments.id', 'chairpeople.department_id')
                        ->whereNull('chairpeople.deleted_at')->pluck('chairpeople.department_id')->first();
                $data = Department::where('id', $department_id)->first();
            }
        }
        elseif($request->input("type_generate") == "college_level"){
            if($request->input("source_generate") == "my"){
                $source_type = "individual";
                $college_id = Dean::where('deans.user_id', auth()->id())->select('deans.college_id', 'colleges.code')
                        ->join('colleges', 'colleges.id', 'deans.college_id')
                        ->whereNull('deans.deleted_at')->pluck('deans.college_id')->first();
                $data = College::where('id', $college_id)->first();
            }
        }
        
        $source_generate = $request->input("source_generate");
        $year_generate = $request->input('year_generate');
        $quarter_generate = $request->input('quarter_generate');
        $college = College::where('id', $request->input('cbco'))->first();
        $file_suffix = '';

        if ($source_generate == "my") {
            if ($request->input('type_generate') == "admin" || $request->input('type_generate') == "academic") {
                $cbco = $request->input('cbco');
                $file_suffix = $data->name.'_'.$college->code.'_'.ucfirst($request->input("type_generate")).'_'.$year_generate.'_'.$quarter_generate.'_Individual_QAR';
                $departments = Department::where('college_id', $cbco)->pluck('id')->all();
                /* */
                $director = User::join('deans', 'deans.user_id', 'users.id')->where('deans.college_id', $cbco)->whereNull('deans.deleted_at')->first('users.*');
                $get_college = College::where('colleges.id', $cbco)->join('sectors', 'sectors.id', 'colleges.sector_id')->select('colleges.*')->first();
                $get_sector = Sector::where('id', $get_college->sector_id)->first();
                $sector_head = User::join('sector_heads', 'sector_heads.user_id', 'users.id')->where('sector_heads.sector_id', $get_college->sector_id)->whereNull('sector_heads.deleted_at')->first('users.*');
                return Excel::download(new IndividualAccomplishmentReportExport(
                    $source_type, 
                    $reportFormat, 
                    $source_generate,
                    $year_generate,
                    $quarter_generate,
                    $cbco, 
                    $id, 
                    $get_college,
                    $get_sector,
                    $director,
                    $sector_head,
                ),
                    $file_suffix.'.xlsx');
            } elseif ($request->input("type_generate") == "department_level") {
                $file_suffix = $data->code.'_'.$request->input('year_generate_level').'_'.$quarter_generate.'_Consolidated_Department_Level_QAR';
                $faculty_researcher = User::join('faculty_researchers', 'faculty_researchers.user_id', 'users.id')->where('faculty_researchers.department_id', $data->id)->whereNull('faculty_researchers.deleted_at')->first('users.*');
                $faculty_extensionist = User::join('faculty_extensionists', 'faculty_extensionists.user_id', 'users.id')->where('faculty_extensionists.department_id', $data->id)->whereNull('faculty_extensionists.deleted_at')->first('users.*');
                return Excel::download(new DepartmentLevelConsolidatedExport(
                    $source_type, 
                    $reportFormat, 
                    $source_generate,
                    $year_generate_level = $request->input('year_generate_level'),
                    $quarter_generate,
                    $department = $data->id, 
                    $id, 
                    $departmentName = $data->name,
                    $faculty_researcher,
                    $faculty_extensionist,
                    ),
                    $file_suffix.'.xlsx');
            } elseif ($request->input("type_generate") == "college_level") {
                $file_suffix = $data->code.'_'.$request->input('year_generate_level').'_'.$quarter_generate.'_Consolidated_College_Level_QAR';
                return Excel::download(new CollegeLevelConsolidatedExport(
                    $source_type, 
                    $reportFormat, 
                    $source_generate,
                    $year_generate_level = $request->input('year_generate_level'),
                    $quarter_generate,
                    $id, 
                    $college = $data->id,
                    $collegeName = $data->name,
                    ),
                    $file_suffix.'.xlsx');
            }

        } elseif ($source_generate == "department") {
            $file_suffix = $data->code.'_'.ucfirst($request->input("type_generate")).'_'.$year_generate.'_'.$quarter_generate.'_Consolidated_Department_QAR';
            $faculty_researcher = User::join('faculty_researchers', 'faculty_researchers.user_id', 'users.id')->where('faculty_researchers.department_id', $data->id)->whereNull('faculty_researchers.deleted_at')->first('users.*');
            $faculty_extensionist = User::join('faculty_extensionists', 'faculty_extensionists.user_id', 'users.id')->where('faculty_extensionists.department_id', $data->id)->whereNull('faculty_extensionists.deleted_at')->first('users.*');
            return Excel::download(new DepartmentConsolidatedAccomplishmentReportExport(
                $source_type, 
                $reportFormat, 
                $source_generate,
                $year_generate,
                $quarter_generate,
                $department = $data->id, 
                $id, 
                $get_department = $data->name,
                $faculty_researcher,
                $faculty_extensionist,
                ),
                $file_suffix.'.xlsx');

        } 
        elseif ($source_generate == "college") {
            $cbco = $data->name;
            $file_suffix = $data->code.'_'.ucfirst($request->input("type_generate")).'_'.$year_generate.'_'.$quarter_generate.'_Consolidated_QAR';
            $departments = Department::where('college_id', $data->id)->pluck('id')->all();
            /* */
            return Excel::download(new CollegeConsolidatedAccomplishmentReportExport(
                $source_type, 
                $reportFormat, 
                $source_generate,
                $year_generate,
                $quarter_generate,
                $id, 
                $cbco,
                ),
                $file_suffix.'.xlsx');
        }
    }

    public function documentView($reportID){
        $reportDocuments = json_decode(Report::where('id', $reportID)->pluck('report_documents')->first(), true);
        return view('reports.generate.document', compact('reportDocuments'));
    }
}
