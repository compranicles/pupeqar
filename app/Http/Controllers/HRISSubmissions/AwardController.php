<?php

namespace App\Http\Controllers\HRISSubmissions;

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
use App\Models\Maintenance\Currency;
use App\Models\Maintenance\HRISField;
use App\Models\Maintenance\Department;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\Maintenances\LockController;

class AwardController extends Controller
{
    public function index(){

        $currentQuarterYear = Quarter::find(1);

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $awardFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCode N'$user->emp_code'");
        $savedReports = HRIS::where('hris_type', '2')->where('user_id', $user->id)->pluck('hris_id')->all();

        $submissionStatus = [];
        foreach ($awardFinal as $award) {
            $id = HRIS::where('hris_id', $award->EmployeeOutstandingAchievementID)->where('hris_type', 2)->where('user_id', $user->id)->pluck('id')->first();
            if($id != ''){
                if (LockController::isLocked($id, 27))
                    $submissionStatus[27][$award->EmployeeOutstandingAchievementID] = 1;
                else
                    $submissionStatus[27][$award->EmployeeOutstandingAchievementID] = 0;
                if ($award->Attachment == null)
                    $submissionStatus[27][$award->EmployeeOutstandingAchievementID] = 2;
            }
        }
        return view('submissions.hris.award.index', compact('awardFinal', 'savedReports', 'currentQuarterYear', 'submissionStatus'));
    }

    public function create(){
        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');

        $fields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 2)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();

        if(session()->get('user_type') == 'Faculty Employee') 
            $colleges = Employee::where('type', 'F')->where('user_id', auth()->id())->pluck('college_id')->all();
        else if(session()->get('user_type') == 'Admin Employee') 
            $colleges = Employee::where('type', 'A')->where('user_id', auth()->id())->pluck('college_id')->all();

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
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetAchievementClassification");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->AchievementClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;

