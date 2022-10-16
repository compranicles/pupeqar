<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Helpers\LogActivity;
use Image;
use Carbon\Carbon;
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
use App\Models\Maintenance\HRISField;
use App\Models\Maintenance\Department;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;
use App\Models\TemporaryFile;
use App\Services\CommonService;
use Exception;

class OfficershipController extends Controller
{
    protected $storageFileController;
    private $commonService;

    public function __construct(StorageFileController $storageFileController, CommonService $commonService){
        $this->storageFileController = $storageFileController;
        $this->commonService = $commonService;
    }
    
    public function index(){

        $currentQuarterYear = Quarter::find(1);

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $officershipFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCode N'$user->emp_code'");
        $savedReports = HRIS::where('hris_type', '3')->where('user_id', $user->id)->pluck('hris_id')->all();

        $submissionStatus = [];
        $submitRole = "";
        foreach ($officershipFinal as $officership) {
            $id = HRIS::where('hris_id', $officership->EmployeeOfficershipMembershipID)->where('hris_type', 3)->where('user_id', $user->id)->pluck('hris_id')->first();
            if($id != ''){
                if (LockController::isLocked($id, 28)) {
                    $submissionStatus[28][$officership->EmployeeOfficershipMembershipID] = 1;
                    $submitRole[$id] = ReportDataController::getSubmitRole($id, 28);
                }
                else
                    $submissionStatus[28][$officership->EmployeeOfficershipMembershipID] = 0;
                if ($officership->Attachment == null)
                    $submissionStatus[28][$officership->EmployeeOfficershipMembershipID] = 2;
            }
        }
        // dd($officershipFinal);
        return view('submissions.hris.officership.index', compact('officershipFinal', 'savedReports', 'currentQuarterYear', 'submissionStatus', 'submitRole'));
    }

    public function create(){
        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');
        $currentQuarter = Quarter::find(1)->current_quarter;

        $fields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 3)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

            if(session()->get('user_type') == 'Faculty Employee')
                $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
            else
                $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $values = [];
        $dropdown_options = [];

        //level
        $hrislevels = $db_ext->select("SET NOCOUNT ON; EXEC GetLevel");
        $levels = [];
        foreach($hrislevels as $row){
            $levels[] = (object)[
                'id' => $row->LevelID,
                'name' => $row->Level,
            ];
        }
        $levels = collect($levels);
        $dropdown_options['level'] = $levels;
        //classification
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetOfficershipMembershipClassification");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->OfficershipMembershipClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;

