<?php

namespace App\Http\Controllers\Research;
use App\Models\User;
use App\Models\Report;
use App\Rules\Keyword;
use App\Models\Research;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchInvite;
use App\Models\ResearchCitation;
use App\Models\ResearchComplete;
use App\Models\ResearchDocument;
use App\Models\ResearchCopyright;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Models\ResearchPublication;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\ResearchPresentation;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ResearchForm;
use App\Models\FormBuilder\ResearchField;
use App\Models\FormBuilder\DropdownOption;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResearchInviteNotification;
use App\Http\Controllers\Maintenances\LockController;
use App\Services\DateContentService;

class ResearchController extends Controller
{
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

        $researchStatus = DropdownOption::where('dropdown_id', 7)->get();

        $researches = Research::where('research.user_id', auth()->id())
                                ->where('research.is_active_member', 1)
                                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                                ->join('colleges', 'colleges.id', 'research.college_id')
                                ->select('research.*', 'dropdown_options.name as status_name', 'colleges.name as college_name', DB::raw('QUARTER(research.updated_at) as quarter'))
                                ->orderBy('research.updated_at', 'DESC')
                                ->get();

        $research_in_colleges = Research::whereNull('research.deleted_at')->join('colleges', 'research.college_id', 'colleges.id')
                                        ->where('user_id', auth()->id())
                                        ->select('colleges.name')
                                        ->distinct()
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

        return view('research.index', compact('researches', 'researchStatus', 'research_in_colleges', 'year', 'statusResearch', 'invites', 'currentQuarterYear'));
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

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $colleges = College::all();
        return view('research.create', compact('researchFields', 'colleges'));
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
        ]);

        $request->validate([
            'keywords' => new Keyword,
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
        $input = $request->except(['_token', 'document', 'funding_amount']);

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
        if($request->has('researchers')){
            $researchers = $input['researchers'];
            $name = preg_split("/\//", $researchers);
            preg_match_all($expr2, $name[0], $matches);
            $result = implode('', $matches[0]);
            $resIni = strtoupper($result).'-';
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

        $research = Research::create([
            'research_code' => $researchCode, 
            'user_id' => auth()->id(), 
            'funding_amount' => $funding_amount,
            // 'nature_of_involvement' => 11
        ]);

        Research::where('id', $research->id)->update($input);

        $string = str_replace(' ', '-', $request->input('description')); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$researchCode.'-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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
        }

        \LogActivity::addToLog('Research entitled "'.$request->input('title').'" was added.');


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
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('1')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

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
        $value->toArray();

        $colleges = College::all();

        return view('research.show', compact('research', 'researchFields', 'value', 'researchDocuments', 'colleges', 'collegeOfDepartment', 'exists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research)
    {
        $this->authorize('update', Research::class);

        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

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
        $colleges = College::all();

        if ($research->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$research->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();
        if ($research->nature_of_involvement ==  11 || $research->nature_of_involvement == 224)
            return view('research.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'researchStatus', 'collegeOfDepartment'));

        return view('research.edit-non-lead', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'researchStatus', 'collegeOfDepartment'));

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
        ]);

        $request->validate([
            'keywords' => new Keyword,
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
        
        $string = str_replace(' ', '-', $request->input('description')); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($request->has('document')){
            $documents = $request->input('document');
            $count = 1;
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$research->research_code.'-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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
        }

        \LogActivity::addToLog('Research entitled "'.$research->title.'" was updated.');

        return redirect()->route('research.show', $research->id)->with('success', 'Research has been updated.');
    }

    public function updateNonLead (Request $request, Research $research)
    {
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required',
        ]);
        // dd($request);
        $input = $request->except(['_token', '_method', 'document']);
        Research::where('id', $research->id)->update($input);

        \LogActivity::addToLog('Research entitled "'.$research->title.'" was updated.');

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
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $research->delete();
        ResearchDocument::where('research_id', $research->id)->delete();

        \LogActivity::addToLog('Research entitled "'.$research->title.'" was deleted.');


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

        
        
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $research = Research::where('research.id', $research_id)->where('nature_of_involvement', 11)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->join('currencies', 'currencies.id', 'research.currency_funding_amount')
                ->select('research.*', 'dropdown_options.name as status_name', 'currencies.code as currency_funding_amount')
                ->first()->toArray();

        $research = collect($research);
        $research = $research->except(['nature_of_involvement', 'college_id', 'department_id']);
        $values = $research->toArray();
        $research = json_decode(json_encode($research), FALSE);
        // dd($values);
        // $research = collect($research);
        $researchers = Research::where('research_code', $research->research_code)->pluck('researchers')->all();

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();

        $departments = Department::all();
        $colleges = College::all();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();

        $notificationID = $request->get('id');

        return view('research.code-create', compact('research', 'researchers', 'researchDocuments', 'values', 'researchFields', 'departments', 'colleges', 'researchStatus', 'notificationID'));
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

        $research = Research::where('id', $research_id)->first()->toArray();
        $research = collect($research);
        $researchFiltered= $research->except(['id', 'college_id', 'department_id', 'researchers', 'nature_of_involvement', 'user_id', 'created_at', 'updated_at', 'deleted_at']);
        $fromRequest = $request->except(['_token', 'document', 'notif_id']);
        $data = array_merge($researchFiltered->toArray(), $fromRequest);
        $data = array_merge($yearAndQuarter, $data);
        $data = Arr::add($data, 'user_id', auth()->id());
        // dd($data);
        $saved = Research::create($data); 
        Research::where('research_code', $saved->research_code)->update([
            'researchers' => $research['researchers'].', '.auth()->user()->first_name.' '.auth()->user()->last_name
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
            'type' => 'confirm'
        ];

        Notification::send($receiver, new ResearchInviteNotification($notificationData));

        $sender->notifications()
                    ->where('id', $request->input('notif_id')) // and/or ->where('type', $notificationType)
                    ->where('type', 'App\Notifications\ResearchInviteNotification')
                    ->get()
                    ->first()
                    ->delete();

        \LogActivity::addToLog('Research added.');
        

        return redirect()->route('research.index')->with('success', 'Research has been saved');
    }

    public function retrieve($research_code){
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('research_code', $research_code)->where('user_id', auth()->id())->first();
        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
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
            $documents = $request->input('document');
            $count = 1;
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$research_code.'-'.$description.now()->timestamp.uniqid().'.'.$ext;
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
        // dd($researchers);
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
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
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

