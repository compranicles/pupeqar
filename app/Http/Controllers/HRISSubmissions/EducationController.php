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
use App\Models\Maintenance\Currency;
use App\Models\Maintenance\HRISField;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Response;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;
use Exception;

class EducationController extends Controller
{
    protected $storageFileController;

    public function __construct(StorageFileController $storageFileController){
        $this->storageFileController = $storageFileController;
    }

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

        foreach($educationList as $education){
            $education = [$education];
            $educationFinal = array_merge($educationFinal, $education);
        }

        $savedReports = HRIS::where('hris_type', '1')->where('user_id', $user->id)->pluck('hris_id')->all();

        $submissionStatus = [];
        $submitRole = "";
        foreach ($educationFinal as $education) {
            $id = HRIS::where('hris_id', $education->EmployeeEducationBackgroundID)->where('hris_type', 1)->where('user_id', $user->id)->pluck('hris_id')->first();
            $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code', '$education->EmployeeEducationBackgroundID'");
            if($id != ''){
                if (LockController::isLocked($id, 24)) {
                    $submissionStatus[24][$education->EmployeeEducationBackgroundID] = 1;
                    $submitRole[$id] = ReportDataController::getSubmitRole($id, 24);
                }
                else
                    $submissionStatus[24][$education->EmployeeEducationBackgroundID] = 0;
                if ($educationData[0]->Attachment == null)
                    $submissionStatus[24][$education->EmployeeEducationBackgroundID] = 2;
            }
        }