        return view('submissions.hris.award.create', compact('values', 'fields', 'dropdown_options', 'departments'));
    }

    public function savetohris(Request $request){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        if($request->has('document')){
            $datastring = file_get_contents($request->file('document'));
            $imagedata = unpack("H*hex", $datastring);
            $imagedata = '0x' . strtoupper($imagedata['hex']);
        }

        $value = [
            0, //EmployeeOutstandingAchievementID
            $emp_code, //EmpCode
            $request->award, //Achievement
            $request->awarded_by, //AwardedBy
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //Date
            $request->level, //LevelID
            $request->classification, //ClassificationID
            '', //Remarks
            $request->description, //AttachmentDescription
            $imagedata ?? null, //Attachment
            $user->email
        ];

        $id = $db_ext->select(
            "
                DECLARE @NewEmployeeOutstandingAchievementID int;
                EXEC SaveEmployeeOutstandingAchievement
                    @EmployeeOutstandingAchievementID = ?,
                    @EmpCode = ?,
                    @Achievement = ?,
                    @AwardedBy = ?,
                    @Venue = ?,
                    @Date = ?,
                    @LevelID = ?,
                    @ClassificationID = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @TransAccount = ?,
                    @NewEmployeeOutstandingAchievementID = @NewEmployeeOutstandingAchievementID OUTPUT;

                SELECT @NewEmployeeOutstandingAchievementID as NewEmployeeOutstandingAchievementID;

            ", $value);

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id[0]->NewEmployeeOutstandingAchievementID,
            'hris_type' => '2',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had saved an Outstanding Achievement.');

        return redirect()->route('submissions.award.index')->with('success','The accomplishment has been saved.');
    }

    public function add(Request $request, $id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $awardData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCodeAndID N'$user->emp_code',$id");

        $awardFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 2)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();


        $values = [
            'award' =>  $awardData[0]->Achievement,
            'classification' => $awardData[0]->AchievementClassificationID,
            'awarded_by' => $awardData[0]->AwardedBy,
            'level' => $awardData[0]->LevelID,
            'venue' => $awardData[0]->Venue,
            'from' => date('m/d/Y', strtotime($awardData[0]->Date)),
            'to' => date('m/d/Y', strtotime($awardData[0]->Date)),
            'document' => $awardData[0]->Attachment,
            'description' => $awardData[0]->Description,
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
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetAchievementClassification");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->AchievementClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;

        if(session()->get('user_type') == 'Faculty Employee') 
            $colleges = Employee::where('type', 'F')->where('user_id', auth()->id())->pluck('college_id')->all();
        else if(session()->get('user_type') == 'Admin Employee') 
            $colleges = Employee::where('type', 'A')->where('user_id', auth()->id())->pluck('college_id')->all();

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
                'document' => $awardData[0]->Attachment,
                'description' => $awardData[0]->Description,
            ];
         }

        return view('submissions.hris.award.add', compact('id', 'awardData', 'awardFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments', 'dropdown_options'));
    }

    public function store(Request $request, $id){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        if($request->has('document')){
            $datastring = file_get_contents($request->file('document'));
            $imagedata = unpack("H*hex", $datastring);
            $imagedata = '0x' . strtoupper($imagedata['hex']);
        }

        $value = [
            $id, //EmployeeOutstandingAchievementID
            $emp_code, //EmpCode
            $request->award, //Achievement
            $request->awarded_by, //AwardedBy
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //Date
            $request->level, //LevelID
            $request->classification, //ClassificationID
            '', //Remarks
            $request->description, //AttachmentDescription
            $imagedata ?? null, //Attachment
            $user->email
        ];

        $newid = $db_ext->select(
            "
                DECLARE @NewEmployeeOutstandingAchievementID int;
                EXEC SaveEmployeeOutstandingAchievement
                    @EmployeeOutstandingAchievementID = ?,
                    @EmpCode = ?,
                    @Achievement = ?,
                    @AwardedBy = ?,
                    @Venue = ?,
                    @Date = ?,
                    @LevelID = ?,
                    @ClassificationID = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @TransAccount = ?,
                    @NewEmployeeOutstandingAchievementID = @NewEmployeeOutstandingAchievementID OUTPUT;

                SELECT @NewEmployeeOutstandingAchievementID as NewEmployeeOutstandingAchievementID;

            ", $value);

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id,
            'hris_type' => '2',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had saved an Outstanding Achievement.');

        return redirect()->route('submissions.award.index')->with('success','The accomplishment has been saved.');
    }

    public function show($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $awardData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCodeAndID N'$user->emp_code',$id");

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '2')->pluck('department_id')->first();

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
            'document' => $awardData[0]->Attachment,
            'description' => $awardData[0]->Description,
            'department_id' => Department::where('id', $department_id)->pluck('name')->first(),
            'college_id' => College::where('id', Department::where('id', $department_id)->pluck('college_id')->first())->pluck('name')->first(),
        ];

        if(session()->get('user_type') == 'Faculty Employee') 
            $colleges = Employee::where('type', 'F')->where('user_id', auth()->id())->pluck('college_id')->all();
        else if(session()->get('user_type') == 'Admin Employee') 
            $colleges = Employee::where('type', 'A')->where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $forview = '';
        return view('submissions.hris.award.add', compact('id', 'awardData', 'awardFields', 'values', 'colleges', 'departments', 'forview'));
    }

    public function edit($id){
        $user = User::find(auth()->id());

        $awardID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '2')->pluck('id')->first();

        if(LockController::isLocked($awardID, 27)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $awardData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCodeAndID N'$user->emp_code',$id");

        $department_id = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '2')->pluck('department_id')->first();

        $awardFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 2)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();


        $values = [
            'award' =>  $awardData[0]->Achievement,
            'classification' => $awardData[0]->AchievementClassificationID,
            'awarded_by' => $awardData[0]->AwardedBy,
            'level' => $awardData[0]->LevelID,
            'venue' => $awardData[0]->Venue,
            'from' => date('m/d/Y', strtotime($awardData[0]->Date)),
            'to' => date('m/d/Y', strtotime($awardData[0]->Date)),
            'document' => $awardData[0]->Attachment,
            'description' => $awardData[0]->Description,
            'department_id' => $department_id
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
        $hrisclassifications = $db_ext->select("SET NOCOUNT ON; EXEC GetAchievementClassification");
        $classifications = [];
        foreach($hrisclassifications as $row){
            $classifications[] = (object)[
                'id' => $row->AchievementClassificationID,
                'name' => $row->Classification,
            ];
        }
        $classifications = collect($classifications);
        $dropdown_options['classification'] = $classifications;

        if(session()->get('user_type') == 'Faculty Employee') 
            $colleges = Employee::where('type', 'F')->where('user_id', auth()->id())->pluck('college_id')->all();
        else if(session()->get('user_type') == 'Admin Employee') 
            $colleges = Employee::where('type', 'A')->where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $forview = '';
        return view('submissions.hris.award.edit', compact('id', 'awardData', 'awardFields', 'values', 'colleges', 'departments', 'dropdown_options'));
    }

    public function update(Request $request, $id){
        $user = User::find(auth()->id());
        $emp_code = $user->emp_code;

        $db_ext = DB::connection('mysql_external');
        $currentQuarterYear = Quarter::find(1);

        if($request->has('document')){
            $datastring = file_get_contents($request->file('document'));
            $imagedata = unpack("H*hex", $datastring);
            $imagedata = '0x' . strtoupper($imagedata['hex']);
        }

        $value = [
            $id, //EmployeeOutstandingAchievementID
            $emp_code, //EmpCode
            $request->award, //Achievement
            $request->awarded_by, //AwardedBy
            $request->venue, //Venue
            Carbon::parse($request->from)->format('Y-m-d'), //Date
            $request->level, //LevelID
            $request->classification, //ClassificationID
            '', //Remarks
            $request->description, //AttachmentDescription
            $imagedata ?? null, //Attachment
            $user->email
        ];

        $newid = $db_ext->select(
            "
                DECLARE @NewEmployeeOutstandingAchievementID int;
                EXEC SaveEmployeeOutstandingAchievement
                    @EmployeeOutstandingAchievementID = ?,
                    @EmpCode = ?,
                    @Achievement = ?,
                    @AwardedBy = ?,
                    @Venue = ?,
                    @Date = ?,
                    @LevelID = ?,
                    @ClassificationID = ?,
                    @Remarks = ?,
                    @AttachmentDescription = ?,
                    @Attachment = ?,
                    @TransAccount = ?,
                    @NewEmployeeOutstandingAchievementID = @NewEmployeeOutstandingAchievementID OUTPUT;

                SELECT @NewEmployeeOutstandingAchievementID as NewEmployeeOutstandingAchievementID;

            ", $value);

        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::where('user_id', auth()->id())->where('hris_id', $id)->where('hris_type', '2')->update([
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
        ]);

        \LogActivity::addToLog('Had updated a Outstanding Achievement.');

        return redirect()->route('submissions.award.index')->with('success','The accomplishment has been updated.');
    }

    public function delete($id){
        $awardID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '2')->pluck('id')->first();

        if(LockController::isLocked($awardID, 27)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        $user = User::find(auth()->id());
        $db_ext = DB::connection('mysql_external');

        $db_ext->statement(
            "
                EXEC DeleteEmployeeOutstandingAchievement
                    @EmployeeOutstandingAchievementID = ?,
                    @EmpCode = ?;
            ", array($id, $user->emp_code)
        );

        if(!is_null($awardID)){
            HRIS::where('id', $awardID)->delete();
        }

        \LogActivity::addToLog('Had deleted a Outstanding Achievement.');

        return redirect()->route('submissions.award.index')->with('success','The accomplishment has been deleted.');
    }

    public function check($id){
        $award = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '2')->first();

        if(LockController::isLocked($award->id, 27))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        if($this->submit($award->id))
            return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');

        return redirect()->back()->with('cannot_access', 'Failed to submit the accomplishment.');
    }

    public function submit($award_id){
        $user = User::find(auth()->id());
        $award = HRIS::where('id', $award_id)->first();

        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $awardData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCodeAndID N'$user->emp_code',$award->hris_id");

        $sector_id = College::where('id', $award->college_id)->pluck('sector_id')->first();
        $department_name = Department::where('id', $award->department_id)->pluck('name')->first();
        $college_name = College::where('id', $award->college_id)->pluck('name')->first();

        $filenames = [];
        $img = Image::make($awardData[0]->Attachment);
        $fileName = 'HRIS-OAA-'.now()->timestamp.uniqid().'.jpeg';
        $newPath = storage_path().'/app/documents/'.$fileName;
        $img->save($newPath);

        HRISDocument::create([
            'hris_form_id' => 2,
            'reference_id' => $award_id,
            'filename' => $fileName,
        ]);
        array_push($filenames, $fileName);

        $values = [
            'award' =>  $awardData[0]->Achievement,
            'classification' => $awardData[0]->Classification,
            'awarded_by' => $awardData[0]->AwardedBy,
            'level' => $awardData[0]->Level,
            'venue' => $awardData[0]->Venue,
            'from' => date('m/d/Y', strtotime($awardData[0]->Date)),
            'to' => date('m/d/Y', strtotime($awardData[0]->Date)),
            // 'document' => $awardData[0]->Attachment,
            'description' => $awardData[0]->Description,
            'department_id' => $department_name,
            'college_id' => $college_name,
        ];

        $currentQuarterYear = Quarter::find(1);

        $getUserTypeFromSession = session()->get('user_type');
        $format_type = '';
        if($getUserTypeFromSession == 'Faculty Employee')
            $format_type = 'f';
        elseif($getUserTypeFromSession == 'Admin Employee')
            $format_type = 'a';

        Report::where('report_reference_id', $award_id)
            ->where('report_category_id', 27)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $award->college_id,
            'department_id' => $award->department_id,
            'format' => $format_type,
            'report_category_id' => 27,
            'report_code' => null,
            'report_reference_id' => $award_id,
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
