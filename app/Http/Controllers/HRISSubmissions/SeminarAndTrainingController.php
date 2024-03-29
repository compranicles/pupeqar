<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Helpers\LogActivity;
use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
};
use App\Models\{
    Employee,
    HRISDocument,
    Report,
    User,
    HRIS,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\HRISField,
    Maintenance\Quarter,
};
use App\Services\CommonService;
use Carbon\Carbon;
use App\Services\SavePersonalDataDocumentService;
use stdClass;

class SeminarAndTrainingController extends Controller
{
    private $commonService;

    public function __construct(CommonService $commonService) {
        $this->commonService = $commonService;
    }

    public function index(){
        $currentQuarterYear = Quarter::find(1);

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $developmentFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        // $seminarReports = Report::where('report_category_id', 25)->where('user_id', $user->id)->select('report_reference_id', 'report_quarter', 'report_year')->get();
        // $trainingReports = Report::where('report_category_id', 26)->where('user_id', $user->id)->select('report_reference_id', 'report_quarter', 'report_year')->get();

        $savedSeminars = HRIS::where('hris_type', '4')->where('user_id', $user->id)->pluck('hris_id')->all();
        $savedTrainings = HRIS::where('hris_type', '5')->where('user_id', $user->id)->pluck('hris_id')->all();

        $submissionStatus = array();
        $submitRole = array();
        foreach ($developmentFinal as $development) {
            $id = HRIS::where('hris_id', $development->EmployeeTrainingProgramID)->where('hris_type', 4)->where('user_id', $user->id)->pluck('hris_id')->first();
            if($id != ''){
                if (LockController::isLocked($id, 25)) {
                    $submissionStatus[25][$development->EmployeeTrainingProgramID] = 1;
                    $submitRole[$id] = ReportDataController::getSubmitRole($id, 25);
                }
                else
                    $submissionStatus[25][$development->EmployeeTrainingProgramID] = 0;
                if ($development->AttachmentCert == null)
                    $submissionStatus[25][$development->EmployeeTrainingProgramID] = 2;
            }

            $id = HRIS::where('hris_id', $development->EmployeeTrainingProgramID)->where('hris_type', 5)->where('user_id', $user->id)->pluck('hris_id')->first();
            if($id != ''){
                if (LockController::isLocked($id, 26)) {
                    $submissionStatus[26][$development->EmployeeTrainingProgramID] = 1;
                    $submitRole[$id] = ReportDataController::getSubmitRole($id, 26);
                }
                else
                    $submissionStatus[26][$development->EmployeeTrainingProgramID] = 0;
                if ($development->AttachmentCert == null)
                    $submissionStatus[26][$development->EmployeeTrainingProgramID] = 2;
            }
        }
        // dd($submissionStatus);
        return view('submissions.hris.development.index', compact('developmentFinal', 'savedSeminars', 'savedTrainings', 'currentQuarterYear', 'submissionStatus', 'submitRole'));
    }

    public function create(){
        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');
        $currentQuarter = Quarter::find(1)->current_quarter;

        $fields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 4)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

                if(session()->get('user_type') == 'Faculty Employee')
                $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
            else
                $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $values = [];
        $dropdown_options = [];

        //classification
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetClassifications");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->ClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;
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
        //type
        $hristypes = $db_ext->select("SET NOCOUNT ON; EXEC GetType");
        $types = [];
        foreach($hristypes as $row){
            $types[] = (object)[
                'id' => $row->TypeID,
                'name' => $row->Type,
            ];
        }
        $types = collect($types);
        $dropdown_options['type'] = $types;
        //nature
        $hrisnatures = $db_ext->select("SET NOCOUNT ON; EXEC GetNature");
        $natures = [];
        foreach($hrisnatures as $row){
            $natures[] = (object)[
                'id' => $row->NatureID,
                'name' => $row->Nature,
            ];
        }
        $natures = collect($natures);
        $dropdown_options['nature'] = $natures;
        //soure of fund
        $hrisfunds = $db_ext->select("SET NOCOUNT ON; EXEC GetSourceOfFunds");
        $funds = [];
        foreach($hrisfunds as $row){
            $funds[] = (object)[
                'id' => $row->SourceOfFundID,
                'name' => $row->SourceOfFund,
            ];
        }
        $funds = collect($funds);
        $dropdown_options['fund_source'] = $funds;