        return view('submissions.hris.education.index', compact('educationFinal', 'savedReports', 'currentQuarterYear', 'submissionStatus', 'submitRole'));
    }

    public function create(){
        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');
        $currentQuarter = Quarter::find(1)->current_quarter;

        $fields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 1)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $values = [];
        $dropdown_options = [];

        //education level
        $hriseducationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");
        $educationLevel = [];
        foreach($hriseducationLevel as $row){
            $educationLevel[] = (object)[
                'id' => $row->EducationLevelID,
                'name' => $row->EducationLevel,
            ];
        }
        $dropdown_options['level'] = $educationLevel;
        //education discipline
        $hriseducationDiscipline = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationDiscipline");
        $educationDiscipline = [];
        foreach($hriseducationDiscipline as $row){
            $educationDiscipline[] = (object)[
                'id' => $row->EducationDisciplineID,
                'name' => $row->EducationDiscipline,
            ];
        }
        $dropdown_options['education_discipline'] = $educationDiscipline;
        //accreditation level
        $hrisaccreditationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetAccreditationLevels");
        $accreditationLevel = [];
        foreach($hrisaccreditationLevel as $row){
            $accreditationLevel[] = (object)[
                'id' => $row->AccreditationLevelID,
                'name' => $row->AccreditationLevel,
            ];
        }
        $dropdown_options['program_level'] = $accreditationLevel;
        //enrollment status
        $hrisenrollmentStatus = $db_ext->select("SET NOCOUNT ON; EXEC GetEnrollmentStatus");
        $enrollmentStatus = [];
        foreach($hrisenrollmentStatus as $row){
            $enrollmentStatus[] = (object)[
                'id' => $row->EnrollmentStatusID,
                'name' => $row->EnrollmentStatus,
            ];
        }
        $dropdown_options['status'] = $enrollmentStatus;
        //type of support
        $hristypeOfSupport = $db_ext->select("SET NOCOUNT ON; EXEC GetTypeOfSupport");
        $typeOfSupport = [];
        foreach($hristypeOfSupport as $row){
            $typeOfSupport[] = (object)[
                'id' => $row->TypeOfSupportID,
                'name' => $row->TypeOfSupport,
            ];
        }
        $dropdown_options['support_type'] = $typeOfSupport;


        return view('submissions.hris.education.create', compact('values', 'fields', 'dropdown_options', 'departments', 'currentQuarter'));
    }

    public function savetohris(Request $request){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);


        try {
            if($request->has('document')){
                $datastring = file_get_contents($request->file('document'));
                $mimetype = $request->file('document')->getMimeType();
                $imagedata = unpack("H*hex", $datastring);
                $imagedata = '0x' . strtoupper($imagedata['hex']);
            }
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        }


        //is_graduated
        $is_graduated = 'Y';
        if($request->is_graduated == 'No'){
            $is_graduated = 'N';
        }

        //is_enrolled
        $is_enrolled = 'Y';
        if($request->is_enrolled == 'No'){
            $is_enrolled = 'N';
        }

        //year graduated
        if(!ctype_alpha($request->to)){
            $year_graduated = $request->to;
        }

        $value = [
            0,                                  //EmployeeEducationBackgroundID
            $emp_code,                          //EmpCode
            $request->level,                    //EducationLevelID
            $request->degree,                   //Degree
            $request->major ?? '',                    //Major
            $request->minor ?? '',                    //Minor
            $request->education_discipline,     //EducationDisciplineID
            $request->school_name,              //SchoolName
            $request->program_level,            //AccreditationLevelID
            $is_graduated,                      //IsGraduated
            $is_enrolled,                       //IsCurrentlyEnrolled
            $request->status,                   //EnrollmentStatusID
            $request->units_earned ?? '',             //UnitsEarned
            $request->units_enrolled ?? '',           //UnitsEnrolled
            $request->from,                     //IncYearFrom
            $request->to,                       //IncYearTo
            $year_graduated ?? 0,            //YearGraduated
            $request->honors ?? '',                   //HonorsReceived
            $request->support_type,             //TypeOfSupportID
            $request->sponsor_name ?? '',             //Scholarship
            $request->amount ?? 0,                   //Amount
            '',                                 //Remarks
            $request->description,              //AttachmentDescription
            $imagedata ?? null,                 //Attachment
            $mimetype ?? null, //MimeType
            $user->email                        //TransAccount
        ];

        $id = $db_ext->select(
            "
                DECLARE @NewEmployeeEducationBackgroundID int;
                EXEC SaveEmployeeEducationBackground
                    @EmployeeEducationBackgroundID = ?,
                    @EmpCode = ?,
                    @EducationLevelID = ?,
                    @Degree = ?,
                    @Major = ?,
                    @Minor = ?,
                    @EducationDisciplineID = ?,
                    @SchoolName = ?,
                    @AccreditationLevelID = ?,
                    @IsGraduated = ?,
                    @IsCurrentlyEnrolled = ?,
                    @EnrollmentStatusID = ?,
                    @UnitsEarned = ?,
                    @UnitsEnrolled = ?,
                    @IncYearFrom = ?,
                    @IncYearTo = ?,
                    @YearGraduated = ?,
                    @HonorsReceived = ?,
                    @TypeOfSupportID = ?,
                    @Scholarship = ?,
                    @Amount = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @MimeType = ?,
                    @TransAccount = ?,
                    @NewEmployeeEducationBackgroundID = @NewEmployeeEducationBackgroundID OUTPUT;

                SELECT @NewEmployeeEducationBackgroundID as NewEmployeeEducationBackgroundID;

            ", $value);

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id[0]->NewEmployeeEducationBackgroundID,
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
            'level' => $educationData[0]->EducationLevelID,
            'degree' =>  $educationData[0]->Degree,
            'major' =>  $educationData[0]->Major,
            'minor' =>  $educationData[0]->Minor,
            'education_discipline' =>  $educationData[0]->EducationDisciplineID,
            'school_name' => $educationData[0]->SchoolName,
            'program_level' => $educationData[0]->AccreditationLevelID,
            'is_graduated' => ($educationData[0]->IsGraduated == 'Y') ? 'Yes' : 'No',
            'is_enrolled' => ($educationData[0]->IsCurrentlyEnrolled == 'Y') ? 'Yes' : 'No',
            'status' => $educationData[0]->EnrollmentStatusID,
            'units_earned' => $educationData[0]->UnitsEarned,
            'units_enrolled' => $educationData[0]->UnitsEnrolled,
            'from' => $educationData[0]->IncYearFrom,
            'to' => $educationData[0]->IncYearTo,
            'year_graduated' => $educationData[0]->YearGraduated,
            'honors' => $educationData[0]->HonorsReceived,
            'support_type' => $educationData[0]->TypeOfSupportID,
            'sponsor_name' => $educationData[0]->Scholarship,
            'amount' => $educationData[0]->Amount,
            'remarks' => $educationData[0]->Remarks,
            'document' => $educationData[0]->Attachment,
            'description' => $educationData[0]->Description,
            'mimetype' => $educationData[0]->MimeType,
        ];

        $dropdown_options = [];

        //education level
        $hriseducationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");
        $educationLevel = [];
        foreach($hriseducationLevel as $row){
            $educationLevel[] = (object)[
                'id' => $row->EducationLevelID,
                'name' => $row->EducationLevel,
            ];
        }
        $dropdown_options['level'] = $educationLevel;
        //education discipline
        $hriseducationDiscipline = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationDiscipline");
        $educationDiscipline = [];
        foreach($hriseducationDiscipline as $row){
            $educationDiscipline[] = (object)[
                'id' => $row->EducationDisciplineID,
                'name' => $row->EducationDiscipline,
            ];
        }
        $dropdown_options['education_discipline'] = $educationDiscipline;
        //accreditation level
        $hrisaccreditationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetAccreditationLevels");
        $accreditationLevel = [];
        foreach($hrisaccreditationLevel as $row){
            $accreditationLevel[] = (object)[
                'id' => $row->AccreditationLevelID,
                'name' => $row->AccreditationLevel,
            ];
        }
        $dropdown_options['program_level'] = $accreditationLevel;
        //enrollment status
        $hrisenrollmentStatus = $db_ext->select("SET NOCOUNT ON; EXEC GetEnrollmentStatus");
        $enrollmentStatus = [];
        foreach($hrisenrollmentStatus as $row){
            $enrollmentStatus[] = (object)[
                'id' => $row->EnrollmentStatusID,
                'name' => $row->EnrollmentStatus,
            ];
        }
        $dropdown_options['status'] = $enrollmentStatus;
        //type of support
        $hristypeOfSupport = $db_ext->select("SET NOCOUNT ON; EXEC GetTypeOfSupport");
        $typeOfSupport = [];
        foreach($hristypeOfSupport as $row){
            $typeOfSupport[] = (object)[
                'id' => $row->TypeOfSupportID,
                'name' => $row->TypeOfSupport,
            ];
        }
        $dropdown_options['support_type'] = $typeOfSupport;

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

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
                'document' => $educationData[0]->Attachment,
                'mimetype' => $educationData[0]->MimeType,
            ];
        }

        return view('submissions.hris.education.add', compact('id', 'educationData', 'educFields', 'values', 'colleges' , 'collegeOfDepartment', 'hrisDocuments', 'departments', 'dropdown_options'));
    }

    public function store(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        if($request->has('document')){
            $datastring = file_get_contents($request->file('document'));
            $mimetype = $request->file('document')->getMimeType();
            $imagedata = unpack("H*hex", $datastring);
            $imagedata = '0x' . strtoupper($imagedata['hex']);
        }

        //is_graduated
        $is_graduated = 'Yes';
        if($request->is_graduated == 'No'){
            $is_graduated = 'No';
        }

        //is_enrolled
        $is_enrolled = 'Yes';
        if($request->is_enrolled == 'No'){
            $is_enrolled = 'No';
        }

        //year graduated
        if(!ctype_alpha($request->to)){
            $year_graduated = $request->to;
        }
        $value = [
            $id,                                  //EmployeeEducationBackgroundID
            $emp_code,                          //EmpCode
            $request->level,                    //EducationLevelID
            $request->degree,                   //Degree
            $request->major ?? '',                    //Major
            $request->minor ?? '',                    //Minor
            $request->education_discipline ?? 0,     //EducationDisciplineID
            $request->school_name,              //SchoolName
            $request->program_level ?? 0,            //AccreditationLevelID
            $is_graduated,                      //IsGraduated
            $is_enrolled,                       //IsCurrentlyEnrolled
            $request->status ?? 0,                   //EnrollmentStatusID
            $request->units_earned ?? '',             //UnitsEarned
            $request->units_enrolled ?? '',           //UnitsEnrolled
            $request->from,                     //IncYearFrom
            $request->to,                       //IncYearTo
            $year_graduated ?? 0,            //YearGraduated
            $request->honors ?? '',                   //HonorsReceived
            $request->support_type ?? 0,             //TypeOfSupportID
            $request->sponsor_name ?? '',             //Scholarship
            $request->amount ?? 0,                   //Amount
            '',                                 //Remarks
            $request->description,              //AttachmentDescription
            $imagedata ?? null,                 //Attachment
            $mimetype ?? null,                 //Attachment
            $user->email                        //TransAccount
        ];

        $newid = $db_ext->select(
            "
                DECLARE @NewEmployeeEducationBackgroundID int;
                EXEC SaveEmployeeEducationBackground
                    @EmployeeEducationBackgroundID = ?,
                    @EmpCode = ?,
                    @EducationLevelID = ?,
                    @Degree = ?,
                    @Major = ?,
                    @Minor = ?,
                    @EducationDisciplineID = ?,
                    @SchoolName = ?,
                    @AccreditationLevelID = ?,
                    @IsGraduated = ?,
                    @IsCurrentlyEnrolled = ?,
                    @EnrollmentStatusID = ?,
                    @UnitsEarned = ?,
                    @UnitsEnrolled = ?,
                    @IncYearFrom = ?,
                    @IncYearTo = ?,
                    @YearGraduated = ?,
                    @HonorsReceived = ?,
                    @TypeOfSupportID = ?,
                    @Scholarship = ?,
                    @Amount = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @MimeType = ?,
                    @TransAccount = ?,
                    @NewEmployeeEducationBackgroundID = @NewEmployeeEducationBackgroundID OUTPUT;

                SELECT @NewEmployeeEducationBackgroundID as NewEmployeeEducationBackgroundID;

            ", $value);

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
            'level' => $educationData[0]->EducationLevel,
            'degree' =>  $educationData[0]->Degree,
            'major' =>  $educationData[0]->Major,
            'minor' =>  $educationData[0]->Minor,
            'education_discipline' =>  $educationData[0]->EducationDiscipline,
            'school_name' => $educationData[0]->SchoolName,
            'program_level' => $educationData[0]->AccreditationLevel,
            'is_graduated' => ($educationData[0]->IsGraduated == 'Y') ? 'Yes' : 'No',
            'is_enrolled' => ($educationData[0]->IsCurrentlyEnrolled == 'Y') ? 'Yes' : 'No',
            'status' => $educationData[0]->EnrollmentStatus,
            'units_earned' => $educationData[0]->UnitsEarned,
            'units_enrolled' => $educationData[0]->UnitsEnrolled,
            'from' => $educationData[0]->IncYearFrom,
            'to' => $educationData[0]->IncYearTo,
            'year_graduated' => $educationData[0]->YearGraduated,
            'honors' => $educationData[0]->HonorsReceived,
            'support_type' => $educationData[0]->TypeOfSupport,
            'sponsor_name' => $educationData[0]->Scholarship,
            'amount' => $educationData[0]->Amount,
            'description' => $educationData[0]->Description,
            'document' => $educationData[0]->Attachment,
            'department_id' => Department::where('id', $department_id)->pluck('name')->first(),
            'college_id' => College::where('id', Department::where('id', $department_id)->pluck('college_id')->first())->pluck('name')->first(),
            'mimetype' => $educationData[0]->MimeType,
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $forview = '';

        $this->storageFileController->fetch_image($id, '1');
        return view('submissions.hris.education.add', compact('id', 'educationData', 'educFields', 'values', 'colleges','departments', 'forview'));
    }

    public function edit($id){
        $user = User::find(auth()->id());
        $currentQuarter = Quarter::find(1)->current_quarter;

        $educID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '1')->pluck('hris_id')->first();

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
            'level' => $educationData[0]->EducationLevelID,
            'degree' =>  $educationData[0]->Degree,
            'major' =>  $educationData[0]->Major,
            'minor' =>  $educationData[0]->Minor,
            'education_discipline' =>  $educationData[0]->EducationDisciplineID,
            'school_name' => $educationData[0]->SchoolName,
            'program_level' => $educationData[0]->AccreditationLevelID,
            'is_graduated' => ($educationData[0]->IsGraduated == 'Y') ? 'Yes' : 'No',
            'is_enrolled' => ($educationData[0]->IsCurrentlyEnrolled == 'Y') ? 'Yes' : 'No',
            'status' => $educationData[0]->EnrollmentStatusID,
            'units_earned' => $educationData[0]->UnitsEarned,
            'units_enrolled' => $educationData[0]->UnitsEnrolled,
            'from' => $educationData[0]->IncYearFrom,
            'to' => $educationData[0]->IncYearTo,
            'year_graduated' => $educationData[0]->YearGraduated,
            'honors' => $educationData[0]->HonorsReceived,
            'support_type' => $educationData[0]->TypeOfSupportID,
            'sponsor_name' => $educationData[0]->Scholarship,
            'amount' => $educationData[0]->Amount,
            'remarks' => $educationData[0]->Remarks,
            'description' => $educationData[0]->Description,
            'department_id' => $department_id,
            'document' => $educationData[0]->Attachment,
            'mimetype' => $educationData[0]->MimeType,
        ];

        $dropdown_options = [];

        //education level
        $hriseducationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");
        $educationLevel = [];
        foreach($hriseducationLevel as $row){
            $educationLevel[] = (object)[
                'id' => $row->EducationLevelID,
                'name' => $row->EducationLevel,
            ];
        }
        $dropdown_options['level'] = $educationLevel;
        //education discipline
        $hriseducationDiscipline = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationDiscipline");
        $educationDiscipline = [];
        foreach($hriseducationDiscipline as $row){
            $educationDiscipline[] = (object)[
                'id' => $row->EducationDisciplineID,
                'name' => $row->EducationDiscipline,
            ];
        }
        $dropdown_options['education_discipline'] = $educationDiscipline;
        //accreditation level
        $hrisaccreditationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetAccreditationLevels");
        $accreditationLevel = [];
        foreach($hrisaccreditationLevel as $row){
            $accreditationLevel[] = (object)[
                'id' => $row->AccreditationLevelID,
                'name' => $row->AccreditationLevel,
            ];
        }
        $dropdown_options['program_level'] = $accreditationLevel;
        //enrollment status
        $hrisenrollmentStatus = $db_ext->select("SET NOCOUNT ON; EXEC GetEnrollmentStatus");
        $enrollmentStatus = [];
        foreach($hrisenrollmentStatus as $row){
            $enrollmentStatus[] = (object)[
                'id' => $row->EnrollmentStatusID,
                'name' => $row->EnrollmentStatus,
            ];
        }
        $dropdown_options['status'] = $enrollmentStatus;
        //type of support
        $hristypeOfSupport = $db_ext->select("SET NOCOUNT ON; EXEC GetTypeOfSupport");
        $typeOfSupport = [];
        foreach($hristypeOfSupport as $row){
            $typeOfSupport[] = (object)[
                'id' => $row->TypeOfSupportID,
                'name' => $row->TypeOfSupport,
            ];
        }
        $dropdown_options['support_type'] = $typeOfSupport;

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('submissions.hris.education.edit', compact('id', 'educationData', 'educFields', 'values', 'colleges','departments', 'dropdown_options', 'currentQuarter'));
    }

    public function update(Request $request, $id){

        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        try {
            if($request->has('document')){
                $datastring = file_get_contents($request->file('document'));
                $mimetype = $request->file('document')->getMimeType();
                $imagedata = unpack("H*hex", $datastring);
                $imagedata = '0x' . strtoupper($imagedata['hex']);
            }
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        }


        //is_graduated
        $is_graduated = 'Y';
        if($request->is_graduated == 'No'){
            $is_graduated = 'N';
        }

        //is_enrolled
        $is_enrolled = 'Y';
        if($request->is_enrolled == 'No'){
            $is_enrolled = 'N';
        }

        //year graduated
        if(!ctype_alpha($request->to)){
            $year_graduated = $request->to;
        }

        $value = [
            $id,                                  //EmployeeEducationBackgroundID
            $emp_code,                          //EmpCode
            $request->level,                    //EducationLevelID
            $request->degree,                   //Degree
            $request->major ?? '',                    //Major
            $request->minor ?? '',                    //Minor
            $request->education_discipline ?? 0,     //EducationDisciplineID
            $request->school_name,              //SchoolName
            $request->program_level ?? 0,            //AccreditationLevelID
            $is_graduated,                      //IsGraduated
            $is_enrolled,                       //IsCurrentlyEnrolled
            $request->status ?? 0,                   //EnrollmentStatusID
            $request->units_earned ?? '',             //UnitsEarned
            $request->units_enrolled ?? '',           //UnitsEnrolled
            $request->from,                     //IncYearFrom
            $request->to,                       //IncYearTo
            $year_graduated ?? 0,            //YearGraduated
            $request->honors ?? '',                   //HonorsReceived
            $request->support_type ?? 0,             //TypeOfSupportID
            $request->sponsor_name ?? '',             //Scholarship
            $request->amount ?? 0,                   //Amount
            '',                                 //Remarks
            $request->description,              //AttachmentDescription
            $imagedata ?? null,                 //Attachment
            $mimetype ?? null, //MimeType
            $user->email                        //TransAccount
        ];

        $newid = $db_ext->select(
            "
                DECLARE @NewEmployeeEducationBackgroundID int;
                EXEC SaveEmployeeEducationBackground
                    @EmployeeEducationBackgroundID = ?,
                    @EmpCode = ?,
                    @EducationLevelID = ?,
                    @Degree = ?,
                    @Major = ?,
                    @Minor = ?,
                    @EducationDisciplineID = ?,
                    @SchoolName = ?,
                    @AccreditationLevelID = ?,
                    @IsGraduated = ?,
                    @IsCurrentlyEnrolled = ?,
                    @EnrollmentStatusID = ?,
                    @UnitsEarned = ?,
                    @UnitsEnrolled = ?,
                    @IncYearFrom = ?,
                    @IncYearTo = ?,
                    @YearGraduated = ?,
                    @HonorsReceived = ?,
                    @TypeOfSupportID = ?,
                    @Scholarship = ?,
                    @Amount = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @MimeType = ?,
                    @TransAccount = ?,
                    @NewEmployeeEducationBackgroundID = @NewEmployeeEducationBackgroundID OUTPUT;

                SELECT @NewEmployeeEducationBackgroundID as NewEmployeeEducationBackgroundID;

            ", $value);

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '1')->update([
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had updated a Ongoing/Advanced Professional Study.');

        return redirect()->route('submissions.educ.index')->with('success','The accomplishment has been updated.');
    }

    public function delete($id){
        $educID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '1')->pluck('hris_id')->first();

        if(LockController::isLocked($educID, 24)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');

        $db_ext->statement(
            "
                EXEC DeleteEmployeeEducationBackground
                    @EmployeeEducationBackgroundID = ?,
                    @EmpCode = ?;
            ", array($id, $user->emp_code)
        );

        if(!is_null($educID)){
            HRIS::where('id', $educID)->delete();
        }

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
        $employee = Employee::where('user_id', auth()->id())->where('college_id', $education->college_id)->get();

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$education->hris_id");

        $sector_id = College::where('id', $education->college_id)->pluck('sector_id')->first();
        $department_name = Department::where('id', $education->department_id)->pluck('name')->first();
        $college_name = College::where('id', $education->college_id)->pluck('name')->first();

        $filenames = [];
        $imagejpeg = ['image/jpeg', 'image/pjpeg', 'image/jpg', 'image/jfif', 'image/pjp'];


        try {
            if(in_array($educationData[0]->MimeType, $imagejpeg)){
                $file = Image::make($educationData[0]->Attachment);
                $fileName = 'HRIS-OPS-'.now()->timestamp.uniqid().'.jpeg';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $file->save($newPath);
            }
            elseif($educationData[0]->MimeType == 'image/png' || $educationData['0']->MimeType == 'image/x-png'){
                $file = Image::make($educationData[0]->Attachment);
                $fileName = 'HRIS-OPS-'.now()->timestamp.uniqid().'.png';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $file->save($newPath);
            }
            elseif($educationData[0]->MimeType == 'application/pdf'){
                $fileName = 'HRIS-OPS-'.now()->timestamp.uniqid().'.pdf';
                file_put_contents(storage_path().'/app/documents/'.$fileName, $educationData[0]->Attachment);
                $file = true;
            } else {
                $file = Image::make($educationData[0]->Attachment);
                $fileName = 'HRIS-OPS-'.now()->timestamp.uniqid().'.png';
                $newPath = storage_path().'/app/documents/'.$fileName;
                $file->save($newPath);
            }
    
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        }

        

        HRISDocument::create([
            'hris_form_id' => 1,
            'reference_id' => $education_id,
            'filename' => $fileName,
        ]);
        array_push($filenames, $fileName);

        $values = [
            'level' => $educationData[0]->EducationLevel,
            'degree' =>  $educationData[0]->Degree,
            'major' =>  $educationData[0]->Major,
            'minor' =>  $educationData[0]->Minor,
            'education_discipline' =>  $educationData[0]->EducationDiscipline,
            'school_name' => $educationData[0]->SchoolName,
            'program_level' => $educationData[0]->AccreditationLevel,
            'is_graduated' => ($educationData[0]->IsGraduated == 'Y') ? 'Yes' : 'No',
            'is_enrolled' => ($educationData[0]->IsCurrentlyEnrolled == 'Y') ? 'Yes' : 'No',
            'status' => $educationData[0]->EnrollmentStatus,
            'units_earned' => $educationData[0]->UnitsEarned,
            'units_enrolled' => $educationData[0]->UnitsEnrolled,
            'from' => $educationData[0]->IncYearFrom,
            'to' => $educationData[0]->IncYearTo,
            'year_graduated' => $educationData[0]->YearGraduated,
            'honors' => $educationData[0]->HonorsReceived,
            'support_type' => $educationData[0]->TypeOfSupport,
            'sponsor_name' => $educationData[0]->Scholarship,
            'amount' => $educationData[0]->Amount,
            'description' => $educationData[0]->Description,
            'department_id' => $department_name,
            'college_id' => $college_name,
        ];

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

        Report::where('report_reference_id', $education->hris_id)
            ->where('report_category_id', 24)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        if ($type == 'a') {
            if ($education->department_id == $education->college_id) {
                Report::create([
                    'user_id' =>  auth()->id(),
                    'sector_id' => $sector_id,
                    'college_id' => $education->college_id,
                    'department_id' => $education->department_id,
                    'format' => $type,
                    'report_category_id' => 24,
                    'report_code' => null,
                    'report_reference_id' => $education->hris_id,
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
                    'college_id' => $education->college_id,
                    'department_id' => $education->department_id,
                    'format' => $type,
                    'report_category_id' => 24,
                    'report_code' => null,
                    'report_reference_id' => $education->hris_id,
                    'report_details' => json_encode($values),
                    'report_documents' => json_encode($filenames),
                    'report_date' => date("Y-m-d", time()),
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
            }
        } elseif ($type == 'f') {
            if ($education->department_id == $education->college_id) {
                if ($education->department_id >= 227 && $education->department_id <= 248) { // If branch
                    Report::create([
                        'user_id' =>  auth()->id(),
                        'sector_id' => $sector_id,
                        'college_id' => $education->college_id,
                        'department_id' => $education->department_id,
                        'format' => $type,
                        'report_category_id' => 24,
                        'report_code' => null,
                        'report_reference_id' => $education->hris_id,
                        'report_details' => json_encode($values),
                        'report_documents' => json_encode($filenames),
                        'report_date' => date("Y-m-d", time()),
                        'report_quarter' => $currentQuarterYear->current_quarter,
                        'report_year' => $currentQuarterYear->current_year,
                    ]);
                } else {
                    if ($report_values_array[1] >= 1 && $report_values_array[1] <= 8) {
                        Report::create([
                            'user_id' =>  auth()->id(),
                            'sector_id' => $sector_id,
                            'college_id' => $education->college_id,
                            'department_id' => $education->department_id,
                            'format' => $type,
                            'report_category_id' => 24,
                            'report_code' => null,
                            'report_reference_id' => $education->hris_id,
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
                            'college_id' => $education->college_id,
                            'department_id' => $education->department_id,
                            'format' => $type,
                            'report_category_id' => 24,
                            'report_code' => null,
                            'report_reference_id' => $education->hris_id,
                            'report_details' => json_encode($values),
                            'report_documents' => json_encode($filenames),
                            'report_date' => date("Y-m-d", time()),
                            'chairperson_approval' => 1,
                            'report_quarter' => $currentQuarterYear->current_quarter,
                            'report_year' => $currentQuarterYear->current_year,
                        ]);
                    }
                }
            } else {
                Report::create([
                    'user_id' =>  auth()->id(),
                    'sector_id' => $sector_id,
                    'college_id' => $education->college_id,
                    'department_id' => $education->department_id,
                    'format' => $type,
                    'report_category_id' => 24,
                    'report_code' => null,
                    'report_reference_id' => $education->hris_id,
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
            try {
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
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
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
