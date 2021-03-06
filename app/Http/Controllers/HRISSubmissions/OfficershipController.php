<?php

namespace App\Http\Controllers\HRISSubmissions;

use Image;
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
use App\Models\FormBuilder\DropdownOption;
use App\Models\Maintenance\HRISField;
use App\Models\Maintenance\Department;
use App\Http\Controllers\Maintenances\LockController;

class OfficershipController extends Controller
{
    public function index(){

        $currentQuarterYear = Quarter::find(1);

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $officershipFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCode N'$user->emp_code'");
        $savedReports = HRIS::where('hris_type', '3')->where('user_id', $user->id)->pluck('hris_id')->all();

        $submissionStatus = [];
        foreach ($officershipFinal as $officership) {
            $id = HRIS::where('hris_id', $officership->EmployeeOfficershipMembershipID)->where('hris_type', 3)->where('user_id', $user->id)->pluck('id')->first();
            if($id != ''){
                if (LockController::isLocked($id, 28))
                    $submissionStatus[28][$officership->EmployeeOfficershipMembershipID] = 1;
                else
                    $submissionStatus[28][$officership->EmployeeOfficershipMembershipID] = 0;
                if ($officership->Attachment == null)
                    $submissionStatus[28][$officership->EmployeeOfficershipMembershipID] = 2;
            }
        }
        // dd($officershipFinal);
        return view('submissions.hris.officership.index', compact('officershipFinal', 'savedReports', 'currentQuarterYear', 'submissionStatus'));
    }

    public function add(Request $request, $id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $officeData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code',$id");

        $officeFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 3)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->Classification,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->Level,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($officeData[0]->IncDateTo)),
            'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        //HRIS Document
        $hrisDocuments = [];
        $collegeOfDepartment = '';
        if(LockController::isNotLocked($id, 28) && Report::where('report_reference_id', $id)
                    ->where('report_quarter', $currentQuarterYear->current_quarter)
                    ->where('report_year', $currentQuarterYear->current_year)
                    ->where('report_category_id', 28)->exists()){

            $hrisDocuments = HRISDocument::where('hris_form_id', 3)->where('reference_id', $id)->get()->toArray();
            $report = Report::where('report_reference_id',$id)->where('report_category_id', 28)->first();
            $report_details = json_decode($report->report_details, true);
            $description;

            foreach($officeFields as $row){
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
                'organization' =>  $officeData[0]->Organization,
                'classification' => $officeData[0]->Classification,
                'position' => $officeData[0]->Position,
                'level' => $officeData[0]->Level,
                'organization_address' => $officeData[0]->Address,
                'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
                'to' => date('m/d/Y', strtotime($officeData[0]->IncDateTo)),
                'document' => $officeData[0]->Attachment,
                'description' => $officeData[0]->Description,
            ];
        }


        return view('submissions.hris.officership.add', compact('id', 'officeData', 'officeFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments'));
    }

    public function store(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id,
            'hris_type' => '3',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had saved a Officership/Membership.');

        return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been saved.');
    }

    public function show($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $officeData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code',$id");

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->pluck('department_id')->first();

        $officeFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 3)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->Classification,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->Level,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($officeData[0]->IncDateTo)),
            'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
            'department_id' => $department_id,
        ];
        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $forview = '';

        return view('submissions.hris.officership.add', compact('id', 'officeData', 'officeFields', 'values', 'colleges','departments', 'forview'));
    }

    public function edit($id){
        $currentQuarterYear = Quarter::find(1);

        $officershipID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->pluck('id')->first();

        if(LockController::isLocked($officershipID, 28)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $officeData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code',$id");

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->pluck('department_id')->first();

        $officeFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 3)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->Classification,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->Level,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($officeData[0]->IncDateTo)),
            'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
            'department_id' => $department_id,
        ];

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('submissions.hris.officership.edit', compact('id', 'officeData', 'officeFields', 'values', 'colleges','departments'));
    }

    public function update(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '3')->update([
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
        ]);

        \LogActivity::addToLog('Had updated a Officership/Membership.');

        return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been updated.');
    }

    public function delete($id){
        $officershipID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->pluck('id')->first();

        if(LockController::isLocked($officershipID, 28)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        HRIS::where('id', $officershipID)->delete();

        \LogActivity::addToLog('Had deleted a Officership/Membership.');

        return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been deleted.');
    }

    public function check($id){
        $officership = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->first();

        if(LockController::isLocked($officership->id, 28))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        if($this->submit($officership->id))
            return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');

        return redirect()->back()->with('cannot_access', 'Failed to submit the accomplishment.');
    }

    public function submit($officership_id){
        $user = User::find(auth()->id());
        $officership = HRIS::where('id', $officership_id)->first();

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $officeData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code',$officership->hris_id");

        $sector_id = College::where('id', $officership->college_id)->pluck('sector_id')->first();
        $department_name = Department::where('id', $officership->department_id)->pluck('name')->first();
        $college_name = College::where('id', $officership->college_id)->pluck('name')->first();

        $filenames = [];
        $img = Image::make($officeData[0]->Attachment);
        $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.jpeg';
        $newPath = storage_path().'/app/documents/'.$fileName;
        $img->save($newPath);

        HRISDocument::create([
            'hris_form_id' => 3,
            'reference_id' => $officership_id,
            'filename' => $fileName,
        ]);
        array_push($filenames, $fileName);

        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->Classification,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->Level,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($officeData[0]->IncDateTo)),
            // 'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
            'department_id' => $department_name,
            'college_id' => $college_name
        ];

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $officership_id)
            ->where('report_category_id', 28)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $officership->college_id,
            'department_id' => $officership->department_id,
            'report_category_id' => 28,
            'report_code' => null,
            'report_reference_id' => $officership_id,
            'report_details' => json_encode($values),
            'report_documents' => json_encode($filenames),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        return true;
    }



































    public function save(Request $request, $id){
        if($request->document[0] == null){
            return redirect()->back()->with('error', 'Document upload are required');
        }

        $officeFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
            ->where('h_r_i_s_fields.h_r_i_s_form_id', 3)->where('h_r_i_s_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
            ->orderBy('h_r_i_s_fields.order')->get();

        $data = [];

        foreach($officeFields as $field){
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
                    $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 3,
                        'reference_id' => $id,
                        'filename' => $fileName,
                    ]);
                    array_push($filenames, $fileName);
                }
            }
        }

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $id)
            ->where('report_category_id', 28)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'department_id' => $request->department_id,
            'report_category_id' => 28,
            'report_code' => null,
            'report_reference_id' => $educID,
            'report_details' => json_encode($data),
            'report_documents' => json_encode(collect($filenames)),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had submitted an Officership/Membership.');

        return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been submitted.');
    }
}
