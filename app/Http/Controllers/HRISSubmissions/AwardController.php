<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Models\User;
use App\Models\Report;
use App\Models\Employee;
use App\Models\HRISDocument;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Currency;
use App\Models\Maintenance\HRISField;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\Maintenances\LockController;

class AwardController extends Controller
{
    public function index(){

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $awardFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCode N'$user->emp_code'");
        $awardReports = Report::where('report_category_id', 27)->where('user_id', $user->id)->select('report_reference_id', 'report_quarter', 'report_year')->get();
        return view('submissions.hris.award.index', compact('awardFinal', 'awardReports'));
    }

    public function add(Request $request, $id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        if(LockController::isLocked($id, 27)){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }

        $db_ext = DB::connection('mysql_external');

        $awardData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCodeAndID N'$user->emp_code',$id");

        $awardFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 2)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();


        $values = [
            'award' =>  $awardData[0]->Achievement,
            'classification' => $awardData[0]->Classification,
            'awarded_by' => $awardData[0]->AwardedBy,
            'level' => $awardData[0]->Level,
            'venue' => $awardData[0]->Venue,
            'from' => date('m/d/Y', strtotime($awardData[0]->Date)),
            'to' => date('m/d/Y', strtotime($awardData[0]->Date)),
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

         //HRIS Document
         $hrisDocuments = [];
         $collegeOfDepartment = '';
         if(LockController::isNotLocked($id, 27) && Report::where('report_reference_id', $id)
                     ->where('report_quarter', $currentQuarterYear->current_quarter)
                     ->where('report_year', $currentQuarterYear->current_year)
                     ->where('report_category_id', 27)->exists()){

             $hrisDocuments = HRISDocument::where('hris_form_id', 2)->where('reference_id', $id)->get()->toArray();
             $report = Report::where('report_reference_id',$id)->where('report_category_id', 27)->first();
             $report_details = json_decode($report->report_details, true);
             $description;

             foreach($awardFields as $row){
                 if($row->name == 'description')
                     $description = $report_details[$row->name];
             }

             if ($report->department_id != null) {
                 $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$report->department_id.")");
             }
             else {
                 $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
             }

             $values = [
                'award' =>  $awardData[0]->Achievement,
                'classification' => $awardData[0]->Classification,
                'awarded_by' => $awardData[0]->AwardedBy,
                'level' => $awardData[0]->Level,
                'venue' => $awardData[0]->Venue,
                'from' => date('m/d/Y', strtotime($awardData[0]->Date)),
                'to' => date('m/d/Y', strtotime($awardData[0]->Date)),
                 'description' => $description
             ];
         }

        return view('submissions.hris.award.add', compact('id', 'awardData', 'awardFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments'));
    }

    public function save(Request $request, $id){
        if($request->document[0] == null){
            return redirect()->back()->with('error', 'Document upload are required');
        }

        $awardFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
            ->where('h_r_i_s_fields.h_r_i_s_form_id', 2)->where('h_r_i_s_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
            ->orderBy('h_r_i_s_fields.order')->get();
        $data = [];

        foreach($awardFields as $field){
            if($field->field_type_id == '5'){
                $data[$field->name] = DropdownOption::where('id', $request->input($field->name))->pluck('name')->first();
            }
            elseif($field->field_type_id == '3'){
                $currency_name = Currency::where('id', $request->input('currency_'.$field->name))->pluck('code')->first();
                $data[$field->name] = $currency_name.' '.$request->input($field->name);
            }
            elseif($field->field_type_id == '10'){
                continue;
            }
            elseif($field->field_type_id == '12'){
                $data[$field->name] = College::where('id', $request->input($field->name))->pluck('name')->first();
            }
            elseif($field->field_type_id == '13'){
                $data[$field->name] = Department::where('id', $request->input($field->name))->pluck('name')->first();
            }
            else{
                $data[$field->name] = $request->input($field->name);
            }
        }

        $data = collect($data);

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();
        $sector_id = College::where('id', $college_id)->pluck('sector_id')->first();

        $filenames = [];
        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'HRIS-OAA-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 2,
                        'reference_id' => $id,
                        'filename' => $fileName,
                    ]);
                    array_push($filenames, $fileName);
                }
            }
        }

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $id)
            ->where('report_category_id', 27)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'department_id' => $request->department_id,
            'report_category_id' => 27,
            'report_code' => null,
            'report_reference_id' => $educID,
            'report_details' => json_encode($data),
            'report_documents' => json_encode(collect($filenames)),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);


        \LogActivity::addToLog('Had submitted an Oustanding Award/Achievement.');

        return redirect()->route('submissions.award.index')->with('success','The accomplishment has been submitted.');
    }
}