        return view('submissions.hris.officership.create', compact('values', 'fields', 'dropdown_options', 'departments', 'currentQuarter'));
    }

    public function savetohris(Request $request){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        if ($request->current_member == 1) $to = "present";
        else $to = Carbon::parse($request->to)->format('Y-m-d');

        // try {
        //     if($request->has('document')){
        //         $datastring = file_get_contents($request->file('document'));
        //         $mimetype = $request->file('document')->getMimeType();
        //         $imagedata = unpack("H*hex", $datastring);
        //         $imagedata = '0x' . strtoupper($imagedata['hex']);
        //     }
        // } catch (Exception $th) {
        //     return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        // }

        $document = $this->commonService->fileUploadHandlerForExternal($request, 'document');
        $value = [
            0, //EmployeeOfficershipMembershipID
            $emp_code, //EmpCode
            $request->position, //Position
            $request->organization, //Organization
            $request->organization_address, //Address
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            $to, //IncDateTo
            $request->level, //LevelID
            $request->classification, //ClassificationID
            'image/pdf/files', //Remarks
            $request->description, //AttachmentDescription
            $document["image"], //Attachment
            $document['mimetype'], //MimeType
            $user->email
        ];

        $id = $db_ext->select(
            "
                DECLARE @NewEmployeeOfficershipMembershipID int;
                EXEC SaveEmployeeOfficershipMembership
                    @EmployeeOfficershipMembershipID = ?,
                    @EmpCode = ?,
                    @Position = ?,
                    @Organization = ?,
                    @Address = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @LevelID = ?,
                    @ClassificationID = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @MimeType = ?,
                    @TransAccount = ?,
                    @NewEmployeeOfficershipMembershipID = @NewEmployeeOfficershipMembershipID OUTPUT;

                SELECT @NewEmployeeOfficershipMembershipID as NewEmployeeOfficershipMembershipID;

            ", $value);

            // dd($id);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id[0]->NewEmployeeOfficershipMembershipID,
            'hris_type' => '3',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        LogActivity::addToLog('Had saved a Officership/Membership.');

        if($document['isError'] == false){
            return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.officership.index')->with('error', "Entry was saved but unable to upload some document/s, Please try reuploading the document/s!");
        }

        // return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been saved.');
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

        if ($officeData[0]->IncDateTo == "present") {
            $to = $officeData[0]->IncDateTo;
            $current = 1;
        }
        else {
            $to = date('m/d/Y', strtotime($officeData[0]->IncDateTo));
            $current = 0;
        }

        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->OfficershipMembershipClassificationID,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->LevelID,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'current_member' => $current,
            'to' => $to,
            'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
            'mimetype' => $officeData[0]->MimeType,
        ];

        $dropdown_options = [];

        //level
        $hrislevels = $db_ext->select("SET NOCOUNT ON; EXEC GetLevel");
        $levels = [];
        foreach($hrislevels as $row){
            $levels[] = (object)[
                'id' => $row->LevelID,
                'name' => $row->Level,
            ];
        }
        $levels = collect($levels);
        $dropdown_options['level'] = $levels;
        //classification
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetOfficershipMembershipClassification");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->OfficershipMembershipClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

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
                'mimetype' => $officeData[0]->MimeType,
            ];
        }


        return view('submissions.hris.officership.add', compact('id', 'officeData', 'officeFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments', 'dropdown_options'));
    }

    public function store(Request $request, $id){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        if ($request->current_member == 1) $to = "present";
        else $to = Carbon::parse($request->to)->format('Y-m-d');

        $document = $this->commonService->fileUploadHandlerForExternal($request, 'document');
        $value = [
            $id, //EmployeeOfficershipMembershipID
            $emp_code, //EmpCode
            $request->position, //Position
            $request->organization, //Organization
            $request->organization_address, //Address
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            $to, //IncDateTo
            $request->level, //LevelID
            $request->classification, //ClassificationID
            'image/pdf/files', //Remarks
            $request->description, //AttachmentDescription
            $document["image"], //Attachment
            $document['mimetype'], //MimeType
            $user->email
        ];

        $db_ext->select(
            "
                DECLARE @NewEmployeeOfficershipMembershipID int;
                EXEC SaveEmployeeOfficershipMembership
                    @EmployeeOfficershipMembershipID = ?,
                    @EmpCode = ?,
                    @Position = ?,
                    @Organization = ?,
                    @Address = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @LevelID = ?,
                    @ClassificationID = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @MimeType = ?,
                    @TransAccount = ?,
                    @NewEmployeeOfficershipMembershipID = @NewEmployeeOfficershipMembershipID OUTPUT;

                SELECT @NewEmployeeOfficershipMembershipID as NewEmployeeOfficershipMembershipID;

            ", $value);

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

        LogActivity::addToLog('Had saved a Officership/Membership.');

        if($document['isError'] == false){
            return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.officership.index')->with('error', "Entry was saved but unable to upload some document/s, Please try reuploading the document/s!");
        }
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

        if ($officeData[0]->IncDateTo == "present") {
            $to = $officeData[0]->IncDateTo;
        }
        else {
            $to = date('m/d/Y', strtotime($officeData[0]->IncDateTo));
        }

        // dd($officeData[0]->Attachment);
        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->Classification,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->Level,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'current_member' => 0,
            'to' => $to,
            'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
            'department_id' => Department::where('id', $department_id)->pluck('name')->first(),
            'college_id' => College::where('id', Department::where('id', $department_id)->pluck('college_id')->first())->pluck('name')->first(),
            'mimetype' => $officeData[0]->MimeType,
        ];
        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $forview = '';
        $this->storageFileController->fetch_image($id, '3');

        return view('submissions.hris.officership.add', compact('id', 'officeData', 'officeFields', 'values', 'colleges','departments', 'forview'));
    }

    public function edit($id){
        $currentQuarterYear = Quarter::find(1);
        $currentQuarter = Quarter::find(1)->current_quarter;

        $officershipID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->pluck('hris_id')->first();

        if(LockController::isLocked($officershipID, 28)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $officeData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code',$id");

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->pluck('department_id')->first();

        $officeFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 3)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        if ($officeData[0]->IncDateTo == "present") {
            $to = $officeData[0]->IncDateTo;
            $current = 1;
        }
        else {
            $to = date('m/d/Y', strtotime($officeData[0]->IncDateTo));
            $current = 0;
        }

        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->OfficershipMembershipClassificationID,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->LevelID,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'current_member' => $current,
            'to' => $to,
            'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
            'department_id' => $department_id,
            'mimetype' => $officeData[0]->MimeType,
        ];

        $dropdown_options = [];

        //level
        $hrislevels = $db_ext->select("SET NOCOUNT ON; EXEC GetLevel");
        $levels = [];
        foreach($hrislevels as $row){
            $levels[] = (object)[
                'id' => $row->LevelID,
                'name' => $row->Level,
            ];
        }
        $levels = collect($levels);
        $dropdown_options['level'] = $levels;
        //classification
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetOfficershipMembershipClassification");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->OfficershipMembershipClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('submissions.hris.officership.edit', compact('id', 'officeData', 'officeFields', 'values', 'colleges','departments', 'dropdown_options', 'currentQuarter'));
    }

    public function update(Request $request, $id){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        if ($request->current_member == 1) $to = "present";
        else $to = Carbon::parse($request->to)->format('Y-m-d');

        $document = $this->commonService->fileUploadHandlerForExternal($request, 'document');    
        $value = [
            $id, //EmployeeOfficershipMembershipID
            $emp_code, //EmpCode
            $request->position, //Position
            $request->organization, //Organization
            $request->organization_address, //Address
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            $to, //IncDateTo
            $request->level, //LevelID
            $request->classification, //ClassificationID
            'image/pdf/files', //Remarks
            $request->description, //AttachmentDescription
            $document["image"], //Attachment
            $document['mimetype'], //MimeType
            $user->email
        ];

        $db_ext->select(
            "
                DECLARE @NewEmployeeOfficershipMembershipID int;
                EXEC SaveEmployeeOfficershipMembership
                    @EmployeeOfficershipMembershipID = ?,
                    @EmpCode = ?,
                    @Position = ?,
                    @Organization = ?,
                    @Address = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @LevelID = ?,
                    @ClassificationID = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @MimeType = ?,
                    @TransAccount = ?,
                    @NewEmployeeOfficershipMembershipID = @NewEmployeeOfficershipMembershipID OUTPUT;

                SELECT @NewEmployeeOfficershipMembershipID as NewEmployeeOfficershipMembershipID;

            ", $value);

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '3')->update([
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        LogActivity::addToLog('Had updated a Officership/Membership.');

        return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been updated.');
        if($document['isError'] == false){
            return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.officership.index')->with('error', "Entry was saved but unable to upload some document/s, Please try reuploading the document/s!");
        }
    }

    public function delete($id){
        $officershipID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '3')->pluck('hris_id')->first();

        if(LockController::isLocked($officershipID, 28)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');

        $db_ext->statement(
            "
                EXEC DeleteEmployeeOfficershipMembership
                    @EmployeeOfficershipmembershipID = ?,
                    @EmpCode = ?;
            ", array($id, $user->emp_code)
        );

        if(!is_null($officershipID)){
            HRIS::where('id', $officershipID)->delete();
        }

        LogActivity::addToLog('Had deleted a Officership/Membership.');

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
        $employee = Employee::where('user_id', auth()->id())->where('college_id', $officership->college_id)->get();

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $officeData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code',$officership->hris_id");

        $sector_id = College::where('id', $officership->college_id)->pluck('sector_id')->first();
        $department_name = Department::where('id', $officership->department_id)->pluck('name')->first();
        $college_name = College::where('id', $officership->college_id)->pluck('name')->first();

        $filenames = [];
        $imagejpeg = ['image/jpeg', 'image/pjpeg', 'image/jpg', 'image/jfif', 'image/pjp'];

        try {
            if(in_array($officeData[0]->MimeType, $imagejpeg)){
                $file = Image::make($officeData[0]->Attachment);
                $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.jpeg';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $file->save($newPath);
            }
            elseif($officeData[0]->MimeType == 'image/png' || $officeData['0']->MimeType == 'image/x-png'){
                $file = Image::make($officeData[0]->Attachment);
                $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.png';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $file->save($newPath);
            }
            elseif($officeData[0]->MimeType == 'application/pdf'){
                $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.pdf';
                file_put_contents(storage_path().'/app/documents/'.$fileName, $officeData[0]->Attachment);
                $file = true;
            } else {
                $file = Image::make($officeData[0]->Attachment);
                $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.png';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $file->save($newPath);
            }
    
            if(isset($file)){
                HRISDocument::create([
                    'hris_form_id' => 3,
                    'reference_id' => $officership_id,
                    'filename' => $fileName,
                ]);
                array_push($filenames, $fileName);
            }
            else{
                return false;
            }

        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Request timeout, Unable to transfer files, Please try again!' );
        }
       

        if ($officeData[0]->IncDateTo == "present") $to = $officeData[0]->IncDateTo;
        else $to = date('m/d/Y', strtotime($officeData[0]->IncDateTo));

        $values = [
            'organization' =>  $officeData[0]->Organization,
            'classification' => $officeData[0]->Classification,
            'position' => $officeData[0]->Position,
            'level' => $officeData[0]->Level,
            'organization_address' => $officeData[0]->Address,
            'from' => date('m/d/Y', strtotime($officeData[0]->IncDateFrom)),
            'to' => $to,
            // 'document' => $officeData[0]->Attachment,
            'description' => $officeData[0]->Description,
            'department_id' => $department_name,
            'college_id' => $college_name
        ];

        $currentQuarterYear = Quarter::find(1);
        $type = '';
        if (count($employee) == 2){
            $getUserTypeFromSession = session()->get('user_type');
            if($getUserTypeFromSession == 'Faculty Employee')
                $type = 'f';
            elseif($getUserTypeFromSession == 'Admin Employee')
                $type = 'a';
        } elseif (count($employee) == 1) {
            if ($employee[0]['type'] == 'F')
                $type = 'f';
            elseif ($employee[0]['type'] == 'A')
                $type = 'a';
        }

        Report::where('report_reference_id', $officership->hris_id)
            ->where('report_category_id', 28)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        if ($type == 'a') {
            if ($officership->department_id == $officership->college_id) {
                Report::create([
                    'user_id' =>  auth()->id(),
                    'sector_id' => $sector_id,
                    'college_id' => $officership->college_id,
                    'department_id' => $officership->department_id,
                    'format' => $type,
                    'report_category_id' => 28,
                    'report_code' => null,
                    'report_reference_id' => $officership->hris_id,
                    'report_details' => json_encode($values),
                    'report_documents' => json_encode($filenames),
                    'report_date' => date("Y-m-d", time()),
                    'chairperson_approval' => 1,
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
            } else {
                Report::create([
                    'user_id' =>  auth()->id(),
                    'sector_id' => $sector_id,
                    'college_id' => $officership->college_id,
                    'department_id' => $officership->department_id,
                    'format' => $type,
                    'report_category_id' => 28,
                    'report_code' => null,
                    'report_reference_id' => $officership->hris_id,
                    'report_details' => json_encode($values),
                    'report_documents' => json_encode($filenames),
                    'report_date' => date("Y-m-d", time()),
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
            }
        } elseif ($type == 'f') {
            if ($officership->department_id == $officership->college_id) {
                if ($officership->department_id >= 227 && $officership->department_id <= 248) { // If branch
                    Report::create([
                        'user_id' =>  auth()->id(),
                        'sector_id' => $sector_id,
                        'college_id' => $officership->college_id,
                        'department_id' => $officership->department_id,
                        'format' => $type,
                        'report_category_id' => 28,
                        'report_code' => null,
                        'report_reference_id' => $officership->hris_id,
                        'report_details' => json_encode($values),
                        'report_documents' => json_encode($filenames),
                        'report_date' => date("Y-m-d", time()),
                        'report_quarter' => $currentQuarterYear->current_quarter,
                        'report_year' => $currentQuarterYear->current_year,
                    ]);
                } else {
                    Report::create([
                        'user_id' =>  auth()->id(),
                        'sector_id' => $sector_id,
                        'college_id' => $officership->college_id,
                        'department_id' => $officership->department_id,
                        'format' => $type,
                        'report_category_id' => 28,
                        'report_code' => null,
                        'report_reference_id' => $officership->hris_id,
                        'report_details' => json_encode($values),
                        'report_documents' => json_encode($filenames),
                        'report_date' => date("Y-m-d", time()),
                        'chairperson_approval' => 1,
                        'report_quarter' => $currentQuarterYear->current_quarter,
                        'report_year' => $currentQuarterYear->current_year,
                    ]);
                }
            } else {
                Report::create([
                    'user_id' =>  auth()->id(),
                    'sector_id' => $sector_id,
                    'college_id' => $officership->college_id,
                    'department_id' => $officership->department_id,
                    'format' => $type,
                    'report_category_id' => 28,
                    'report_code' => null,
                    'report_reference_id' => $officership->hris_id,
                    'report_details' => json_encode($values),
                    'report_documents' => json_encode($filenames),
                    'report_date' => date("Y-m-d", time()),
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
            }
        }

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

        // if($request->has('document')){
        //     $documents = $request->input('document');
        //     foreach($documents as $document){
        //         $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //         if($temporaryFile){
        //             $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //             $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //             $ext = $info['extension'];
        //             $fileName = 'HRIS-OM-'.now()->timestamp.uniqid().'.'.$ext;
        //             $newPath = "documents/".$fileName;
        //             Storage::move($temporaryPath, $newPath);
        //             Storage::deleteDirectory("documents/tmp/".$document);
        //             $temporaryFile->delete();

        //             HRISDocument::create(['hris_form_id' => 3, 'reference_id' => $id, 'filename' => $fileName]);
        //             array_push($filenames, $fileName);
        //         }
        //     }
        // }

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $id)
            ->where('report_category_id', 28)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        $FORFILESTORE = Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'department_id' => $request->department_id,
            'report_category_id' => 28,
            'report_code' => null,
            'report_reference_id' => $id,
            'report_details' => json_encode($data),
            'report_documents' => json_encode(collect([])),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        LogActivity::addToLog('Had submitted an Officership/Membership.');

        $filenames = [];

        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $fileName = $this->commonService->fileUploadHandler($document, "", 'HRIS-OM', 'submissions.officership.index');
                if(is_string($fileName)) {
                    HRISDocument::create(['hris_form_id' => 3, 'reference_id' => $id, 'filename' => $fileName]);
                    array_push($filenames, $fileName);
                } else {
                    HRISDocument::where('reference_id', $id)->delete();
                    return $fileName;
                }
            }
        }

        $FORFILESTORE->report_documents = json_encode(collect($filenames));
        $FORFILESTORE->save();
        
        return redirect()->route('submissions.officership.index')->with('success','The accomplishment has been submitted.');
    }
}
