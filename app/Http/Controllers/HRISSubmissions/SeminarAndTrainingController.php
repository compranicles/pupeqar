<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Models\User;
use App\Models\Report;
use App\Models\HRISDocument;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\SyllabusDocument;
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

class SeminarAndTrainingController extends Controller
{
    public function index(){

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $developmentFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        return view('submissions.hris.development.index', compact('developmentFinal'));
    }

    public function addSeminar($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        if(LockController::isLocked($id, 25)){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }
        if(LockController::isLocked($id, 26)){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }
        if(Report::where('report_reference_id', $id)
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->where('report_category_id', 26)->exists()
            ){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment in Training');
        }

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $seminar;

        $seminarFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 4)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();
        
        foreach($developments as $development){
            if($development->EmployeeTrainingProgramID == $id){
                $seminar = $development;
                break;
            }
        }

        $values = [
            'title' => $seminar->TrainingProgram,
            'classification' => $seminar->Classification,
            'nature' => $seminar->Nature,
            'budget' => $seminar->Budget,
            'fund_source' => $seminar->SourceOfFund,
            'organizer' => $seminar->Conductor,
            'level' => $seminar->Level,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours
        ];

        $colleges = College::all();

        //HRIS Document 
        $hrisDocuments = [];
        $collegeOfDepartment = '';
        if(LockController::isNotLocked($id, 25) && Report::where('report_reference_id', $id)
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->where('report_category_id', 25)->exists()){

            $hrisDocuments = HRISDocument::where('hris_form_id', 4)->where('reference_id', $id)->get()->toArray();
            $report = Report::where('report_reference_id',$id)->where('report_category_id', 25)->first();
            $report_details = json_decode($report->report_details, true);
            $description;

            foreach($seminarFields as $row){
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
                'title' => $seminar->TrainingProgram,
                'classification' => $seminar->Classification,
                'nature' => $seminar->Nature,
                'budget' => $seminar->Budget,
                'fund_source' => $seminar->SourceOfFund,
                'organizer' => $seminar->Conductor,
                'level' => $seminar->Level,
                'venue' => $seminar->Venue,
                'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
                'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
                'total_hours' => $seminar->NumberOfHours,
                'description' => $description
            ]; 
        }

        return view('submissions.hris.development.seminar.add', compact('id', 'seminar', 'seminarFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments'));
    }

    public function saveSeminar(Request $request, $id){
        if($request->document[0] == null){
            return redirect()->back()->with('error', 'Document upload are required');
        }


        $sector_id = College::where('id', $request->college_id)->pluck('sector_id')->first();

        $seminarFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
            ->where('h_r_i_s_fields.h_r_i_s_form_id', 4)->where('h_r_i_s_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
            ->orderBy('h_r_i_s_fields.order')->get();
    
        $data = [];
        
        foreach($seminarFields as $field){
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
                if($request->input($field->name) == 'null' || $request->input($field->name) == null)
                    $data[$field->name] = '';
                else
                    $data[$field->name] = $request->input($field->name);
            }
        }
        
        $filenames = [];

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'HRIS-ADP-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 4,
                        'reference_id' => $id,
                        'filename' => $fileName,
                    ]);
                    array_push($filenames, $fileName);
                }
            }
        }

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $id)
            ->where('report_category_id', 25)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'report_category_id' => 25,
            'report_code' => null,
            'report_reference_id' => $id,
            'report_details' => json_encode($data),
            'report_documents' => json_encode($filenames),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Seminar/Webinar added.');

        return redirect()->route('submissions.development.index')->with('success','Report Submitted Successfully');
    }

    public function addTraining($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        if(LockController::isLocked($id, 25)){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }
        if(LockController::isLocked($id, 26)){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }
        if(Report::where('report_reference_id', $id)
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->where('report_category_id', 25)->exists()
            ){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment in Seminar');
        }

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $training;

        $trainingFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 5)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();
        
        foreach($developments as $development){
            if($development->EmployeeTrainingProgramID == $id){
                $training = $development;
                break;
            }
        }

        $values = [
            'title' => $training->TrainingProgram,
            'classification' => $training->Classification,
            'nature' => $training->Nature,
            'budget' => $training->Budget,
            'fund_source' => $training->SourceOfFund,
            'organizer' => $training->Conductor,
            'level' => $training->Level,
            'venue' => $training->Venue,
            'from' => date('m/d/Y', strtotime($training->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($training->IncDateTo)),
            'total_hours' => $training->NumberOfHours
        ];

        $colleges = College::all();

        //HRIS Document 
        $hrisDocuments = [];
        $collegeOfDepartment = '';
        if(LockController::isNotLocked($id, 26) && Report::where('report_reference_id', $id)
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->where('report_category_id', 26)->exists()){

            $hrisDocuments = HRISDocument::where('hris_form_id', 5)->where('reference_id', $id)->get()->toArray();
            $report = Report::where('report_reference_id', $id)->where('report_category_id', 26)->first();
            $report_details = json_decode($report->report_details, true);
            $description;

            foreach($trainingFields as $row){
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
                'title' => $training->TrainingProgram,
                'classification' => $training->Classification,
                'nature' => $training->Nature,
                'budget' => $training->Budget,
                'fund_source' => $training->SourceOfFund,
                'organizer' => $training->Conductor,
                'level' => $training->Level,
                'venue' => $training->Venue,
                'from' => date('m/d/Y', strtotime($training->IncDateFrom)),
                'to' => date('m/d/Y', strtotime($training->IncDateTo)),
                'total_hours' => $training->NumberOfHours,
                'description' => $description
            ]; 
        }

        return view('submissions.hris.development.training.add', compact('id', 'training', 'trainingFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments'));
    }

    public function saveTraining(Request $request, $id){
        
        if($request->document[0] == null){
            return redirect()->back()->with('error', 'Document upload are required');
        }

        $sector_id = College::where('id', $request->college_id)->pluck('sector_id')->first();

        $trainingFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
            ->where('h_r_i_s_fields.h_r_i_s_form_id', 5)->where('h_r_i_s_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
            ->orderBy('h_r_i_s_fields.order')->get();
        
        $data = [];
        
        foreach($trainingFields as $field){
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
                if($request->input($field->name) == 'null' || $request->input($field->name) == null)
                    $data[$field->name] = '';
                else
                    $data[$field->name] = $request->input($field->name);
            }
        }

        $filenames = [];
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'HRIS-AT-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 5,
                        'reference_id' => $id,
                        'filename' => $fileName,
                    ]);
                    array_push($filenames, $fileName);
                }
            }
        }

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $id)
            ->where('report_category_id', 26)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'report_category_id' => 26,
            'report_code' => null,
            'report_reference_id' => $id,
            'report_details' => json_encode($data),
            'report_documents' => json_encode(collect($filenames)),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Training added.');

        return redirect()->route('submissions.development.index')->with('success','Report Submitted Successfully');
    }
}