<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Models\HRIS;
use App\Models\User;
use App\Models\Report;
use App\Models\Employee;
use App\Models\HRISDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Currency;
use App\Models\Maintenance\HRISField;
use App\Models\Maintenance\Department;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\Maintenances\LockController;
use Image;

class EducationController extends Controller
{
    public function index(){

        $currentQuarterYear = Quarter::find(1);

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $educationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");

        $educationList = [];
        $educationFinal = [];

        foreach($educationLevel as $level){

            $educationTemp = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndEducationLevelID N'$user->emp_code',$level->EducationLevelID");

            $educationList = array_merge($educationList, $educationTemp);
        }

        foreach($educationList as $education)
            if($education->IsCurrentlyEnrolled == 'Y'){
                // $education = collect($education);
                $education = [$education];
                $educationFinal = array_merge($educationFinal, $education);
            }

        // dd($educationFinal);

        $savedReports = HRIS::where('hris_type', '1')->where('user_id', $user->id)->pluck('hris_id')->all();

        $submissionStatus = [];
        foreach ($educationFinal as $education) {
            $id = HRIS::where('hris_id', $education->EmployeeEducationBackgroundID)->where('hris_type', 1)->where('user_id', $user->id)->pluck('id')->first();
            $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code', '$education->EmployeeEducationBackgroundID'");
            if($id != ''){
                if (LockController::isLocked($id, 24))
                    $submissionStatus[24][$education->EmployeeEducationBackgroundID] = 1;
                else
                    $submissionStatus[24][$education->EmployeeEducationBackgroundID] = 0;
                if ($educationData[0]->Attachment == null)
                    $submissionStatus[24][$education->EmployeeEducationBackgroundID] = 2;
            }
        }

        return view('submissions.hris.education.index', compact('educationFinal', 'savedReports', 'currentQuarterYear', 'submissionStatus'));
    }

    public function add(Request $request, $id){

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$id");

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
            'units_enrolled' =>$educationData[0]->UnitsEnrolled,
            'description' => $educationData[0]->Description,
            'document' => $educationData[0]->Attachment
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        //HRIS Document
        $hrisDocuments = [];
        $collegeOfDepartment = '';
        if(LockController::isNotLocked($id, 24) && Report::where('report_reference_id', $id)
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->where('report_category_id', 24)->exists()){

            $hrisDocuments = HRISDocument::where('hris_form_id', 1)->where('reference_id', $id)->get()->toArray();
            $report = Report::where('report_reference_id',$id)->where('report_category_id', 24)->first();
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
                'description' => $educationData[0]->Description,
                'document' => $educationData[0]->Attachment
            ];
        }

        return view('submissions.hris.education.add', compact('id', 'educationData', 'educFields', 'values', 'colleges' , 'collegeOfDepartment', 'hrisDocuments', 'departments'));
    }

    public function store(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id,
            'hris_type' => '1',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had saved a Ongoing Studies Accomplishment.');

        return redirect()->route('submissions.educ.index')->with('success','The accomplishment has been saved.');
    }

    public function show($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$id");
        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '1')->pluck('department_id')->first();

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
            'units_enrolled' =>$educationData[0]->UnitsEnrolled,
            'description' => $educationData[0]->Description,
            'document' => $educationData[0]->Attachment,
            'department_id' => $department_id
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $forview = '';

        return view('submissions.hris.education.add', compact('id', 'educationData', 'educFields', 'values', 'colleges','departments', 'forview'));
    }

    public function edit($id){
        $user = User::find(auth()->id());

        $educID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '1')->pluck('id')->first();

        if(LockController::isLocked($educID, 24)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$id");

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '1')->pluck('department_id')->first();

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
            'units_enrolled' =>$educationData[0]->UnitsEnrolled,
            'description' => $educationData[0]->Description,
            'document' => $educationData[0]->Attachment,
            'department_id' => $department_id
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('submissions.hris.education.edit', compact('id', 'educationData', 'educFields', 'values', 'colleges','departments'));
    }

    public function update(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '1')->update([
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
        ]);

        \LogActivity::addToLog('Had updated a Ongoing/Advanced Professional Study.');

        return redirect()->route('submissions.educ.index')->with('success','The accomplishment has been updated.');
    }

    public function delete($id){
        $educID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '1')->pluck('id')->first();

        if(LockController::isLocked($educID, 24)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        HRIS::where('id', $educID)->delete();

        \LogActivity::addToLog('Had deleted a Ongoing Studies.');

        return redirect()->route('submissions.educ.index')->with('success','The accomplishment has been deleted.');
    }

    public function check($id){
        $education = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '1')->first();

        if(LockController::isLocked($education->id, 24))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        if($this->submit($education->id))
            return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');

        return redirect()->back()->with('cannot_access', 'Failed to submit the accomplishment.');
    }

    public function submit($education_id){
        $user = User::find(auth()->id());
        $education = HRIS::where('id', $education_id)->first();

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$education->hris_id");

        $sector_id = College::where('id', $education->college_id)->pluck('sector_id')->first();
        $department_name = Department::where('id', $education->department_id)->pluck('name')->first();
        $college_name = College::where('id', $education->college_id)->pluck('name')->first();

        $filenames = [];
        $img = Image::make($educationData[0]->Attachment);
        $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.jpeg';
        $newPath = storage_path().'/app/documents/'.$fileName;
        $img->save($newPath);

        HRISDocument::create([
            'hris_form_id' => 1,
            'reference_id' => $education_id,
            'filename' => $fileName,
        ]);
        array_push($filenames, $fileName);

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
            'description' => $educationData[0]->Description,
            'department_id' => $department_name,
            'college_id' => $college_name
        ];

        $currentQuarterYear = Quarter::find(1);
        $getUserTypeFromSession = session()->get('user_type');
        $format_type = '';
        if($getUserTypeFromSession == 'Faculty Employee')
            $format_type = 'f';
        elseif($getUserTypeFromSession == 'Admin Employee')
            $format_type = 'a';


        Report::where('report_reference_id', $education_id)
            ->where('report_category_id', 24)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $education->college_id,
            'department_id' => $education->department_id,
            'format' => $format_type,
            'report_category_id' => 24,
            'report_code' => null,
            'report_reference_id' => $education_id,
            'report_details' => json_encode($values),
            'report_documents' => json_encode($filenames),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        return true;
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
