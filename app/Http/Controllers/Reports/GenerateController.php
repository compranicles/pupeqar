<?php

namespace App\Http\Controllers\Reports;

use App\Models\User;
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
                $table_format = GenerateTable::where('type_id', 2)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.department_id', $department_id)
                            ->where('reports.chairperson_approval', 1)
                            ->whereYear('reports.updated_at', $request->input('year_generate'))
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $request->input('quarter_generate'))
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($request->input("source_generate") == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
                $table_format = GenerateTable::where('type_id', 2)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.college_id', $college_id)
                            ->where('reports.dean_approval', 1)
                            ->whereYear('reports.updated_at', $request->input('year_generate'))
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $request->input('quarter_generate'))
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($request->input("source_generate") == "my"){
                $source_type = "individual";
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();
                $table_format = GenerateTable::where('type_id', 2)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->whereYear('reports.updated_at', $request->input('year_generate'))
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $request->input('quarter_generate'))
                            ->where('reports.user_id', $user_id)
                            ->where('reports.college_id', $request->input('cbco'))
                            ->join('users', 'users.id', 'reports.user_id')
                            // ->join('departments', 'departments.id', 'reports.department_id')
                            // ->join('colleges', 'colleges.id', 'reports.college_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
        }
        elseif($request->input("type_generate") == "admin"){
            if($request->input("source_generate") == "department"){
                $source_type = "department";
                $department_id = $id;
                $data = Department::where('id', $department_id)->first();
                $table_format = GenerateTable::where('type_id', 1)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.department_id', $department_id)
                            ->where('reports.chairperson_approval', 1)
                            ->whereYear('reports.updated_at', $request->input('year_generate'))
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $request->input('quarter_generate'))
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($request->input("source_generate") == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
                $table_format = GenerateTable::where('type_id', 1)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.college_id', $college_id)
                            ->where('reports.dean_approval', 1)
                            ->whereYear('reports.updated_at', $request->input('year_generate'))
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $request->input('quarter_generate'))
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($request->input("source_generate") == "my"){
                $source_type = "individual";
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();
                $table_format = GenerateTable::where('type_id', 1)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->whereYear('reports.updated_at', $request->input('year_generate'))
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $request->input('quarter_generate'))
                            ->where('reports.user_id', $user_id)
                            ->where('reports.college_id', $request->input('cbco'))
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
        }
        
        $source_generate = $request->input("source_generate");
        $year_generate = $request->input('year_generate');
        $quarter_generate = $request->input('quarter_generate');
        $college = College::where('id', $request->input('cbco'))->first();
        $file_suffix = '';
        if ($source_generate == "my") {
            $cbco = $request->input('cbco');
            $file_suffix = $data->name.'_'.$college->code.'_'.$year_generate.'_'.$quarter_generate.'_Individual_Report';
        } elseif ($source_generate == "department") {
            $cbco = $data->college_id;
            $file_suffix = $data->name.'_'.$year_generate.'_'.$quarter_generate.'_Consolidated_Report';
        } elseif ($source_generate == "college") {
            $cbco = $data->id;
            $file_suffix = $data->name.'_'.$year_generate.'_'.$quarter_generate.'_Consolidated_Report';
        }


        $user = User::where('id', auth()->id())->first('last_name');
        $nameUser = $user->last_name;
        return Excel::download(new IndividualAccomplishmentReportExport(
            $source_type, 
            $reportFormat, 
            $source_generate,
            $year_generate,
            $quarter_generate,
            $cbco, 
            $id, 
            json_decode($request->input('table_columns_json'), true), 
            json_decode($request->input('table_contents_json'), true), 
            json_decode($request->input('table_format_json'), true)), 
            
            $file_suffix.'.xlsx');
    }
}
