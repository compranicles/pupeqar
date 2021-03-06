<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    StorageFileController,
};
use Illuminate\Http\Request;
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
    HRIS,
    FormBuilder\DropdownOption,
    Maintenance\College,
    Maintenance\Currency,
    Maintenance\Department,
    Maintenance\HRISField,
    Maintenance\Quarter,
};
use Image;

class SeminarAndTrainingController extends Controller
{
    public function index(){
        $currentQuarterYear = Quarter::find(1);

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $developmentFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        // $seminarReports = Report::where('report_category_id', 25)->where('user_id', $user->id)->select('report_reference_id', 'report_quarter', 'report_year')->get();
        // $trainingReports = Report::where('report_category_id', 26)->where('user_id', $user->id)->select('report_reference_id', 'report_quarter', 'report_year')->get();

        $savedSeminars = HRIS::where('hris_type', '4')->where('user_id', $user->id)->pluck('hris_id')->all();
        $savedTrainings = HRIS::where('hris_type', '5')->where('user_id', $user->id)->pluck('hris_id')->all();

        $submissionStatus = [];
        foreach ($developmentFinal as $development) {
            $id = HRIS::where('hris_id', $development->EmployeeTrainingProgramID)->where('hris_type', 4)->where('user_id', $user->id)->pluck('id')->first();
            if($id != ''){
                if (LockController::isLocked($id, 25))
                    $submissionStatus[25][$development->EmployeeTrainingProgramID] = 1;
                else
                    $submissionStatus[25][$development->EmployeeTrainingProgramID] = 0;
                if ($development->Attachment == null)
                    $submissionStatus[25][$development->EmployeeTrainingProgramID] = 2;
            }

            $id = HRIS::where('hris_id', $development->EmployeeTrainingProgramID)->where('hris_type', 5)->where('user_id', $user->id)->pluck('id')->first();
            if($id != ''){
                if (LockController::isLocked($id, 26))
                    $submissionStatus[26][$development->EmployeeTrainingProgramID] = 1;
                else
                    $submissionStatus[26][$development->EmployeeTrainingProgramID] = 0;
                if ($development->Attachment == null)
                    $submissionStatus[26][$development->EmployeeTrainingProgramID] = 2;
            }
        }
        // dd($submissionStatus);
        return view('submissions.hris.development.index', compact('developmentFinal', 'savedSeminars', 'savedTrainings', 'currentQuarterYear', 'submissionStatus'));
    }

