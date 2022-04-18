<?php

namespace App\Http\Controllers\Reports;

use App\Models\User;
use App\Models\FacultyResearcher;
use App\Models\FacultyExtensionist;
use App\Models\Chairperson;
use App\Models\Maintenance\Sector;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\Maintenance\GenerateTable;
use App\Models\Maintenance\GenerateColumn;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IndividualAccomplishmentReportExport;
use App\Exports\DepartmentConsolidatedAccomplishmentReportExport;
use App\Exports\CollegeConsolidatedAccomplishmentReportExport;

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
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();
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
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();   
            }
        }
        
        $source_generate = $request->input("source_generate");
        $year_generate = $request->input('year_generate');
        $quarter_generate = $request->input('quarter_generate');
        $college = College::where('id', $request->input('cbco'))->first();
        $file_suffix = '';
        if ($source_generate == "my") {
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
                json_decode($request->input('table_columns_json'), true), 
                json_decode($request->input('table_contents_json'), true), 
                json_decode($request->input('table_format_json'), true)), 
                $file_suffix.'.xlsx');

        } elseif ($source_generate == "department") {
            $department = $data->name;
            $file_suffix = $data->name.'_'.ucfirst($request->input("type_generate")).'_'.$year_generate.'_'.$quarter_generate.'_Consolidated_Department_QAR';
            $faculty_researcher = User::join('faculty_researchers', 'faculty_researchers.user_id', 'users.id')->where('faculty_researchers.department_id', $data->id)->whereNull('faculty_researchers.deleted_at')->first();
            $faculty_extensionist = User::join('faculty_extensionists', 'faculty_extensionists.user_id', 'users.id')->where('faculty_extensionists.department_id', $data->id)->whereNull('faculty_extensionists.deleted_at')->first();
            return Excel::download(new DepartmentConsolidatedAccomplishmentReportExport(
                $source_type, 
                $reportFormat, 
                $source_generate,
                $year_generate,
                $quarter_generate,
                $department, 
                $id, 
                $get_department = $data->name,
                $faculty_researcher,
                $faculty_extensionist,
                json_decode($request->input('table_columns_json'), true), 
                json_decode($request->input('table_contents_json'), true), 
                json_decode($request->input('table_format_json'), true)), 
                $file_suffix.'.xlsx');

        } elseif ($source_generate == "college") {
            $cbco = $data->name;
            $file_suffix = $data->name.'_'.ucfirst($request->input("type_generate")).'_'.$year_generate.'_'.$quarter_generate.'_Consolidated_QAR';
            $departments = Department::where('college_id', $data->id)->pluck('id')->all();
            /* */
            $faculty_researchers = FacultyResearcher::whereIn('faculty_researchers.department_id', [$departments])->join('departments', 'departments.id', 'faculty_researchers.department_id')->join('users', 'users.id', 'faculty_researchers.user_id')->select('users.*', 'departments.name as department_name')->first();
            $faculty_extensionists = FacultyExtensionist::whereIn('faculty_extensionists.department_id', [$departments])->join('departments', 'departments.id', 'faculty_extensionists.department_id')->join('users', 'users.id', 'faculty_extensionists.user_id')->select('users.*', 'departments.name as department_name')->first();
            $chairpeople = Chairperson::whereIn('chairpeople.department_id', [$departments])->join('departments', 'departments.id', 'chairpeople.department_id')->join('users', 'users.id', 'chairpeople.user_id')->select('users.*', 'departments.name as department_name')->first();
            return Excel::download(new CollegeConsolidatedAccomplishmentReportExport(
                $source_type, 
                $reportFormat, 
                $source_generate,
                $year_generate,
                $quarter_generate,
                $id, 
                $cbco,
                $faculty_researchers,
                $faculty_extensionists,
                $chairpeople,
                json_decode($request->input('table_columns_json'), true), 
                json_decode($request->input('table_contents_json'), true), 
                json_decode($request->input('table_format_json'), true)), 
                $file_suffix.'.xlsx');
        }
    }

    public function documentView($reportID){
        $reportDocuments = json_decode(Report::where('id', $reportID)->pluck('report_documents')->first(), true);
        return view('reports.generate.document', compact('reportDocuments'));
    }
}
