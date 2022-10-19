<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    Research\InviteController,
    StorageFileController,
};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{
    DB,
    Notification,
    Storage,
};
use App\Models\{
    Employee,
    Report,
    Research,
    ResearchCitation,
    ResearchComplete,
    ResearchCopyright,
    ResearchDocument,
    ResearchInvite,
    ResearchPresentation,
    ResearchPublication,
    ResearchUtilization,
    TemporaryFile,
    User,
    FormBuilder\DropdownOption,
    FormBuilder\ResearchField,
    FormBuilder\ResearchForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};
use App\Notifications\ResearchInviteNotification;
use App\Rules\Keyword;
use App\Services\DateContentService;
use Exception;

class ResearchController extends Controller
{
    protected $storageFileController;

    public function __construct(StorageFileController $storageFileController){
        $this->storageFileController = $storageFileController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Research::class);
        $year = 'started';
        $statusResearch = "started";//for filter

        $currentQuarterYear = Quarter::find(1);

        $researches = Research::where('research.user_id', auth()->id())
                                ->where('research.is_active_member', 1)
                                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                                ->join('colleges', 'colleges.id', 'research.college_id')
                                ->select('research.*', 'dropdown_options.name as status_name', 'colleges.name as college_name')
                                ->orderBy('research.updated_at', 'DESC')
                                ->get();

        $invites = ResearchInvite::join('research', 'research.id', 'research_invites.research_id')
                                ->join('users', 'users.id', 'research_invites.sender_id')
                                ->where('research_invites.user_id', auth()->id())
                                ->select(
                                    'users.first_name', 'users.last_name', 'users.middle_name', 'users.suffix',
                                    'research.title', 'research.research_code', 'research_invites.research_id',
                                    'research_invites.status'
                                )
                                ->where('research_invites.status', null)
                                ->get();