        return view('submissions.hris.development.create', compact('fields', 'departments', 'values', 'dropdown_options', 'currentQuarter'));
    }

    public function savetohris(Request $request){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        $is_paid = 'Y';
        if($request->fund_source == '0' && $request->budget == 0){
            $is_paid = 'N';
        }

        // if($request->has('documentSO')){
        //     $datastringSO = file_get_contents($request->file(['documentSO']));
        //     $mimetypeSO = $request->file('documentSO')->getMimeType();
        //     $imagedataSO = unpack("H*hex", $datastringSO);
        //     $imagedataSO = '0x' . strtoupper($imagedataSO['hex']);
        //     $descriptionSO = "SPECIAL ORDER (S.O.) DOCUMENT";
        // }
        // if($request->has('documentCert')){
        //     $datastringCert = file_get_contents($request->file(['documentCert']));
        //     $mimetypeCert = $request->file('documentCert')->getMimeType();
        //     $imagedataCert = unpack("H*hex", $datastringCert);
        //     $imagedataCert = '0x' . strtoupper($imagedataCert['hex']);
        //     $descriptionCert = "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION";
        // }
        // if($request->has('documentPic')){
        //     $datastringPic = file_get_contents($request->file(['documentPic']));
        //     $mimetypePic = $request->file('documentPic')->getMimeType();
        //     $imagedataPic = unpack("H*hex", $datastringPic);
        //     $imagedataPic = '0x' . strtoupper($imagedataPic['hex']);
        //     $descriptionPic = "COMPILED PHOTOS";
        // }

        // '', //Remarks
        // $descriptionSO ?? null, //AttachmentDescSO
        // $imagedataSO ?? null, //AttachmentSO
        // $mimetypeSO ?? null, //MimeTypeSO
        // $descriptionCert ?? null, //AttachmentDescCert
        // $imagedataCert ?? null, //AttachmentCert
        // $mimetypeCert ?? null, //MimeTypeCert
        // $descriptionPic ?? null, //AttachmentDescPic
        // $imagedataPic ?? null, //AttachmentPic
        // $mimetypePic ?? null, //MimeTypePic
        
        // dd($datastringSO);
        $documentSO = $this->commonService->fileUploadHandlerForExternal($request, 'documentSO', "SPECIAL ORDER (S.O.) DOCUMENT");
        $documentCert = $this->commonService->fileUploadHandlerForExternal($request, 'documentCert', "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION");
        $documentPic = $this->commonService->fileUploadHandlerForExternal($request, 'documentPic', "COMPILED PHOTOS");

        $value = array(
            0, //EmployeeTrainingProgramID
            $emp_code, //EmpCode
            $request->title, //TrainingProgram
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            Carbon::parse($request->to)->format('Y-m-d'), //IncDateTo
            $request->total_hours, //NumberOfHours
            $request->organizer, //Conductor
            $request->level, //LevelID
            $request->type, //TypeID
            $request->classification, //ClassificationID
            $request->nature, //NatureID
            $is_paid, //IsPaid
            $request->fund_source, //SourceOfFundID
            $request->budget, //Budget
            'image/pdf/files', //Remarks

            $documentSO['description'], //AttachmentDescSO
            $documentSO['image'], //AttachmentSO
            $documentSO['mimetype'], //MimeTypeSO

            $documentCert['description'], //AttachmentDescCert
            $documentCert['image'], //AttachmentCert
            $documentCert['mimetype'], //MimeTypeCert

            $documentPic['description'], //AttachmentDescPic
            $documentPic['image'], //AttachmentPic
            $documentPic['mimetype'], //MimeTypePic

            $user->email //TransAccount
        );

        // dd($value);
        $id = $db_ext->select(
            "
                DECLARE @NewEmployeeTrainingProgramID int;

                EXEC SaveEmployeeTrainingProgram
                    @EmployeeTrainingProgramID = ?,
                    @EmpCode = ?,
                    @TrainingProgram = ?,
                    @Venue = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @NumberOfHours = ?,
                    @Conductor = ?,
                    @LevelID = ?,
                    @TypeID = ?,
                    @ClassificationID = ?,
                    @NatureID = ?,
                    @IsPaid = ?,
                    @SourceOfFundID = ?,
                    @Budget = ?,
                    @Remarks = ?,
                    @AttachmentDescSO= ?,
                    @AttachmentSO = ?,
                    @MimeTypeSO= ?,
                    @AttachmentDescCert= ?,
                    @AttachmentCert = ?,
                    @MimeTypeCert= ?,
                    @AttachmentDescPic= ?,
                    @AttachmentPic = ?,
                    @MimeTypePic= ?,
                    @TransAccount = ?,
                    @NewEmployeeTrainingProgramID = @NewEmployeeTrainingProgramID OUTPUT;

                SELECT @NewEmployeeTrainingProgramID as NewEmployeeTrainingProgramID;

            ", $value
        );

        $hris_type = 4;
        if($request->classification >= 5) //>= 5 is for training
            $hris_type = 5;

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id[0]->NewEmployeeTrainingProgramID,
            'hris_type' => $hris_type,
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        LogActivity::addToLog('Had saved a Seminar/Webinar.');

        if($documentSO['isError'] == false && $documentCert['isError'] == false && $documentPic['isError'] == false){
            return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.development.index')->with('error', 
                $documentSO['message'] . " | " . 
                $documentCert['message'] . " | " . 
                $documentPic['message']
            );
        }

        // return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
    }

    public function add($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $seminar = new stdClass();

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

        $dropdown_options = [];

        //classification
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetClassifications");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->ClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;
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
        //type
        $hristypes = $db_ext->select("SET NOCOUNT ON; EXEC GetType");
        $types = [];
        foreach($hristypes as $row){
            $types[] = (object)[
                'id' => $row->TypeID,
                'name' => $row->Type,
            ];
        }
        $types = collect($types);
        $dropdown_options['type'] = $types;
        //nature
        $hrisnatures = $db_ext->select("SET NOCOUNT ON; EXEC GetNature");
        $natures = [];
        foreach($hrisnatures as $row){
            $natures[] = (object)[
                'id' => $row->NatureID,
                'name' => $row->Nature,
            ];
        }
        $natures = collect($natures);
        $dropdown_options['nature'] = $natures;
        //soure of fund
        $hrisfunds = $db_ext->select("SET NOCOUNT ON; EXEC GetSourceOfFunds");
        $funds = [];
        foreach($hrisfunds as $row){
            $funds[] = (object)[
                'id' => $row->SourceOfFundID,
                'name' => $row->SourceOfFund,
            ];
        }
        $funds = collect($funds);
        $dropdown_options['fund_source'] = $funds;

        $values = [
            'title' => $seminar->TrainingProgram,
            'classification' => $seminar->ClassificationID,
            'nature' => $seminar->NatureID,
            'budget' => $seminar->Budget,
            'type' => $seminar->TypeID,
            'fund_source' => $seminar->SourceOfFundID,
            'organizer' => $seminar->Conductor,
            'level' => $seminar->LevelID,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours,
            'documentSO' => $seminar->AttachmentSO,
            'documentCert' => $seminar->AttachmentCert,
            'documentPic' => $seminar->AttachmentPic,
            'description' => "",
            'id' => $seminar->EmployeeTrainingProgramID,
            'mimeTypeSO' => $seminar->MimeTypeSO,
            'mimeTypeCert' => $seminar->MimeTypeCert,
            'mimeTypePic' => $seminar->MimeTypePic,
        ];

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

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
            $description = "";

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
                'classification' => $seminar->ClassificationID,
                'nature' => $seminar->NatureID,
                'budget' => $seminar->Budget,
                'type' => $seminar->TypeID,
                'fund_source' => $seminar->SourceOfFundID,
                'organizer' => $seminar->Conductor,
                'level' => $seminar->LevelID,
                'venue' => $seminar->Venue,
                'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
                'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
                'total_hours' => $seminar->NumberOfHours,
                'documentSO' => $seminar->AttachmentSO,
                'documentCert' => $seminar->AttachmentCert,
                'documentPic' => $seminar->AttachmentPic,
                'description' => "",
                'id' => $seminar->EmployeeTrainingProgramID,
                'mimeTypeSO' => $seminar->MimeTypeSO,
                'mimeTypeCert' => $seminar->MimeTypeCert,
                'mimeTypePic' => $seminar->MimeTypePic,
            ];
        }

        if($seminar->ClassificationID >= 5){
            $training = $seminar;
            $trainingFields = $seminarFields;
            return view('submissions.hris.development.training.add', compact('id', 'training', 'trainingFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments', 'dropdown_options'));
        }

        return view('submissions.hris.development.seminar.add', compact('id', 'seminar', 'seminarFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments', 'dropdown_options'));
    }

    public function storeSeminar(Request $request, $id){

        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        $is_paid = 'Y';
        if($request->fund_source == '0' && $request->budget == 0){
            $is_paid = 'N';
        }

        $documentSO = $this->commonService->fileUploadHandlerForExternal($request, 'documentSO', "SPECIAL ORDER (S.O.) DOCUMENT");
        $documentCert = $this->commonService->fileUploadHandlerForExternal($request, 'documentCert', "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION");
        $documentPic = $this->commonService->fileUploadHandlerForExternal($request, 'documentPic', "COMPILED PHOTOS");

        // if($request->has('documentSO')){
        //     $datastring = file_get_contents($request->file(['documentSO']));
        //     $mimetypeSO = $request->file('documentSO')->getMimeType();
        //     $imagedataSO = unpack("H*hex", $datastring);
        //     $imagedataSO = '0x' . strtoupper($imagedataSO['hex']);
        //     $descriptionSO = "SPECIAL ORDER (S.O.) DOCUMENT";
        // }
        // if($request->has('documentCert')){
        //     $datastring = file_get_contents($request->file(['documentCert']));
        //     $mimetypeCert = $request->file('documentCert')->getMimeType();
        //     $imagedataCert = unpack("H*hex", $datastring);
        //     $imagedataCert = '0x' . strtoupper($imagedataCert['hex']);
        //     $descriptionCert = "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION";
        // }
        // if($request->has('documentPic')){
        //     $datastring = file_get_contents($request->file(['documentPic']));
        //     $mimetypePic = $request->file('documentPic')->getMimeType();
        //     $imagedataPic = unpack("H*hex", $datastring);
        //     $imagedataPic = '0x' . strtoupper($imagedataPic['hex']);
        //     $descriptionPic = "COMPILED PHOTOS";
        // }

        // $descriptionSO ?? null, //AttachmentDescSO
        // $imagedataSO ?? null, //AttachmentSO
        // $mimetypeSO ?? null, //MimeTypeSO
        // $descriptionCert ?? null, //AttachmentDescCert
        // $imagedataCert ?? null, //AttachmentCert
        // $mimetypeCert ?? null, //MimeTypeCert
        // $descriptionPic ?? null, //AttachmentDescPic
        // $imagedataPic ?? null, //AttachmentPic
        // $mimetypePic ?? null, //MimeTypePic

        $value = array(
            $id, //EmployeeTrainingProgramID
            $emp_code, //EmpCode
            $request->title, //TrainingProgram
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            Carbon::parse($request->to)->format('Y-m-d'), //IncDateTo
            $request->total_hours, //NumberOfHours
            $request->organizer, //Conductor
            $request->level, //LevelID
            $request->type, //TypeID
            $request->classification ?? 0, //ClassificationID
            $request->nature ?? 0, //NatureID
            $is_paid ?? '', //IsPaid
            $request->fund_source ?? 0, //SourceOfFundID
            $request->budget ?? '', //Budget
            'image/pdf/files', //Remarks

            $documentSO['description'], //AttachmentDescSO
            $documentSO['image'], //AttachmentSO
            $documentSO['mimetype'], //MimeTypeSO

            $documentCert['description'], //AttachmentDescCert
            $documentCert['image'], //AttachmentCert
            $documentCert['mimetype'], //MimeTypeCert

            $documentPic['description'], //AttachmentDescPic
            $documentPic['image'], //AttachmentPic
            $documentPic['mimetype'], //MimeTypePic

            $user->email //TransAccount
        );

        $db_ext->select(
            "
                DECLARE @NewEmployeeTrainingProgramID int;

                EXEC SaveEmployeeTrainingProgram
                    @EmployeeTrainingProgramID = ?,
                    @EmpCode = ?,
                    @TrainingProgram = ?,
                    @Venue = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @NumberOfHours = ?,
                    @Conductor = ?,
                    @LevelID = ?,
                    @TypeID = ?,
                    @ClassificationID = ?,
                    @NatureID = ?,
                    @IsPaid = ?,
                    @SourceOfFundID = ?,
                    @Budget = ?,
                    @Remarks = ?,
                    @AttachmentDescSO= ?,
                    @AttachmentSO = ?,
                    @MimeTypeSO= ?,
                    @AttachmentDescCert= ?,
                    @AttachmentCert = ?,
                    @MimeTypeCert= ?,
                    @AttachmentDescPic= ?,
                    @AttachmentPic = ?,
                    @MimeTypePic= ?,
                    @TransAccount = ?,
                    @NewEmployeeTrainingProgramID = @NewEmployeeTrainingProgramID OUTPUT;

                SELECT @NewEmployeeTrainingProgramID as NewEmployeeTrainingProgramID;

            ", $value
        );

        HRIS::create([
            'hris_id' => $id,
            'hris_type' => '4',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        LogActivity::addToLog('Had saved a Seminar/Webinar.');

        if($documentSO['isError'] == false && $documentCert['isError'] == false && $documentPic['isError'] == false){
            return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.development.index')->with('error', 
                $documentSO['message'] . " | " . 
                $documentCert['message'] . " | " . 
                $documentPic['message']
                // "Entry was saved but unable to upload some document/s, Please try reuploading the document/s!"
            );
        }
    }

    public function storeTraining(Request $request, $id){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        $is_paid = 'Y';
        if($request->fund_source == '0' && $request->budget == 0){
            $is_paid = 'N';
        }

        $documentSO = $this->commonService->fileUploadHandlerForExternal($request, 'documentSO', "SPECIAL ORDER (S.O.) DOCUMENT");
        $documentCert = $this->commonService->fileUploadHandlerForExternal($request, 'documentCert', "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION");
        $documentPic = $this->commonService->fileUploadHandlerForExternal($request, 'documentPic', "COMPILED PHOTOS");

        $value = array(
            $id, //EmployeeTrainingProgramID
            $emp_code, //EmpCode
            $request->title, //TrainingProgram
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            Carbon::parse($request->to)->format('Y-m-d'), //IncDateTo
            $request->total_hours, //NumberOfHours
            $request->organizer, //Conductor
            $request->level, //LevelID
            $request->type, //TypeID
            $request->classification ?? 0, //ClassificationID
            $request->nature ?? 0, //NatureID
            $is_paid ?? '', //IsPaid
            $request->fund_source ?? 0, //SourceOfFundID
            $request->budget ?? '', //Budget
            'image/pdf/files', //Remarks

            $documentSO['description'], //AttachmentDescSO
            $documentSO['image'], //AttachmentSO
            $documentSO['mimetype'], //MimeTypeSO

            $documentCert['description'], //AttachmentDescCert
            $documentCert['image'], //AttachmentCert
            $documentCert['mimetype'], //MimeTypeCert

            $documentPic['description'], //AttachmentDescPic
            $documentPic['image'], //AttachmentPic
            $documentPic['mimetype'], //MimeTypePic

            $user->email //TransAccount
        );

        // if($request->has('documentSO')){
        //     $datastring = file_get_contents($request->file(['documentSO']));
        //     $mimetypeSO = $request->file('documentSO')->getMimeType();
        //     $imagedataSO = unpack("H*hex", $datastring);
        //     $imagedataSO = '0x' . strtoupper($imagedataSO['hex']);
        //     $descriptionSO = "SPECIAL ORDER (S.O.) DOCUMENT";
        // }
        // if($request->has('documentCert')){
        //     $datastring = file_get_contents($request->file(['documentCert']));
        //     $mimetypeCert = $request->file('documentCert')->getMimeType();
        //     $imagedataCert = unpack("H*hex", $datastring);
        //     $imagedataCert = '0x' . strtoupper($imagedataCert['hex']);
        //     $descriptionCert = "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION";
        // }
        // if($request->has('documentPic')){
        //     $datastring = file_get_contents($request->file(['documentPic']));
        //     $mimetypePic = $request->file('documentPic')->getMimeType();
        //     $imagedataPic = unpack("H*hex", $datastring);
        //     $imagedataPic = '0x' . strtoupper($imagedataPic['hex']);
        //     $descriptionPic = "COMPILED PHOTOS";
        // }

        // $descriptionSO ?? null, //AttachmentDescSO
        // $imagedataSO ?? null, //AttachmentSO
        // $mimetypeSO ?? null, //MimeTypeSO
        // $descriptionCert ?? null, //AttachmentDescCert
        // $imagedataCert ?? null, //AttachmentCert
        // $mimetypeCert ?? null, //MimeTypeCert
        // $descriptionPic ?? null, //AttachmentDescPic
        // $imagedataPic ?? null, //AttachmentPic
        // $mimetypePic ?? null, //MimeTypePic

        $db_ext->select(
            "
                DECLARE @NewEmployeeTrainingProgramID int;

                EXEC SaveEmployeeTrainingProgram
                    @EmployeeTrainingProgramID = ?,
                    @EmpCode = ?,
                    @TrainingProgram = ?,
                    @Venue = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @NumberOfHours = ?,
                    @Conductor = ?,
                    @LevelID = ?,
                    @TypeID = ?,
                    @ClassificationID = ?,
                    @NatureID = ?,
                    @IsPaid = ?,
                    @SourceOfFundID = ?,
                    @Budget = ?,
                    @Remarks = ?,
                    @AttachmentDescSO= ?,
                    @AttachmentSO = ?,
                    @MimeTypeSO= ?,
                    @AttachmentDescCert= ?,
                    @AttachmentCert = ?,
                    @MimeTypeCert= ?,
                    @AttachmentDescPic= ?,
                    @AttachmentPic = ?,
                    @MimeTypePic= ?,
                    @TransAccount = ?,
                    @NewEmployeeTrainingProgramID = @NewEmployeeTrainingProgramID OUTPUT;

                SELECT @NewEmployeeTrainingProgramID as NewEmployeeTrainingProgramID;

            ", $value
        );

        HRIS::create([
            'hris_id' => $id,
            'hris_type' => '5',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        LogActivity::addToLog('Had saved a Training.');

        if($documentSO['isError'] == false && $documentCert['isError'] == false && $documentPic['isError'] == false){
            return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.development.index')->with('error', 
                $documentSO['message'] . " | " . 
                $documentCert['message'] . " | " . 
                $documentPic['message']
                // "Entry was saved but unable to upload some document/s, Please try reuploading the document/s!"
            );
        }

        
    }

    public function show($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");
        $seminar = new stdClass();
        foreach($developments as $development){
            if($development->EmployeeTrainingProgramID == $id){
                $seminar = $development;
                break;
            }
        }

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->pluck('department_id')->first();
        if(is_null($department_id))
            $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->pluck('department_id')->first();

        $seminarFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 4)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        $values = [
            'title' => $seminar->TrainingProgram,
            'classification' => $seminar->Classification,
            'nature' => $seminar->Nature,
            'budget' => $seminar->Budget,
            'fund_source' => $seminar->SourceOfFund ?? '-',
            'organizer' => $seminar->Conductor,
            'type' => $seminar->Type,
            'level' => $seminar->Level,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours,
            'documentSO' => $seminar->AttachmentSO,
            'documentCert' => $seminar->AttachmentCert,
            'documentPic' => $seminar->AttachmentPic,
            'description' => $seminar->DescriptionSO.', '.$seminar->DescriptionCert.', '.$seminar->DescriptionPic,
            'id' => $seminar->EmployeeTrainingProgramID,
            'mimeTypeSO' => $seminar->MimeTypeSO,
            'mimeTypeCert' => $seminar->MimeTypeCert,
            'mimeTypePic' => $seminar->MimeTypePic,
            'department_id' => Department::where('id', $department_id)->pluck('name')->first(),
            'college_id' => College::where('id', Department::where('id', $department_id)->pluck('college_id')->first())->pluck('name')->first(),
        ];

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $forview = '';
        if($seminar->ClassificationID >= 5){
            $training = $seminar;
            $trainingFields = $seminarFields;
            return view('submissions.hris.development.training.add', compact('id', 'training', 'trainingFields', 'values', 'colleges', 'departments', 'forview'));
        }

        return view('submissions.hris.development.seminar.add', compact('id', 'seminar', 'seminarFields', 'values', 'colleges', 'departments', 'forview'));
    }

    public function edit($id){

        $currentQuarterYear = Quarter::find(1);
        $currentQuarter = Quarter::find(1)->current_quarter;

        $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->pluck('hris_id')->first();
        if(is_null($developmentID))
            $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->pluck('hris_id')->first();

        if(LockController::isLocked($developmentID, 25)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }
        if(LockController::isLocked($developmentID, 26)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");
        $seminar = new stdClass();;
        foreach($developments as $development){
            if($development->EmployeeTrainingProgramID == $id){
                $seminar = $development;
                break;
            }
        }

        $dropdown_options = [];

        //classification
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetClassifications");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->ClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;
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
        //type
        $hristypes = $db_ext->select("SET NOCOUNT ON; EXEC GetType");
        $types = [];
        foreach($hristypes as $row){
            $types[] = (object)[
                'id' => $row->TypeID,
                'name' => $row->Type,
            ];
        }
        $types = collect($types);
        $dropdown_options['type'] = $types;
        //nature
        $hrisnatures = $db_ext->select("SET NOCOUNT ON; EXEC GetNature");
        $natures = [];
        foreach($hrisnatures as $row){
            $natures[] = (object)[
                'id' => $row->NatureID,
                'name' => $row->Nature,
            ];
        }
        $natures = collect($natures);
        $dropdown_options['nature'] = $natures;
        //soure of fund
        $hrisfunds = $db_ext->select("SET NOCOUNT ON; EXEC GetSourceOfFunds");
        $funds = [];
        foreach($hrisfunds as $row){
            $funds[] = (object)[
                'id' => $row->SourceOfFundID,
                'name' => $row->SourceOfFund,
            ];
        }
        $funds = collect($funds);
        $dropdown_options['fund_source'] = $funds;

        $values = [
            'title' => $seminar->TrainingProgram,
            'classification' => $seminar->ClassificationID,
            'nature' => $seminar->NatureID,
            'budget' => $seminar->Budget,
            'type' => $seminar->TypeID,
            'fund_source' => $seminar->SourceOfFundID,
            'organizer' => $seminar->Conductor,
            'level' => $seminar->LevelID,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours,
            'documentSO' => $seminar->AttachmentSO,
            'documentCert' => $seminar->AttachmentCert,
            'documentPic' => $seminar->AttachmentPic,
            'description' => "",
            'id' => $seminar->EmployeeTrainingProgramID,
            'mimeTypeSO' => $seminar->MimeTypeSO,
            'mimeTypeCert' => $seminar->MimeTypeCert,
            'mimeTypePic' => $seminar->MimeTypePic,
        ];

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->pluck('department_id')->first();
        if(is_null($department_id))
            $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->pluck('department_id')->first();

        $seminarFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 4)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        $values = [
            'title' => $seminar->TrainingProgram,
            'classification' => $seminar->ClassificationID,
            'nature' => $seminar->NatureID,
            'budget' => $seminar->Budget,
            'type' => $seminar->TypeID,
            'fund_source' => $seminar->SourceOfFundID,
            'organizer' => $seminar->Conductor,
            'level' => $seminar->LevelID,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours,
            'documentSO' => $seminar->AttachmentSO,
            'documentCert' => $seminar->AttachmentCert,
            'documentPic' => $seminar->AttachmentPic,
            'description' => "",
            'id' => $seminar->EmployeeTrainingProgramID,
            'mimeTypeSO' => $seminar->MimeTypeSO,
            'mimeTypeCert' => $seminar->MimeTypeCert,
            'mimeTypePic' => $seminar->MimeTypePic,
            'department_id' => $department_id,
        ];

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        if($seminar->ClassificationID >= 5){
            $training = $seminar;
            $trainingFields = $seminarFields;
            return view('submissions.hris.development.training.edit', compact('id', 'training', 'trainingFields', 'values', 'colleges', 'departments', 'dropdown_options', 'currentQuarter'));
        }

        return view('submissions.hris.development.seminar.edit', compact('id', 'seminar', 'seminarFields', 'values', 'colleges', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    public function updateSeminar(Request $request, $id){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        $is_paid = 'Y';
        if($request->fund_source == '0' && $request->budget == 0){
            $is_paid = 'N';
        }

        // if($request->has('documentSO')){
        //     $datastringSO = file_get_contents($request->file(['documentSO']));
        //     $mimetypeSO = $request->file('documentSO')->getMimeType();
        //     $imagedataSO = unpack("H*hex", $datastringSO);
        //     $imagedataSO = '0x' . strtoupper($imagedataSO['hex']);
        // }
        // if($request->has('documentCert')){
        //     $datastringCert = file_get_contents($request->file(['documentCert']));
        //     $mimetypeCert = $request->file('documentCert')->getMimeType();
        //     $imagedataCert = unpack("H*hex", $datastringCert);
        //     $imagedataCert = '0x' . strtoupper($imagedataCert['hex']);
        // }
        // if($request->has('documentPic')){
        //     $datastringPic = file_get_contents($request->file(['documentPic']));
        //     $mimetypePic = $request->file('documentPic')->getMimeType();
        //     $imagedataPic = unpack("H*hex", $datastringPic);
        //     $imagedataPic = '0x' . strtoupper($imagedataPic['hex']);
        // }

        // '', //Remarks
        // "SPECIAL ORDER (S.O.) DOCUMENT", //AttachmentDescSO
        // $imagedataSO ?? null, //AttachmentSO
        // $mimetypeSO ?? null, //MimeTypeSO
        // "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION", //AttachmentDescCert
        // $imagedataCert ?? null, //AttachmentCert
        // $mimetypeCert ?? null, //MimeTypeCert
        // "COMPILED PHOTOS", //AttachmentDescPic
        // $imagedataPic ?? null, //AttachmentPic
        // $mimetypePic ?? null, //MimeTypePic

        $documentSO = $this->commonService->fileUploadHandlerForExternal($request, 'documentSO', "SPECIAL ORDER (S.O.) DOCUMENT");
        $documentCert = $this->commonService->fileUploadHandlerForExternal($request, 'documentCert', "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION");
        $documentPic = $this->commonService->fileUploadHandlerForExternal($request, 'documentPic', "COMPILED PHOTOS");

        $value = array(
            $id, //EmployeeTrainingProgramID
            $emp_code, //EmpCode
            $request->title, //TrainingProgram
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            Carbon::parse($request->to)->format('Y-m-d'), //IncDateTo
            $request->total_hours, //NumberOfHours
            $request->organizer, //Conductor
            $request->level, //LevelID
            $request->type, //TypeID
            $request->classification ?? 0, //ClassificationID
            $request->nature ?? 0, //NatureID
            $is_paid ?? '', //IsPaid
            $request->fund_source ?? 0, //SourceOfFundID
            $request->budget ?? '', //Budget
            'image/pdf/files', //Remarks

            $documentSO['description'], //AttachmentDescSO
            $documentSO['image'], //AttachmentSO
            $documentSO['mimetype'], //MimeTypeSO

            $documentCert['description'], //AttachmentDescCert
            $documentCert['image'], //AttachmentCert
            $documentCert['mimetype'], //MimeTypeCert

            $documentPic['description'], //AttachmentDescPic
            $documentPic['image'], //AttachmentPic
            $documentPic['mimetype'], //MimeTypePic

            $user->email //TransAccount
        );

        // dd($value);
        $db_ext->select(
            "
                DECLARE @NewEmployeeTrainingProgramID int;

                EXEC SaveEmployeeTrainingProgram
                    @EmployeeTrainingProgramID = ?,
                    @EmpCode = ?,
                    @TrainingProgram = ?,
                    @Venue = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @NumberOfHours = ?,
                    @Conductor = ?,
                    @LevelID = ?,
                    @TypeID = ?,
                    @ClassificationID = ?,
                    @NatureID = ?,
                    @IsPaid = ?,
                    @SourceOfFundID = ?,
                    @Budget = ?,
                    @Remarks = ?,
                    @AttachmentDescSO= ?,
                    @AttachmentSO = ?,
                    @MimeTypeSO= ?,
                    @AttachmentDescCert= ?,
                    @AttachmentCert = ?,
                    @MimeTypeCert= ?,
                    @AttachmentDescPic= ?,
                    @AttachmentPic = ?,
                    @MimeTypePic= ?,
                    @TransAccount = ?,
                    @NewEmployeeTrainingProgramID = @NewEmployeeTrainingProgramID OUTPUT;

                SELECT @NewEmployeeTrainingProgramID as NewEmployeeTrainingProgramID;

            ", $value
        );

        if(HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '5')->exists()){
            HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '5')->delete();
            HRIS::create([
                'hris_id' => $id,
                'hris_type' => '4',
                'college_id' => $college_id,
                'department_id' => $request->input('department_id'),
                'user_id' => auth()->id(),
                'report_quarter' => $currentQuarterYear->current_quarter,
                'report_year' => $currentQuarterYear->current_year,
            ]);
        }
        else{
            HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '4')->update([
                'college_id' => $college_id,
                'department_id' => $request->input('department_id'),
                'report_quarter' => $currentQuarterYear->current_quarter,
                'report_year' => $currentQuarterYear->current_year,
            ]);
        }

        LogActivity::addToLog('Had updated a Seminar/Webinar.');

        if($documentSO['isError'] == false && $documentCert['isError'] == false && $documentPic['isError'] == false){
            return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.development.index')->with('error', 
                $documentSO['message'] . " | " . 
                $documentCert['message'] . " | " . 
                $documentPic['message']
                // "Entry was saved but unable to upload some document/s, Please try reuploading the document/s!"
            );
        }
    }

    public function updateTraining(Request $request, $id){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        $is_paid = 'Y';
        if($request->fund_source == '0' && $request->budget == 0){
            $is_paid = 'N';
        }

        // try {
        //     if($request->has('documentSO')){
        //         $datastringSO = file_get_contents($request->file(['documentSO']));
        //         $mimetypeSO = $request->file('documentSO')->getMimeType();
        //         $imagedataSO = unpack("H*hex", $datastringSO);
        //         $imagedataSO = '0x' . strtoupper($imagedataSO['hex']);
        //     }
        //     if($request->has('documentCert')){
        //         $datastringCert = file_get_contents($request->file(['documentCert']));
        //         $mimetypeCert = $request->file('documentCert')->getMimeType();
        //         $imagedataCert = unpack("H*hex", $datastringCert);
        //         $imagedataCert = '0x' . strtoupper($imagedataCert['hex']);
        //     }
        //     if($request->has('documentPic')){
        //         $datastringPic = file_get_contents($request->file(['documentPic']));
        //         $mimetypePic = $request->file('documentPic')->getMimeType();
        //         $imagedataPic = unpack("H*hex", $datastringPic);
        //         $imagedataPic = '0x' . strtoupper($imagedataPic['hex']);
        //     }
        // } catch (Exception $th) {
        //     return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        // }

        // '', //Remarks
        // "SPECIAL ORDER (S.O.) DOCUMENT", //AttachmentDescSO
        // $imagedataSO ?? null, //AttachmentSO
        // $mimetypeSO ?? null, //MimeTypeSO
        // "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION", //AttachmentDescCert
        // $imagedataCert ?? null, //AttachmentCert
        // $mimetypeCert ?? null, //MimeTypeCert
        // "COMPILED PHOTOS", //AttachmentDescPic
        // $imagedataPic ?? null, //AttachmentPic
        // $mimetypePic ?? null, //MimeTypePic

        $documentSO = $this->commonService->fileUploadHandlerForExternal($request, 'documentSO', "SPECIAL ORDER (S.O.) DOCUMENT");
        $documentCert = $this->commonService->fileUploadHandlerForExternal($request, 'documentCert', "CERTIFICATE OF PARTICIPATION/ATTENDANCE/COMPLETION");
        $documentPic = $this->commonService->fileUploadHandlerForExternal($request, 'documentPic', "COMPILED PHOTOS");

        $value = array(
            $id, //EmployeeTrainingProgramID
            $emp_code, //EmpCode
            $request->title, //TrainingProgram
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //IncDateFrom
            Carbon::parse($request->to)->format('Y-m-d'), //IncDateTo
            $request->total_hours, //NumberOfHours
            $request->organizer, //Conductor
            $request->level, //LevelID
            $request->type, //TypeID
            $request->classification ?? 0, //ClassificationID
            $request->nature ?? 0, //NatureID
            $is_paid ?? '', //IsPaid
            $request->fund_source ?? 0, //SourceOfFundID
            $request->budget ?? '', //Budget
            'image/pdf/files', //Remarks

            $documentSO['description'], //AttachmentDescSO
            $documentSO['image'], //AttachmentSO
            $documentSO['mimetype'], //MimeTypeSO

            $documentCert['description'], //AttachmentDescCert
            $documentCert['image'], //AttachmentCert
            $documentCert['mimetype'], //MimeTypeCert

            $documentPic['description'], //AttachmentDescPic
            $documentPic['image'], //AttachmentPic
            $documentPic['mimetype'], //MimeTypePic

            $user->email //TransAccount
        );

        // dd($value);
        $db_ext->select(
            "
                DECLARE @NewEmployeeTrainingProgramID int;

                EXEC SaveEmployeeTrainingProgram
                    @EmployeeTrainingProgramID = ?,
                    @EmpCode = ?,
                    @TrainingProgram = ?,
                    @Venue = ?,
                    @IncDateFrom = ?,
                    @IncDateTo = ?,
                    @NumberOfHours = ?,
                    @Conductor = ?,
                    @LevelID = ?,
                    @TypeID = ?,
                    @ClassificationID = ?,
                    @NatureID = ?,
                    @IsPaid = ?,
                    @SourceOfFundID = ?,
                    @Budget = ?,
                    @Remarks = ?,
                    @AttachmentDescSO= ?,
                    @AttachmentSO = ?,
                    @MimeTypeSO= ?,
                    @AttachmentDescCert= ?,
                    @AttachmentCert = ?,
                    @MimeTypeCert= ?,
                    @AttachmentDescPic= ?,
                    @AttachmentPic = ?,
                    @MimeTypePic= ?,
                    @TransAccount = ?,
                    @NewEmployeeTrainingProgramID = @NewEmployeeTrainingProgramID OUTPUT;

                SELECT @NewEmployeeTrainingProgramID as NewEmployeeTrainingProgramID;

            ", $value
        );

        if(HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '4')->exists()){
            HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '4')->delete();
            HRIS::create([
                'hris_id' => $id,
                'hris_type' => '5',
                'college_id' => $college_id,
                'department_id' => $request->input('department_id'),
                'user_id' => auth()->id(),
                'report_quarter' => $currentQuarterYear->current_quarter,
                'report_year' => $currentQuarterYear->current_year,
            ]);
        }
        else{
            HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '5')->update([
                'college_id' => $college_id,
                'department_id' => $request->input('department_id'),
                'report_quarter' => $currentQuarterYear->current_quarter,
                'report_year' => $currentQuarterYear->current_year,
            ]);
        }

        LogActivity::addToLog('Had saved a Training.');

        if($documentSO['isError'] == false && $documentCert['isError'] == false && $documentPic['isError'] == false){
            return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
        } else {
            return redirect()->route('submissions.development.index')->with('error', 
                $documentSO['message'] . " | " . 
                $documentCert['message'] . " | " . 
                $documentPic['message']
                // "Entry was saved but unable to upload some document/s, Please try reuploading the document/s!"
            );
        }
    }

    public function delete($id){

        $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->pluck('hris_id')->first();
        if(is_null($developmentID))
            $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->pluck('hris_id')->first();

        if(LockController::isLocked($developmentID, 25)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }
        if(LockController::isLocked($developmentID, 26)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');

        $db_ext->statement(
            "
                EXEC DeleteEmployeeTrainingProgram
                    @EmployeeTrainingProgramID = ?,
                    @EmpCode = ?;
            ", array($id, $user->emp_code)
        );

        if(!is_null($developmentID)){
            HRIS::where('id', $developmentID)->delete();
        }


        LogActivity::addToLog('Had deleted a Seminar/Webinar or Training.');

        return redirect()->route('submissions.development.index')->with('success','The accomplishment has been deleted.');
    }

    public function check($id){
        $development = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->first();
        if(is_null($development))
            $development = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->first();

        if(LockController::isLocked($development->hris_id, 25))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        if(LockController::isLocked($development->hris_id, 26))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        if($development->hris_type == '4'){
            if($this->submitSeminar($development->id))
                return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');
            else
                return redirect()->back()->with('error', 'Something went wrong.');
        }
        elseif($development->hris_type == '5'){
            if($this->submitTraining($development->id))
                return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');
            else
                return redirect()->back()->with('error', 'Something went wrong.');
        }
        return redirect()->back()->with('cannot_access', 'Failed to submit the accomplishment.');
    }

    public function submitSeminar($development_id){
        $user = User::find(auth()->id());
        $development = HRIS::where('id', $development_id)->first();
        $employee = Employee::where('user_id', auth()->id())->where('college_id', $development->college_id)->get();

        $db_ext = DB::connection('mysql_external');

        $seminars = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $seminar = new stdClass();

        foreach($seminars as $row){
            if($row->EmployeeTrainingProgramID == $development->hris_id){
                $seminar = $row;
                break;
            }
        }

        $sector_id = College::where('id', $development->college_id)->pluck('sector_id')->first();
        $department_name = Department::where('id', $development->department_id)->pluck('name')->first();
        $college_name = College::where('id', $development->college_id)->pluck('name')->first();

        $filenames = [];
        $filenames = (new SavePersonalDataDocumentService())->saveFilesFromPersonnelPortal($seminar, 4, $development_id);

        $description = [];
        array_push($description, $seminar->DescriptionSO);
        array_push($description, $seminar->DescriptionCert);
        array_push($description, $seminar->DescriptionPic);

        $values = [
            'title' => $seminar->TrainingProgram,
            'classification' => $seminar->Classification,
            'nature' => $seminar->Nature,
            'budget' => $seminar->Budget,
            'fund_source' => $seminar->SourceOfFund,
            'organizer' => $seminar->Conductor,
            'type' => $seminar->Type,
            'level' => $seminar->Level,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours,
            'description' => implode(", ", array_filter($description)),
            'department_id' => $department_name,
            'college_id' => $college_name,
        ];

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $development->hris_id)
            ->where('report_category_id', 25)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();
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

        if ($type == 'a') {
            if ($development->department_id == $development->college_id) {
                Report::create([
                    'user_id' =>  auth()->id(),
                    'sector_id' => $sector_id,
                    'college_id' => $development->college_id,
                    'department_id' => $development->department_id,
                    'format' => $type,
                    'report_category_id' => 25,
                    'report_code' => null,
                    'report_reference_id' => $development->hris_id,
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
                    'college_id' => $development->college_id,
                    'department_id' => $development->department_id,
                    'format' => $type,
                    'report_category_id' => 25,
                    'report_code' => null,
                    'report_reference_id' => $development->hris_id,
                    'report_details' => json_encode($values),
                    'report_documents' => json_encode($filenames),
                    'report_date' => date("Y-m-d", time()),
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
            }
        } elseif ($type == 'f') {
            if ($development->department_id == $development->college_id) {
                if ($development->department_id >= 227 && $development->department_id <= 248) { // If branch
                    Report::create([
                        'user_id' =>  auth()->id(),
                        'sector_id' => $sector_id,
                        'college_id' => $development->college_id,
                        'department_id' => $development->department_id,
                        'format' => $type,
                        'report_category_id' => 25,
                        'report_code' => null,
                        'report_reference_id' => $development->hris_id,
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
                        'college_id' => $development->college_id,
                        'department_id' => $development->department_id,
                        'format' => $type,
                        'report_category_id' => 25,
                        'report_code' => null,
                        'report_reference_id' => $development->hris_id,
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
                    'college_id' => $development->college_id,
                    'department_id' => $development->department_id,
                    'format' => $type,
                    'report_category_id' => 25,
                    'report_code' => null,
                    'report_reference_id' => $development->hris_id,
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

    public function submitTraining($development_id){
        $user = User::find(auth()->id());
        $development = HRIS::where('id', $development_id)->first();
        $employee = Employee::where('user_id', auth()->id())->where('college_id', $development->college_id)->get();

        $db_ext = DB::connection('mysql_external');

        $trainings = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $training = new stdClass();;

        foreach($trainings as $row){
            if($row->EmployeeTrainingProgramID == $development->hris_id){
                $training = $row;
                break;
            }
        }

        $sector_id = College::where('id', $development->college_id)->pluck('sector_id')->first();
        $department_name = Department::where('id', $development->department_id)->pluck('name')->first();
        $college_name = College::where('id', $development->college_id)->pluck('name')->first();

        $filenames = [];
        $filenames = (new SavePersonalDataDocumentService())->saveFilesFromPersonnelPortal(
            $training, 4, $development_id);

        $description = [];
        array_push($description, $training->DescriptionSO);
        array_push($description, $training->DescriptionCert);
        array_push($description, $training->DescriptionPic);

        $values = [
            'title' => $training->TrainingProgram,
            'classification' => $training->Classification,
            'nature' => $training->Nature,
            'budget' => $training->Budget,
            'type' => $training->Type,
            'fund_source' => $training->SourceOfFund,
            'organizer' => $training->Conductor,
            'level' => $training->Level,
            'venue' => $training->Venue,
            'from' => date('m/d/Y', strtotime($training->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($training->IncDateTo)),
            'total_hours' => $training->NumberOfHours,
            'description' => implode(", ", array_filter($description)),
            'department_id' => $department_name,
            'college_id' => $college_name,
        ];

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $development->hris_id)
            ->where('report_category_id', 26)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();
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
        if ($type == 'a') {
            if ($development->department_id == $development->college_id) {
                Report::create([
                    'user_id' =>  auth()->id(),
                    'sector_id' => $sector_id,
                    'college_id' => $development->college_id,
                    'department_id' => $development->department_id,
                    'format' => $type,
                    'report_category_id' => 26,
                    'report_code' => null,
                    'report_reference_id' => $development->hris_id,
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
                    'college_id' => $development->college_id,
                    'department_id' => $development->department_id,
                    'format' => $type,
                    'report_category_id' => 26,
                    'report_code' => null,
                    'report_reference_id' => $development->hris_id,
                    'report_details' => json_encode($values),
                    'report_documents' => json_encode($filenames),
                    'report_date' => date("Y-m-d", time()),
                    'report_quarter' => $currentQuarterYear->current_quarter,
                    'report_year' => $currentQuarterYear->current_year,
                ]);
            }
        } elseif ($type == 'f') {
            if ($development->department_id == $development->college_id) {
                if ($development->department_id >= 227 && $development->department_id <= 248) { // If branch
                    Report::create([
                        'user_id' =>  auth()->id(),
                        'sector_id' => $sector_id,
                        'college_id' => $development->college_id,
                        'department_id' => $development->department_id,
                        'format' => $type,
                        'report_category_id' => 26,
                        'report_code' => null,
                        'report_reference_id' => $development->hris_id,
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
                        'college_id' => $development->college_id,
                        'department_id' => $development->department_id,
                        'format' => $type,
                        'report_category_id' => 26,
                        'report_code' => null,
                        'report_reference_id' => $development->hris_id,
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
                    'college_id' => $development->college_id,
                    'department_id' => $development->department_id,
                    'format' => $type,
                    'report_category_id' => 26,
                    'report_code' => null,
                    'report_reference_id' => $development->hris_id,
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

    // public function saveSeminar(Request $request, $id){
    //     if($request->document[0] == null){
    //         return redirect()->back()->with('error', 'Document upload are required');
    //     }


    //     $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();
    //     $sector_id = College::where('id', $college_id)->pluck('sector_id')->first();

    //     $seminarFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
    //         ->where('h_r_i_s_fields.h_r_i_s_form_id', 4)->where('h_r_i_s_fields.is_active', 1)
    //         ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
    //         ->orderBy('h_r_i_s_fields.order')->get();

    //     $data = [];

    //     foreach($seminarFields as $field){
    //         if($field->field_type_id == '5'){
    //             $data[$field->name] = DropdownOption::where('id', $request->input($field->name))->pluck('name')->first();
    //         }
    //         elseif($field->field_type_id == '3'){
    //             $currency_name = Currency::where('id', $request->input('currency_'.$field->name))->pluck('code')->first();
    //             $data[$field->name] = $currency_name.' '.$request->input($field->name);
    //         }
    //         elseif($field->field_type_id == '10'){
    //             continue;
    //         }
    //         elseif($field->field_type_id == '12'){
    //             $data[$field->name] = College::where('id', $request->input($field->name))->pluck('name')->first();
    //         }
    //         elseif($field->field_type_id == '13'){
    //             $data[$field->name] = Department::where('id', $request->input($field->name))->pluck('name')->first();
    //         }
    //         else{
    //             if($request->input($field->name) == 'null' || $request->input($field->name) == null)
    //                 $data[$field->name] = '';
    //             else
    //                 $data[$field->name] = $request->input($field->name);
    //         }
    //     }

    //     $filenames = [];

    //     if($request->has('document')){

    //         $documents = $request->input('document');
    //         foreach($documents as $document){
    //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
    //             if($temporaryFile){
    //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
    //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
    //                 $ext = $info['extension'];
    //                 $fileName = 'HRIS-ADP-'.now()->timestamp.uniqid().'.'.$ext;
    //                 $newPath = "documents/".$fileName;
    //                 Storage::move($temporaryPath, $newPath);
    //                 Storage::deleteDirectory("documents/tmp/".$document);
    //                 $temporaryFile->delete();

    //                 HRISDocument::create([
    //                     'hris_form_id' => 4,
    //                     'reference_id' => $id,
    //                     'filename' => $fileName,
    //                 ]);
    //                 array_push($filenames, $fileName);
    //             }
    //         }
    //     }

    //     $currentQuarterYear = Quarter::find(1);

    //     Report::where('report_reference_id', $id)
    //         ->where('report_category_id', 25)
    //         ->where('user_id', auth()->id())
    //         ->where('report_quarter', $currentQuarterYear->current_quarter)
    //         ->where('report_year', $currentQuarterYear->current_year)
    //         ->delete();

    //     Report::create([
    //         'user_id' =>  auth()->id(),
    //         'sector_id' => $sector_id,
    //         'college_id' => $college_id,
    //         'department_id' => $request->department_id,
    //         'report_category_id' => 25,
    //         'report_code' => null,
    //         'report_reference_id' => $id,
    //         'report_details' => json_encode($data),
    //         'report_documents' => json_encode($filenames),
    //         'report_date' => date("Y-m-d", time()),
    //         'report_quarter' => $currentQuarterYear->current_quarter,
    //         'report_year' => $currentQuarterYear->current_year,
    //     ]);

    //     \LogActivity::addToLog('Had submitted a Seminar/Webinar.');

    //     return redirect()->route('submissions.development.index')->with('success','The accomplishment has been submitted.');
    // }

    // public function addTraining($id){
    //     $user = User::find(auth()->id());

    //     $currentQuarterYear = Quarter::find(1);

    //     if(LockController::isLocked($id, 25)){
    //         return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
    //     }
    //     if(LockController::isLocked($id, 26)){
    //         return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
    //     }
    //     if(Report::where('report_reference_id', $id)
    //         ->where('report_quarter', $currentQuarterYear->current_quarter)
    //         ->where('report_year', $currentQuarterYear->current_year)
    //         ->where('report_category_id', 25)->exists()
    //         ){
    //         return redirect()->back()->with('error', 'The "Seminar" accomplishment report has already been submitted.');
    //     }

    //     $db_ext = DB::connection('mysql_external');

    //     $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

    //     $training;

    //     $trainingFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
    //             ->where('h_r_i_s_fields.h_r_i_s_form_id', 5)->where('h_r_i_s_fields.is_active', 1)
    //             ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
    //             ->orderBy('h_r_i_s_fields.order')->get();

    //     foreach($developments as $development){
    //         if($development->EmployeeTrainingProgramID == $id){
    //             $training = $development;
    //             break;
    //         }
    //     }

    //     $values = [
    //         'title' => $training->TrainingProgram,
    //         'classification' => $training->Classification,
    //         'nature' => $training->Nature,
    //         'budget' => $training->Budget,
    //         'fund_source' => $training->SourceOfFund,
    //         'organizer' => $training->Conductor,
    //         'level' => $training->Level,
    //         'venue' => $training->Venue,
    //         'from' => date('m/d/Y', strtotime($training->IncDateFrom)),
    //         'to' => date('m/d/Y', strtotime($training->IncDateTo)),
    //         'total_hours' => $training->NumberOfHours,
    //         'document' => $seminar->Attachment,
    //         'description' => $seminar->Description,
    //     ];

    //     // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
    //     $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

    //     $departments = Department::whereIn('college_id', $colleges)->get();

    //     //HRIS Document
    //     $hrisDocuments = [];
    //     $collegeOfDepartment = '';
    //     if(LockController::isNotLocked($id, 26) && Report::where('report_reference_id', $id)
    //         ->where('report_quarter', $currentQuarterYear->current_quarter)
    //         ->where('report_year', $currentQuarterYear->current_year)
    //         ->where('report_category_id', 26)->exists()){

    //         $hrisDocuments = HRISDocument::where('hris_form_id', 5)->where('reference_id', $id)->get()->toArray();
    //         $report = Report::where('report_reference_id', $id)->where('report_category_id', 26)->first();
    //         $report_details = json_decode($report->report_details, true);
    //         $description;

    //         foreach($trainingFields as $row){
    //             if($row->name == 'description')
    //                 $description = $report_details[$row->name];
    //         }

    //         if ($report->department_id != null) {
    //             $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$report->department_id.")");
    //         }
    //         else {
    //             $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
    //         }

    //         $values = [
    //             'title' => $training->TrainingProgram,
    //             'classification' => $training->Classification,
    //             'nature' => $training->Nature,
    //             'budget' => $training->Budget,
    //             'fund_source' => $training->SourceOfFund,
    //             'organizer' => $training->Conductor,
    //             'level' => $training->Level,
    //             'venue' => $training->Venue,
    //             'from' => date('m/d/Y', strtotime($training->IncDateFrom)),
    //             'to' => date('m/d/Y', strtotime($training->IncDateTo)),
    //             'total_hours' => $training->NumberOfHours,
    //             'document' => $seminar->Attachment,
    //             'description' => $seminar->Description,
    //         ];
    //     }

    //     return view('submissions.hris.development.training.add', compact('id', 'training', 'trainingFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments'));
    // }

    // public function saveTraining(Request $request, $id){

    //     if($request->document[0] == null){
    //         return redirect()->back()->with('error', 'Document upload are required');
    //     }

    //     $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();
    //     $sector_id = College::where('id', $college_id)->pluck('sector_id')->first();

    //     $trainingFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
    //         ->where('h_r_i_s_fields.h_r_i_s_form_id', 5)->where('h_r_i_s_fields.is_active', 1)
    //         ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
    //         ->orderBy('h_r_i_s_fields.order')->get();

    //     $data = [];

    //     foreach($trainingFields as $field){
    //         if($field->field_type_id == '5'){
    //             $data[$field->name] = DropdownOption::where('id', $request->input($field->name))->pluck('name')->first();
    //         }
    //         elseif($field->field_type_id == '3'){
    //             $currency_name = Currency::where('id', $request->input('currency_'.$field->name))->pluck('code')->first();
    //             $data[$field->name] = $currency_name.' '.$request->input($field->name);
    //         }
    //         elseif($field->field_type_id == '10'){
    //             continue;
    //         }
    //         elseif($field->field_type_id == '12'){
    //             $data[$field->name] = College::where('id', $request->input($field->name))->pluck('name')->first();
    //         }
    //         elseif($field->field_type_id == '13'){
    //             $data[$field->name] = Department::where('id', $request->input($field->name))->pluck('name')->first();
    //         }
    //         else{
    //             if($request->input($field->name) == 'null' || $request->input($field->name) == null)
    //                 $data[$field->name] = '';
    //             else
    //                 $data[$field->name] = $request->input($field->name);
    //         }
    //     }

    //     $filenames = [];
    //     if($request->has('document')){

    //         $documents = $request->input('document');
    //         foreach($documents as $document){
    //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
    //             if($temporaryFile){
    //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
    //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
    //                 $ext = $info['extension'];
    //                 $fileName = 'HRIS-AT-'.now()->timestamp.uniqid().'.'.$ext;
    //                 $newPath = "documents/".$fileName;
    //                 Storage::move($temporaryPath, $newPath);
    //                 Storage::deleteDirectory("documents/tmp/".$document);
    //                 $temporaryFile->delete();

    //                 HRISDocument::create([
    //                     'hris_form_id' => 5,
    //                     'reference_id' => $id,
    //                     'filename' => $fileName,
    //                 ]);
    //                 array_push($filenames, $fileName);
    //             }
    //         }
    //     }

    //     $currentQuarterYear = Quarter::find(1);

    //     Report::where('report_reference_id', $id)
    //         ->where('report_category_id', 26)
    //         ->where('user_id', auth()->id())
    //         ->where('report_quarter', $currentQuarterYear->current_quarter)
    //         ->where('report_year', $currentQuarterYear->current_year)
    //         ->delete();

    //     Report::create([
    //         'user_id' =>  auth()->id(),
    //         'sector_id' => $sector_id,
    //         'college_id' => $college_id,
    //         'department_id' => $request->department_id,
    //         'report_category_id' => 26,
    //         'report_code' => null,
    //         'report_reference_id' => $id,
    //         'report_details' => json_encode($data),
    //         'report_documents' => json_encode(collect($filenames)),
    //         'report_date' => date("Y-m-d", time()),
    //         'report_quarter' => $currentQuarterYear->current_quarter,
    //         'report_year' => $currentQuarterYear->current_year,
    //     ]);

    //     \LogActivity::addToLog('Had submitted a Training.');

    //     return redirect()->route('submissions.development.index')->with('success','The accomplishment has been submitted.');
    // }
}