    public function add($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

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
            'total_hours' => $seminar->NumberOfHours,
            'document' => $seminar->Attachment,
            'description' => $seminar->Description ?? 'No Document Attached',
            'id' => $seminar->EmployeeTrainingProgramID,
        ];

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

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
                'document' => $seminar->Attachment,
                'description' => $seminar->Description ?? 'No Document Attached',
                'id' => $seminar->EmployeeTrainingProgramID,
            ];
        }

        // dd($seminar);

        if($seminar->ClassificationID >= 5){
            $training = $seminar;
            $trainingFields = $seminarFields;
            return view('submissions.hris.development.training.add', compact('id', 'training', 'trainingFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments'));
        }

        return view('submissions.hris.development.seminar.add', compact('id', 'seminar', 'seminarFields', 'values', 'colleges', 'collegeOfDepartment', 'hrisDocuments', 'departments'));
    }

    public function storeSeminar(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id,
            'hris_type' => '4',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had saved a Seminar/Webinar.');

        return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
    }

    public function storeTraining(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

        HRIS::create([
            'hris_id' => $id,
            'hris_type' => '5',
            'college_id' => $college_id,
            'department_id' => $request->input('department_id'),
            'user_id' => auth()->id(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        \LogActivity::addToLog('Had saved a Training.');

        return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
    }

    public function show($id){
        $user = User::find(auth()->id());

        $currentQuarterYear = Quarter::find(1);

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");
        $seminar;
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
            'fund_source' => $seminar->SourceOfFund,
            'organizer' => $seminar->Conductor,
            'level' => $seminar->Level,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours,
            'document' => $seminar->Attachment,
            'description' => $seminar->Description ?? 'No Document Attached',
            'id' => $seminar->EmployeeTrainingProgramID,
            'department_id' => $department_id,
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

        $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->pluck('id')->first();
        if(is_null($developmentID))
            $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->pluck('id')->first();

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
        $seminar;
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
            'fund_source' => $seminar->SourceOfFund,
            'organizer' => $seminar->Conductor,
            'level' => $seminar->Level,
            'venue' => $seminar->Venue,
            'from' => date('m/d/Y', strtotime($seminar->IncDateFrom)),
            'to' => date('m/d/Y', strtotime($seminar->IncDateTo)),
            'total_hours' => $seminar->NumberOfHours,
            'document' => $seminar->Attachment,
            'description' => $seminar->Description ?? 'No Document Attached',
            'id' => $seminar->EmployeeTrainingProgramID,
            'department_id' => $department_id,
        ];

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        if($seminar->ClassificationID >= 5){
            $training = $seminar;
            $trainingFields = $seminarFields;
            return view('submissions.hris.development.training.edit', compact('id', 'training', 'trainingFields', 'values', 'colleges', 'departments'));
        }

        return view('submissions.hris.development.seminar.edit', compact('id', 'seminar', 'seminarFields', 'values', 'colleges', 'departments'));
    }

    public function updateSeminar(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

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
            ]);
        }

        \LogActivity::addToLog('Had updated a Seminar/Webinar.');

        return redirect()->route('submissions.development.index')->with('success','The accomplishment has been updated.');
    }

    public function updateTraining(Request $request, $id){
        $currentQuarterYear = Quarter::find(1);
        $college_id = Department::where('id', $request->input('department_id'))->pluck('college_id')->first();

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
            ]);
        }

        \LogActivity::addToLog('Had saved a Training.');

        return redirect()->route('submissions.development.index')->with('success','The accomplishment has been saved.');
    }

    public function delete($id){

        $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->pluck('id')->first();
        if(is_null($developmentID))
            $developmentID = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->pluck('id')->first();

        if(LockController::isLocked($developmentID, 25)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }
        if(LockController::isLocked($developmentID, 26)){
            return redirect()->back()->with('error', 'The accomplishment report has already been submitted.');
        }

        HRIS::where('id', $developmentID)->delete();

        \LogActivity::addToLog('Had deleted a Seminar/Webinar or Training.');

        return redirect()->route('submissions.development.index')->with('success','The accomplishment has been deleted.');
    }

    public function check($id){
        $development = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '4')->first();
        if(is_null($development))
            $development = HRIS::where('hris_id', $id)->where('user_id', auth()->id())->where('hris_type', '5')->first();

        if(LockController::isLocked($development->id, 25))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        if(LockController::isLocked($development->id, 26))
            return redirect()->back()->with('cannot_access', 'Accomplishment already submitted.');

        if($development->hris_type == '4'){
            if($this->submitSeminar($development->id))
                return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');
        }
        elseif($development->hris_type == '5'){
            if($this->submitTraining($development->id))
                return redirect()->back()->with('success', 'Accomplishment submitted succesfully.');
        }
        return redirect()->back()->with('cannot_access', 'Failed to submit the accomplishment.');
    }


    public function submitSeminar($development_id){
        $user = User::find(auth()->id());
        $development = HRIS::where('id', $development_id)->first();

        $db_ext = DB::connection('mysql_external');

        $seminars = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $seminar;

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
        $img = Image::make($seminar->Attachment);
        $fileName = 'HRIS-ADP-'.now()->timestamp.uniqid().'.jpeg';
        $newPath = storage_path().'/app/documents/'.$fileName;
        $img->save($newPath);

        HRISDocument::create([
            'hris_form_id' => 4,
            'reference_id' => $development_id,
            'filename' => $fileName,
        ]);
        array_push($filenames, $fileName);


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
            'description' => $seminar->Description,
            'department_id' => $department_name,
            'college_id' => $college_name,
        ];

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $development_id)
            ->where('report_category_id', 25)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $development->college_id,
            'department_id' => $development->department_id,
            'report_category_id' => 25,
            'report_code' => null,
            'report_reference_id' => $development_id,
            'report_details' => json_encode($values),
            'report_documents' => json_encode($filenames),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        return true;
    }

    public function submitTraining($development_id){
        $user = User::find(auth()->id());
        $development = HRIS::where('id', $development_id)->first();

        $db_ext = DB::connection('mysql_external');

        $trainings = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $training;

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
        $img = Image::make($training->Attachment);
        $fileName = 'HRIS-AT-'.now()->timestamp.uniqid().'.jpeg';
        $newPath = storage_path().'/app/documents/'.$fileName;
        $img->save($newPath);

        HRISDocument::create([
            'hris_form_id' => 5,
            'reference_id' => $development_id,
            'filename' => $fileName,
        ]);
        array_push($filenames, $fileName);

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
            'description' => $training->Description,
            'department_id' => $department_name,
            'college_id' => $college_name,
        ];

        $currentQuarterYear = Quarter::find(1);

        Report::where('report_reference_id', $development_id)
            ->where('report_category_id', 26)
            ->where('user_id', auth()->id())
            ->where('report_quarter', $currentQuarterYear->current_quarter)
            ->where('report_year', $currentQuarterYear->current_year)
            ->delete();

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $development->college_id,
            'department_id' => $development->department_id,
            'report_category_id' => 26,
            'report_code' => null,
            'report_reference_id' => $development_id,
            'report_details' => json_encode($values),
            'report_documents' => json_encode($filenames),
            'report_date' => date("Y-m-d", time()),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

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