        return view('research.index', compact('researches', 'year', 'statusResearch', 'invites',
             'currentQuarterYear'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');
        $currentQuarter = Quarter::find(1)->current_quarter;

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $allUsers = [];
        $users = User::all()->except(auth()->id());
        $i = 0;
        foreach($users as $user) {
            if ($user->middle_name != null) {
                $userFullName = $user->last_name.', '.$user->first_name.' '.substr($user->middle_name, 0, 1).'.';
                if ($user->suffix != null) {
                    $userFullName = $user->last_name.', '.$user->first_name.' '.substr($user->middle_name, 0, 1).'. '.$user->suffix;
                }
            }
            else {
                if ($user->suffix != null) {
                    $userFullName = $user->last_name.', '.$user->first_name.' '.$user->suffix;
                } else {
                    $userFullName = $user->last_name.', '.$user->first_name;
                }
            }
            $allUsers[$i++] = array("id" => $user->id, 'fullname' => $userFullName);
        }

        return view('research.create', compact('researchFields', 'colleges', 'departments', 'dropdown_options', 'allUsers', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $value = $request->input('funding_amount');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $target_date = (new DateContentService())->checkDateContent($request, "target_date");
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'start_date' => $start_date,
            'target_date' => $target_date,
            'funding_amount' => $value,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $request->validate([
            // 'keywords' => new Keyword,
            'college_id' => 'required',
            'department_id' => 'required',
        ]);

        $departmentIni = Department::where('id', $request->input('department_id'))->pluck('code')->first();
        $classIni = '';
        $catIni = '';
        $resIni = '';

        $year = date("Y").'-';
        $expr = '/(?<=\s|^)[a-z]{0,4}/i';
        $expr2 = '/(?<=\s|^)[a-z]/i';
        $input = $request->except(['_token', 'document', 'funding_amount', 'tagged_collaborators']);

        if($request->has('researchers')){
            $researchers = $input['researchers'];
            $name = preg_split("/\//", $researchers);
            preg_match_all($expr2, $name[0], $matches);
            $result = implode('', $matches[0]);
            $resIni = strtoupper($result).'-';
        }
        
        if($request->has('classification')){
            $classificationName = DropdownOption::where('id', $input['classification'])->pluck('name')->first();
            if($classificationName == 'Program')
                $classIni = 'PG-';
            elseif($classificationName == 'Project')
                $classIni = 'PJ-';
            elseif($classificationName == 'Study')
                $classIni = 'S-';
            else{
                preg_match_all($expr, $classificationName, $matches);
                $result = implode('', $matches[0]);
                $classIni = strtoupper($result).'-';
            }
        }
        if($request->has('category')){
            $categoryName = DropdownOption::where('id', $input['category'])->pluck('name')->first();
            if($categoryName == 'Research')
                $catIni = 'RES-';
            elseif($categoryName == 'Book Chapter')
                $catIni = 'BC-';
            else{
                preg_match_all($expr2, $categoryName, $matches);
                $result = implode('', $matches[0]);
                $catIni = strtoupper($result).'-';
            }
        }


        $researchCodeIni = $classIni.$catIni.$departmentIni.'-'.$resIni.$year;
        $lastID = Research::withTrashed()->where('research_code', 'like', '%'.$researchCodeIni.'%')
            ->pluck('research_code')->last();

        if($lastID == null){
            $researchCode = $researchCodeIni.'01';
        }
        else{
            $lastIdSplit = preg_split('/-/',$lastID);

            if($lastIdSplit[count($lastIdSplit) - 3].'-' == $resIni){

                $lastIdDigit = (int) end($lastIdSplit);
                $lastIdDigit++;
                if($lastIdDigit < 10){
                    $lastIdDigit = '0'.$lastIdDigit;
                }
                $researchCode = $researchCodeIni.$lastIdDigit;
            }
            else{
                $researchCode = $researchCodeIni.'01';
            }

        }

        $funding_amount = $request->funding_amount;

        $funding_amount = str_replace( ',' , '', $funding_amount);

        // foreach ($request->input('tagged_collaborators') as $collab) {
            $research = Research::create([
                'research_code' => $researchCode,
                'user_id' => auth()->id(),
                'funding_amount' => $funding_amount,
                // 'nature_of_involvement' => 11
            ]);

            Research::where('id', $research->id)->update($input);

            if($request->has('document')){

                try {
                    $documents = $request->input('document');
                    foreach($documents as $document){
                        $temporaryFile = TemporaryFile::where('folder', $document)->first();
                        if($temporaryFile){
                            $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                            $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                            $ext = $info['extension'];
                            $fileName = 'RR-'.$researchCode.'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                            $newPath = "documents/".$fileName;
                            Storage::move($temporaryPath, $newPath);
                            Storage::deleteDirectory("documents/tmp/".$document);
                            $temporaryFile->delete();
    
                            ResearchDocument::create([
                                'research_id' => $research->id,
                                'research_code' => $researchCode,
                                'research_form_id' => 1,
                                'filename' => $fileName,
    
                            ]);
                        }
                    }
                } catch (Exception $th) {
                    return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
                }
                

              
            }
        // }

        $count = 0;
        if ($request->input('tagged_collaborators') != null) {
            foreach ($request->input('tagged_collaborators') as $collab) {
                if ($collab != auth()->id() ) {
                    ResearchInvite::create([
                        'user_id' => $collab,
                        'sender_id' => auth()->id(),
                        'research_id' => $research->id
                    ]);

                    $researcher = Research::find($research->id)->researchers;
                    $researcherExploded = explode("/", $researcher);
                    $user = User::find($collab);
                    if ($user->middle_name != '') {
                        array_push($researcherExploded, $user->last_name.', '.$user->first_name.' '.substr($user->middle_name,0,1).'.');
                    } else {
                        array_push($researcherExploded, $user->last_name.', '.$user->first_name);
                    }
                    
                    $research_title = Research::where('id', $research->id)->pluck('title')->first();
                    $sender = User::join('research', 'research.user_id', 'users.id')
                                    ->where('research.user_id', auth()->id())
                                    ->where('research.id', $research->id)
                                    ->select('users.first_name', 'users.last_name', 'users.middle_name', 'users.suffix')->first();
                    $url_accept = route('research.invite.confirm', $research->id);
                    $url_deny = route('research.invite.cancel', $research->id);

                    $notificationData = [
                        'receiver' => $user->first_name,
                        'title' => $research_title,
                        'sender' => $sender->first_name.' '.$sender->middle_name.' '.$sender->last_name.' '.$sender->suffix,
                        'url_accept' => $url_accept,
                        'url_deny' => $url_deny,
                        'date' => date('F j, Y, g:i a'),
                        'type' => 'res-invite'
                    ];

                    Notification::send($user, new ResearchInviteNotification($notificationData));
                }
                $count++;
                Research::where('id', $research->id)->update([
                    'researchers' => implode("/", $researcherExploded),
                ]);
            }
            \LogActivity::addToLog('Had added a co-researcher in the research "'.$research_title.'".'); 
        }           
        \LogActivity::addToLog('Had added a research entitled "'.$request->input('title').'".');

        return redirect()->route('research.index')->with('success', 'Research has been registered.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research)
    {
        $this->authorize('view', Research::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('1')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        $firstResearch = Research::where('research_code', $research->research_code)->first();
        //$values = Research::where('research_code', $research->research_code)->first()->toArray();

        if ($research->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$research->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $exists = 0;
        if(Report::where
        ('report_reference_id', $research->id)->where('report_category_id', 1)->exists()){
            $exists = 1;
        }

        $value = $research;
        $value = $value->toArray();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        $submissionStatus = array();
        $submitRole = array();
        $reportdata = new ReportDataController;
            if (LockController::isLocked($research->id, 1)) {
                $submissionStatus[1][$research->id] = 1;
                $submitRole[$research->id] = ReportDataController::getSubmitRole($research->id, 1);
            }
            else
                $submissionStatus[1][$research->id] = 0;
            if (empty($reportdata->getDocuments(1, $research->id)))
                $submissionStatus[1][$research->id] = 2;

        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $value[$field->name])->where('is_active', 1)->pluck('name')->first();
                if($dropdownOptions == null)
                    $dropdownOptions = "-";
                $value[$field->name] = $dropdownOptions;
            }
            elseif($field->field_type_name == "college"){
                if($value[$field->name] == '0'){
                    $value[$field->name] = 'N/A';
                }
                else{
                    $college = College::where('id', $value[$field->name])->pluck('name')->first();
                    $value[$field->name] = $college;
                }
            }
            elseif($field->field_type_name == "department"){
                if($value[$field->name] == '0'){
                    $value[$field->name] = 'N/A';
                }
                else{
                    $department = Department::where('id', $value[$field->name])->pluck('name')->first();
                    $value[$field->name] = $department;
                }
            }
        }

        return view('research.show', compact('research', 'researchFields', 'value', 'researchDocuments',
             'colleges', 'collegeOfDepartment', 'exists',
             'submissionStatus', 'submitRole', 'firstResearch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research)
    {
        $currentQuarter = Quarter::find(1)->current_quarter;
        $this->authorize('update', Research::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already committed this accomplishment. You can edit it again in the next quarter.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        if($research->nature_of_involvement != 11 || $research->nature_of_involvement != 224){
            $values = Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                        ->join('currencies', 'currencies.id', 'research.currency_funding_amount')
                        ->select('research.*', 'dropdown_options.name as status_name', 'currencies.code as currency_funding_amount_code')
                        ->first()->toArray();
        }
        else{
            $values = Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->first()->toArray();
        }

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        if ($research->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$research->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $firstResearch = Research::where('research_code', $research->research_code)->first();
        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();
        if ($research->id == $firstResearch['id'])
            return view('research.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'researchStatus', 'collegeOfDepartment', 'departments', 'dropdown_options', 'currentQuarter'));

        return view('research.edit-non-lead', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'researchStatus', 'collegeOfDepartment', 'departments', 'dropdown_options', 'currentQuarter'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research)
    {
        $currentQuarterYear = Quarter::find(1);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $value = $request->input('funding_amount');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $target_date = (new DateContentService())->checkDateContent($request, "target_date");

        $request->merge([
            'funding_amount' => $value,
            'start_date' => $start_date,
            'target_date' => $target_date,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $request->validate([
            // 'keywords' => new Keyword,
            'college_id' => 'required',
            'department_id' => 'required',
        ]);


        $input = $request->except(['_token', '_method', 'document', 'funding_amount']);
        $inputOtherResearchers = $request->except(['_token', '_method', 'document', 'funding_amount', 'college_id', 'department_id', 'nature_of_involvement']);
        $funding_amount = $request->funding_amount;
        $funding_amount = str_replace( ',' , '', $funding_amount);

        $research->update(['description' => '-clear']);

        $research->update($input);
        Research::where('research_code', $research->research_code)->update($inputOtherResearchers);
        Research::where('research_code', $research->research_code)->update([
            'funding_amount' => $funding_amount,
        ]);

        if($request->has('document')){
            try {
                $documents = $request->input('document');
                $count = 1;
                foreach($documents as $document){
                    $temporaryFile = TemporaryFile::where('folder', $document)->first();
                    if($temporaryFile){
                        $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                        $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                        $ext = $info['extension'];
                        $fileName = 'RR-'.$research->researchCode.'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                        $newPath = "documents/".$fileName;
                        Storage::move($temporaryPath, $newPath);
                        Storage::deleteDirectory("documents/tmp/".$document);
                        $temporaryFile->delete();
    
                        ResearchDocument::create([
                            'research_id' => $research->id,
                            'research_code' => $research->research_code,
                            'research_form_id' => 1,
                            'filename' => $fileName,
    
                        ]);
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }
           
        }

        \LogActivity::addToLog('Had updated the details of research "'.$research->title.'".');

        return redirect()->route('research.show', $research->id)->with('success', 'Research has been updated.');
    }

    public function updateNonLead (Request $request, Research $research)
    {
        $currentQuarterYear = Quarter::find(1);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->merge([
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);
        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required',
        ]);
        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $target_date = (new DateContentService())->checkDateContent($request, "target_date");
        $request->merge([
            'start_date' => $start_date,
            'target_date' => $target_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);
        $input = $request->except(['_token', '_method', 'document']);
        Research::where('id', $research->id)->update($input);

        \LogActivity::addToLog('Had updated the details of research "'.$research->title.'".');

        return redirect()->route('research.show', $research)->with('success', 'Research has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research)
    {
        $this->authorize('delete', Research::class);

        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $research->delete();
        ResearchInvite::where('research_id', $research->id)->delete();
        ResearchDocument::where('research_id', $research->id)->delete();

        \LogActivity::addToLog('Had deleted the research entitled "'.$research->title.'".');


        return redirect()->route('research.index')->with('success', 'Research has been deleted.');
    }

    public function complete($complete){
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $complete)->pluck('research_code')->first();
        $researchCompleteId = ResearchComplete::where('research_code', $research)->pluck('id')->first();
        if($researchCompleteId != null)
            return redirect()->route('research.completed.edit', ['research' => $complete, 'completed' =>  $researchCompleteId]);
        else
            return redirect()->route('research.completed.create', $complete);
    }

    public function publication($publication){
        if(ResearchForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $publication)->pluck('research_code')->first();
        $researchPublicationId = ResearchPublication::where('research_code', $research)->pluck('id')->first();
        if($researchPublicationId != null)
            return redirect()->route('research.publication.edit', ['research' => $publication, 'publication' =>  $researchPublicationId]);
        else
            return redirect()->route('research.publication.create', $publication);
    }

    public function presentation($presentation){
        if(ResearchForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $presentation)->pluck('research_code')->first();
        $researchPresentationId = ResearchPresentation::where('research_code', $research)->pluck('id')->first();
        if($researchPresentationId != null)
            return redirect()->route('research.presentation.edit', ['research' => $presentation, 'presentation' =>  $researchPresentationId]);
        else
            return redirect()->route('research.presentation.create', $presentation);
    }

    public function copyright($copyright){
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $copyright)->pluck('research_code')->first();
        $researchCopyrightId = ResearchCopyright::where('research_code', $research)->pluck('id')->first();
        if($researchCopyrightId != null)
            return redirect()->route('research.copyrighted.edit', ['research' => $copyright, 'copyrighted' =>  $researchCopyrightId]);
        else
            return redirect()->route('research.copyrighted.create', $copyright);
    }

    public function removeDoc($filename){
        ResearchDocument::where('filename', $filename)->delete();
        return true;
    }

    public function useResearchCode(Request $request){
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(Research::where('research_code', $request->input('code'))->where('user_id', auth()->id())->exists()){
            return redirect()->route('research.index')->with('code-missing', 'Research Already added. If it is not displayed, you are already removed by the Lead Researcher/ Team Leader');
        }
        if (Research::where('research_code', $request->code)->exists())
            return redirect()->route('research.code.create', $request->code);
        else
            return redirect()->route('research.index')->with('code-missing', 'Code does not exist');
    }

    public function addResearch($research_id, Request $request){
        $currentQuarterYear = Quarter::find(1);
        $currentQuarter = Quarter::find(1)->current_quarter;

        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $research = Research::where('research.id', $research_id)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->join('currencies', 'currencies.id', 'research.currency_funding_amount')
                ->select('research.*', 'dropdown_options.name as status_name', 'currencies.code as currency_funding_amount')
                ->first();

        if ($research == null)
            return redirect()->route('research.index')->with('code-missing', 'The research not found in the system.');

        $research = collect($research);
        $research = $research->except(['nature_of_involvement', 'college_id', 'department_id']);
        $values = $research->toArray();
        $research = json_decode(json_encode($research), FALSE);
        // $research = collect($research);
        $researchers = Research::where('research_code', $research->research_code)->pluck('researchers')->all();

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();

        $notificationID = $request->get('id');

        return view('research.code-create', compact('research', 'researchers', 'researchDocuments', 'values', 'researchFields', 'colleges', 'researchStatus', 'notificationID', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    public function saveResearch($research_id, Request $request){
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarterYear = Quarter::find(1);
        $yearAndQuarter = [
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ];

        $request->merge([
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $research = Research::where('id', $research_id)->first()->toArray();
        if (empty($research['start_date']))
            $start_date = null;
        else
            $start_date = date("Y-m-d", strtotime($research['start_date']));

        if (empty($research['target_date']))
            $target_date = null;
        else
            $target_date = date("Y-m-d", strtotime($research['target_date']));

        $research = collect($research);
        $researchFiltered= $research->except(['id', 'college_id', 'department_id', 'nature_of_involvement', 'user_id', 'created_at', 'updated_at', 'deleted_at']);
        $fromRequest = $request->except(['_token', 'document', 'notif_id']);
        $data = array_merge($researchFiltered->toArray(), $fromRequest);
        $data = array_merge($yearAndQuarter, $data);
        $data = Arr::add($data, 'user_id', auth()->id());
        $saved = Research::create($data);

        Research::where('id', $saved->id)->update([
            'start_date' => $start_date,
            'target_date' => $target_date
        ]);
        Research::where('research_code', $saved->research_code)->update([
            'researchers' => $request->input('researchers'),
        ]);
        ResearchInvite::where('research_id', $research_id)->where('user_id', auth()->id())->update([
            'status' => 1
        ]);

        $receiver_user_id = Research::where("id", $research_id)->pluck('user_id')->first();
        $receiver = User::find($receiver_user_id);
        $research_title = Research::where('id', $research_id)->pluck('title')->first();
        $sender = User::find(auth()->id());
        $url = route('research.show', $research_id);

        $notificationData = [
            'receiver' => $receiver->first_name,
            'title' => $research_title,
            'sender' => $sender->first_name.' '.$sender->middle_name.' '.$sender->last_name.' '.$sender->suffix,
            'url' => $url,
            'date' => date('F j, Y, g:i a'),
            'type' => 'res-confirm'
        ];

        Notification::send($receiver, new ResearchInviteNotification($notificationData));

        if($request->has('notif_id'))
            $sender->notifications()
                        ->where('id', $request->input('notif_id')) // and/or ->where('type', $notificationType)
                        ->get()
                        ->first()
                        ->delete();

        \LogActivity::addToLog('Had saved a research entitled "'.$research_title.'".');


        return redirect()->route('research.index')->with('success', 'Research has been saved.');
    }

    public function retrieve($research_code){
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('research_code', $research_code)->where('user_id', auth()->id())->first();
        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        $researchLead = Research::where('research_code', $research_code)->first()->toArray();
        $researchLead = collect($researchLead);
        $researchLead = $researchLead->except(['id','research_code', 'college_id', 'department_id', 'nature_of_involvement', 'user_id', 'created_at', 'updated_at', 'deleted_at' ]);
        Research::where('research_code', $research_code)->where('user_id', auth()->id())
                ->update($researchLead->toArray());
        $research = Research::where('research_code', $research_code)->where('user_id', auth()->id())->first();
        return redirect()->route('research.show', $research->id)->with('success', 'Latest version has been retrieved.');
    }

    public function addDocument($research_code, $report_category_id){
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        return view('research.add-documents', compact('research_code', 'report_category_id'));
    }

    public function saveDocument($research_code, $report_category_id, Request $request){
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if($report_category_id == 5){
            $citation_id = $research_code;
            $research_code = ResearchCitation::where('id', $citation_id)->pluck('research_code')->first();
        }
        if($report_category_id == 6){
            $utilization_id = $research_code;
            $research_code = ResearchUtilization::where('id', $utilization_id)->pluck('research_code')->first();
        }

        $string = str_replace(' ', '-', $request->input('description')); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($request->has('document')){

            try {
                $documents = $request->input('document');
                $count = 1;
                foreach($documents as $document){
                    $temporaryFile = TemporaryFile::where('folder', $document)->first();
                    if($temporaryFile){
                        $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                        $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                        $ext = $info['extension'];
                        $fileName = 'RR-'.$researchCode.'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                        $newPath = "documents/".$fileName;
                        Storage::move($temporaryPath, $newPath);
                        Storage::deleteDirectory("documents/tmp/".$document);
                        $temporaryFile->delete();

                        if($report_category_id == 5){//citations
                            ResearchDocument::create([
                                'research_code' => $research_code,
                                'research_form_id' => $report_category_id,
                                'research_citation_id' => $citation_id,
                                'filename' => $fileName,
                            ]);
                        }
                        elseif($report_category_id == 6){
                            ResearchDocument::create([
                                'research_code' => $research_code,
                                'research_form_id' => $report_category_id,
                                'research_utilization_id' => $utilization_id,
                                'filename' => $fileName,
                            ]);
                        }
                        else{
                            ResearchDocument::create([
                                'research_code' => $research_code,
                                'research_form_id' => $report_category_id,
                                'filename' => $fileName,

                            ]);
                        }
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }



            
        }
        return redirect()->route('to-finalize.index')->with('success', 'Document added successfully');
    }

    public function manageResearchers($research_code){
        $research = Research::where('research_code', $research_code)->where('user_id', auth()->id())->first();
        if($research->nature_of_involvement != 11)
            abort('404');
        $researchers = Research::select('research.id', 'research.user_id',  'research.nature_of_involvement', 'dropdown_options.name as nature_of_involvement_name', 'users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->join('dropdown_options', 'dropdown_options.id', 'research.nature_of_involvement')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();
        $inactive_researchers = Research::select('research.id', 'research.user_id',  'research.nature_of_involvement', 'dropdown_options.name as nature_of_involvement_name', 'users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->join('dropdown_options', 'dropdown_options.id', 'research.nature_of_involvement')
                ->where('research.research_code', $research_code)->where('is_active_member', 0)
                ->get();
        $nature_of_involvement_dropdown = DropdownOption::where('dropdown_id', 4)->where('is_active', 1)->orderBy('order')->get();
        return view('research.manage-researchers.index', compact('research', 'research_code', 'researchers', 'inactive_researchers','nature_of_involvement_dropdown'));
    }

    public function saveResearchRole($research_code, Request $request){
        Research::where('research_code', $research_code)->where('user_id', $request->input('user_id'))->update([
            'nature_of_involvement' => $request->input('nature_of_involvement')
        ]);
        return redirect()->route('research.manage-researchers', $research_code)->with('success', 'Researcher records has been updated.');
    }

    public function removeResearcher($research_code, Request $request){
        Research::where('research_code', $research_code)->where('user_id', $request->input('user_id'))->update([
            'is_active_member' => 0
        ]);
        $researchers = Research::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();

        $researcherNewName = '';
        foreach($researchers as $researcher){
            if(count($researchers) == 1)
                $researcherNewName = $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name;
            else
                $researcherNewName .= $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name.', ';
        }

        Research::where('research_code', $research_code)->update([
            'researchers' => $researcherNewName
        ]);

        return redirect()->route('research.manage-researchers', $research_code)->with('success', 'Researcher has been removed.');
    }

    public function returnResearcher($research_code, Request $request){
        Research::where('research_code', $research_code)->where('user_id', $request->input('user_id'))->update([
            'is_active_member' => 1
        ]);
        $researchers = Research::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();

        $researcherNewName = '';
        foreach($researchers as $researcher){
            if(count($researchers) == 1)
                $researcherNewName = $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name;
            else
                $researcherNewName .= $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name.', ';
        }

        Research::where('research_code', $research_code)->update([
            'researchers' => $researcherNewName
        ]);

        return redirect()->route('research.manage-researchers', $research_code)->with('success', 'Researcher has been added.');
    }

    public function removeSelf($research_code){
        $research_id = Research::where('research_code', $research_code)->where('user_id', auth()->id())->pluck('id')->first();
        if(LockController::isLocked($research_id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        Research::where('research_code', $research_code)->where('user_id', auth()->id())->delete();
        $researchers = Research::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();

        $researcherNewName = '';
        foreach($researchers as $researcher){
            if(count($researchers) == 1)
                $researcherNewName = $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name;
            else
                $researcherNewName .= $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name.', ';
        }

        Research::where('research_code', $research_code)->update([
            'researchers' => $researcherNewName
        ]);

        ResearchInvite::where('research_id', $research_id)->where('user_id', auth()->id())->delete();

        return redirect()->route('research.index')->with('success', 'Research has been removed.');
    }

    public function researchYearFilter($year, $statusResearch) {

        if ($year == "started" || $year == "completion" || $year == "published" || $year == "presented" || $year == "created") {
            return redirect()->route('research.index');
        }

        $currentQuarterYear = Quarter::find(1);

        $researchStatus = DropdownOption::where('dropdown_id', 7)->get();

        $research_in_colleges = Research::whereNull('research.deleted_at')->join('colleges', 'research.college_id', 'colleges.id')
                                        ->where('user_id', auth()->id())
                                        ->select('colleges.name')
                                        ->distinct()
                                        ->get();

        if ($statusResearch == 'started') {
            $researches = Research::select(DB::raw('research.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(research.updated_at) as quarter'))
                    ->where('user_id', auth()->id())
                    ->where('is_active_member', 1)
                    ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                    ->join('colleges', 'colleges.id', 'research.college_id')
                    ->whereYear('research.start_date', $year)
                    ->orderBy('research.updated_at', 'desc')
                    ->get();
        }

        elseif ($statusResearch == 'completion') {
            $researches = Research::where('user_id', auth()->id())->where('is_active_member', 1)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                    ->join('colleges', 'colleges.id', 'research.college_id')
                    ->whereYear('research.completion_date', $year)
                    ->select(DB::raw('research.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(research.updated_at) as quarter'))
                    ->orderBy('research.updated_at', 'desc')
                    ->get();
        }

        elseif ($statusResearch == 'published') {
            $researches = ResearchPublication::where('user_id', auth()->id())->where('is_active_member', 1)
                    ->join('research', 'research.id', 'research_publications.research_id')
                    ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                    ->join('colleges', 'colleges.id', 'research.college_id')
                    ->whereYear('research_publications.publish_date', $year)
                    ->select(DB::raw('research.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(research.updated_at) as quarter'))
                    ->orderBy('research.updated_at', 'desc')
                    ->get();
        }

        elseif ($statusResearch == 'presented') {
            $researches = ResearchPresentation::where('user_id', auth()->id())->where('is_active_member', 1)
                    ->join('research', 'research.id', 'research_presentations.research_id')
                    ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                    ->join('colleges', 'colleges.id', 'research.college_id')
                    ->whereYear('research_presentations.date_presented', $year)
                    ->select(DB::raw('research.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(research.updated_at) as quarter'))
                    ->orderBy('research.updated_at', 'desc')
                    ->get();

        }

        elseif ($statusResearch == 'created') {
            $researches = Research::where('user_id', auth()->id())->where('is_active_member', 1)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                        ->join('colleges', 'colleges.id', 'research.college_id')
                        ->select(DB::raw('research.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(research.updated_at) as quarter'))
                        ->orderBy('research.updated_at', 'desc')
                        ->whereYear('research.created_at', $year)
                        ->get();

        }

        else {
            return redirect()->route('research.index');
        }

        $invites = ResearchInvite::join('research', 'research.id', 'research_invites.research_id')
                                ->join('users', 'users.id', 'research_invites.sender_id')
                                ->where('research_invites.user_id', auth()->id())
                                ->where('research_invites.status', null)
                                ->select(
                                    'users.first_name', 'users.last_name', 'users.middle_name', 'users.suffix',
                                    'research.title', 'research.research_code',
                                    'research_invites.status'
                                )
                                ->get();

        return view('research.index', compact('researches', 'researchStatus', 'research_in_colleges', 'year', 'statusResearch', 'invites', 'currentQuarterYear'));

    }
}

