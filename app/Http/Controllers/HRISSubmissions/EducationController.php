<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{
    DB,
    Storage,
};
use App\Models\{
    Employee,
    HRISDocument,
    Report,
    TemporaryFile,
    User,
    FormBuilder\DropdownOption,
    Maintenance\College,
    Maintenance\Currency,
    Maintenance\Department,
    Maintenance\HRISField,
    Maintenance\Quarter,
};

class EducationController extends Controller
{
    public function index(){

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $educationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");

        $educationFinal = [];

        foreach($educationLevel as $level){

            $educationTemp = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndEducationLevelID N'$user->emp_code',$level->EducationLevelID");

            $educationFinal = array_merge($educationFinal, $educationTemp);
        }

        return view('submissions.hris.education.index', compact('educationFinal', 'educationLevel'));
    }

    public function add(Request $request,$educID){

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        if(LockController::isLocked($educID, 24)){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }

        $db_ext = DB::connection('mysql_external');

        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$educID");

        $educFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 1)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();
        $values = [
            'degree' =>  $educationData[0]->Degree,
            'school_name' => $educationData[0]->SchoolName,
            'program_level' => $educationData[0]->AccreditationLevel,
            'support_type' => $educationData[0]->TypeOfSupport,
            'sponsor_name' => $educationData[0]->Scholarship,
            'amount' => $educationData[0]->Amount,
            'from' => $educationData[0]->IncYearFrom,
            'to' => $educationData[0]->IncYearTo,
            'status' => $educationData[0]->EnrollmentStatus,
            'units_earned' => $educationData[0]->UnitsEarned,
            'units_enrolled' =>$educationData[0]->UnitsEnrolled
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        //HRIS Document
        $hrisDocuments = [];
        $collegeOfDepartment = '';
        if(LockController::isNotLocked($educID, 24) && Report::where('report_reference_id', $educID)
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->where('report_category_id', 24)->exists()){

            $hrisDocuments = HRISDocument::where('hris_form_id', 1)->where('reference_id', $educID)->get()->toArray();
            $report = Report::where('report_reference_id',$educID)->where('report_category_id', 24)->first();
            $report_details = json_decode($report->report_details, true);
            $description;

            foreach($educFields as $row){
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
                'degree' =>  $educationData[0]->Degree,
                'school_name' => $educationData[0]->SchoolName,
                'program_level' => $educationData[0]->AccreditationLevel,
                'support_type' => $educationData[0]->TypeOfSupport,
                'sponsor_name' => $educationData[0]->Scholarship,
                'amount' => $educationData[0]->Amount,
                'from' => $educationData[0]->IncYearFrom,
                'to' => $educationData[0]->IncYearTo,
                'status' => $educationData[0]->EnrollmentStatus,
                'units_earned' => $educationData[0]->UnitsEarned,
                'units_enrolled' =>$educationData[0]->UnitsEnrolled,
                'description' => $description
            ];
        }

        return view('submissions.hris.education.add', compact('educID', 'educationData', 'educFields', 'values', 'colleges' , 'collegeOfDepartment', 'hrisDocuments', 'departments'));
    }

    public function save(Request $request, $educID){

        if($request->document[0] == null){
            return redirect()->back()->with('error', 'Document upload are required');
        }

        $educFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
            ->where('h_r_i_s_fields.h_r_i_s_form_id', 1)->where('h_r_i_s_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
            ->orderBy('h_r_i_s_fields.order')->get();
        $data = [];

        foreach($educFields as $field){
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
                    $fileName = 'HRIS-OAPS-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 1,
                        'reference_id' => $educID,
                        'filename' => $fileName,
                    ]);
                    array_push($filenames, $fileName);
                }
            }
        }

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $educID)
            ->where('report_category_id', 24)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'department_id' => $request->department_id,
            'report_category_id' => 24,
            'report_code' => null,
            'report_reference_id' => $educID,
            'report_details' => json_encode($data),
            'report_documents' => json_encode(collect($filenames)),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had submitted an Ongoing Advanced/Professional Study.');


        return redirect()->route('submissions.educ.index')->with('success','The accomplishment has been submitted.');
    }
}
