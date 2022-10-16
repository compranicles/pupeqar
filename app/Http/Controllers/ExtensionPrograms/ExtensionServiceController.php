<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Storage,
    Notification
};
use App\Models\{
    Employee,
    ExtensionService,
    ExtensionServiceDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ExtensionProgramField,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
    User,
    ExtensionInvite,
};
use App\Notifications\ExtensionInviteNotification;
use App\Rules\Keyword;
use App\Services\DateContentService;
use Exception;

class ExtensionServiceController extends Controller
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
        $this->authorize('viewAny', ExtensionService::class);

        $currentQuarterYear = Quarter::find(1);

        $extensionServices = ExtensionService::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'extension_services.status')
                                        ->join('colleges', 'colleges.id', 'extension_services.college_id')
                                        ->select(DB::raw('extension_services.*, dropdown_options.name as status, colleges.name as college_name, QUARTER(extension_services.updated_at) as quarter'))
                                        ->orderBy('extension_services.updated_at', 'desc')
                                        ->get();


        $invites = ExtensionInvite::join('extension_services', 'extension_services.id', 'extension_invites.extension_service_id')
            ->join('users', 'users.id', 'extension_invites.sender_id')
            ->where('extension_invites.user_id', auth()->id())
            ->select(
                'users.first_name', 'users.last_name', 'users.middle_name', 'users.suffix',
                'extension_services.ext_code', 'extension_invites.extension_service_id',
                'extension_invites.status', 'extension_services.title_of_extension_program',
                'extension_services.title_of_extension_project', 'extension_services.title_of_extension_activity', 
            )
            ->where('extension_invites.status', null)
            ->get();

        $submissionStatus = [];
        $submitRole = "";
        $reportdata = new ReportDataController;
        foreach ($extensionServices as $extension) {
            if (LockController::isLocked($extension->id, 12)) {
                $submissionStatus[12][$extension->id] = 1;
                $submitRole[$extension->id] = ReportDataController::getSubmitRole($extension->id, 12);
            }
            else
                $submissionStatus[12][$extension->id] = 0;
            if (empty($reportdata->getDocuments(12, $extension->id)))
                $submissionStatus[12][$extension->id] = 2;
            }

        // dd($reportdata->getDocuments(12, $extension->id));

        return view('extension-programs.extension-services.index', compact('extensionServices', 'currentQuarterYear', 'invites', 'submissionStatus', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentQuarter = Quarter::find(1)->current_quarter;
        $this->authorize('create', ExtensionService::class);

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");

        $dropdown_options = [];
        foreach($extensionServiceFields as $field){
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

        return view('extension-programs.extension-services.create', compact('extensionServiceFields', 'colleges', 'departments', 'dropdown_options', 'allUsers', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ExtensionService::class);

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $value = $request->input('amount_of_funding');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");

        $currentQuarterYear = Quarter::find(1);

        $abbr = '';
        if($request->has('title_of_extension_program'))
            $abbr = 'EXT-PRG';
        elseif($request->has('title_of_extension_project'))
            $abbr = 'EXT-PRO';
        elseif($request->has('title_of_extension_activity'))
            $abbr = 'EXT-ACT';

        $extensionist = DB::table('users')->select(DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))->where('id', auth()->id())->first();
        $extIni =  preg_split("/[\s,_-]+/", $extensionist->faculty_name);
        $temp = '';
        foreach($extIni as $word){
            preg_match_all('/(?<=\s|^)[a-z]/i', $word, $matches);
            $result = implode('', $matches[0]);
            $temp = $temp.strtoupper($result);
        }
        $extIni = $temp;

        $departmentIni = Department::where('id', $request->input('department_id'))->pluck('code')->first();
        $ext_code = $abbr.'-'.$departmentIni.'-'.$extIni.'-'.$currentQuarterYear->current_year.'-';
        $last_code = ExtensionService::withTrashed()->where('ext_code', 'like', '%'.$ext_code.'%')
            ->pluck('ext_code')->last();

        if($last_code == null){
            $ext_code = $ext_code.'01';
        }
        else{
            $lastCodeSplit = preg_split('/-/',$last_code);
            if($lastCodeSplit[count($lastCodeSplit) - 3] == $extIni){

                $lastIdDigit = (int) end($lastCodeSplit);
                $lastIdDigit++;
                if($lastIdDigit < 10){
                    $lastIdDigit = '0'.$lastIdDigit;
                }
                $ext_code = $ext_code.$lastIdDigit;
            }
            else{
                $ext_code = $ext_code.'01';
            }
        }

        $request->merge([
            'ext_code' => $ext_code,
            'amount_of_funding' => $value,
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        // dd($request->input('to'));
        $request->validate([
            // 'keywords' => new Keyword,
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        if ($request->input('total_no_of_hours') != '') {
            $request->validate([
                'total_no_of_hours' => 'numeric',
            ]);
        }

        $input = $request->except(['_token', '_method', 'document', 'tagged_collaborators']);

        $eService = ExtensionService::create($input);
        $eService->update(['user_id' => auth()->id()]);

        ExtensionInvite::create([
            'user_id' => auth()->id(),
            'sender_id' => auth()->id(),
            'status' => 1,
            'extension_service_id' => $eService->id,
            'ext_code' => $ext_code,
            'is_owner' => 1
        ]);
        
        if($request->has('document')){


            try {
                $documents = $request->input('document');
                foreach($documents as $document){
                    $temporaryFile = TemporaryFile::where('folder', $document)->first();
                    if($temporaryFile){
                        $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                        $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                        $ext = $info['extension'];
                        $fileName = 'ES-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                        $newPath = "documents/".$fileName;
                        Storage::move($temporaryPath, $newPath);
                        Storage::deleteDirectory("documents/tmp/".$document);
                        $temporaryFile->delete();
    
                        ExtensionServiceDocument::create([
                            'extension_service_id' => $eService->id,
                            'ext_code' => $eService->ext_code,
                            'filename' => $fileName,
                        ]);
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }
        }

        $count = 0;
        if ($request->input('tagged_collaborators') != null) {
            foreach ($request->input('tagged_collaborators') as $collab) {
                if ($collab != auth()->id() ) {
                    ExtensionInvite::create([
                        'user_id' => $collab,
                        'sender_id' => auth()->id(),
                        'extension_service_id' => $eService->id,
                        'ext_code' => $eService->ext_code
                    ]);
    
                    $user = User::find($collab);
                    $extension_title = "Extension";
                    $sender = User::join('extension_services', 'extension_services.user_id', 'users.id')
                                    ->where('extension_services.user_id', auth()->id())
                                    ->where('extension_services.id', $eService->id)
                                    ->select('users.first_name', 'users.last_name', 'users.middle_name', 'users.suffix')->first();
                    $url_accept = route('extension.invite.confirm', $eService->id);
                    $url_deny = route('extension.invite.cancel', $eService->id);
    
                    $notificationData = [
                        'receiver' => $user->first_name,
                        'title' => $extension_title,
                        'sender' => $sender->first_name.' '.$sender->middle_name.' '.$sender->last_name.' '.$sender->suffix,
                        'url_accept' => $url_accept,
                        'url_deny' => $url_deny,
                        'date' => date('F j, Y, g:i a'),
                        'type' => 'ext-invite'
                    ];
        
                    Notification::send($user, new ExtensionInviteNotification($notificationData));
                }
                $count++;
            }
            \LogActivity::addToLog('Had added '.$count.' extension partners in an extension program/project/activity.');
        }
        \LogActivity::addToLog('Had added an extension program/project/activity.');

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Extension program/project/activity has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExtensionService $extension_service)
    {
        $this->authorize('view', ExtensionService::class);

        if (auth()->id() !== $extension_service->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");
        $extensionServiceDocuments = ExtensionServiceDocument::where('ext_code', $extension_service->ext_code)->get()->toArray();

        $values = $extension_service->toArray();

        $extensionRole = ExtensionInvite::where('user_id', auth()->id())->where('extension_service_id', $extension_service->id )->pluck('is_owner')->first();

        foreach($extensionServiceFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $values[$field->name])->where('is_active', 1)->pluck('name')->first();
                if($dropdownOptions == null)
                    $dropdownOptions = "-";
                $values[$field->name] = $dropdownOptions;
            }
            elseif($field->field_type_name == "college"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $college = College::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $college;
                }
            }
            elseif($field->field_type_name == "department"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $department = Department::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $department;
                }
            }
        }

        // dd($extensionServiceFields);
        return view('extension-programs.extension-services.show', compact('extension_service', 'extensionServiceDocuments', 'values', 'extensionServiceFields', 'extensionRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtensionService $extension_service)
    {
        $this->authorize('update', ExtensionService::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if (auth()->id() !== $extension_service->user_id)
            abort(403);

        if(LockController::isLocked($extension_service->id, 12)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");

        $dropdown_options = [];
        foreach($extensionServiceFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $extensionServiceDocuments = ExtensionServiceDocument::where('ext_code', $extension_service->ext_code)->get()->toArray();

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        if ($extension_service->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$extension_service->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $value = $extension_service;
        $value->toArray();
        $value = collect($extension_service);
        $value = $value->toArray();

        if (ExtensionInvite::where('user_id', auth()->id())->where('ext_code', $extension_service->ext_code)->pluck('is_owner')->first() == '1'){
            $is_owner = 1;
            return view('extension-programs.extension-services.edit', compact('value', 'extensionServiceFields', 'extensionServiceDocuments', 'colleges', 'collegeOfDepartment', 'is_owner', 'departments', 'dropdown_options', 'currentQuarter'));
        }
        $is_owner = 0;
        return view('extension-programs.extension-services.edit-code', compact('value', 'extensionServiceFields', 'extensionServiceDocuments', 'colleges', 'collegeOfDepartment', 'is_owner', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtensionService $extension_service)
    {
        $this->authorize('update', ExtensionService::class);
        $currentQuarterYear = Quarter::find(1);

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $value = $request->input('amount_of_funding');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");

        $request->merge([
            'amount_of_funding' => $value,
            'from' => $from,
            'to' => $to,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        if ($request->input('total_no_of_hours') != '') {
            $request->validate([
                'total_no_of_hours' => 'numeric',
            ]);
        }

        $input = $request->except(['_token', '_method', 'document']);

        $extension_service->update(['description' => '-clear']);

        $extension_service->update($input);

        if(ExtensionInvite::where('user_id', auth()->id())->where('ext_code', $extension_service->ext_code)->pluck('is_owner')->first()){
            $details = $request->except(['_token', '_method', 'document', 'nature_of_involvement', 'college_id', 'department_id']);
            ExtensionService::where('ext_code', $extension_service->ext_code)->update($details);
        }

        if($request->has('document')){


            try {
                $documents = $request->input('document');
                foreach($documents as $document){
                    $temporaryFile = TemporaryFile::where('folder', $document)->first();
                    if($temporaryFile){
                        $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                        $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                        $ext = $info['extension'];
                        $fileName = 'ES-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                        $newPath = "documents/".$fileName;
                        Storage::move($temporaryPath, $newPath);
                        Storage::deleteDirectory("documents/tmp/".$document);
                        $temporaryFile->delete();

                        ExtensionServiceDocument::create([
                            'extension_service_id' => $extension_service->id,
                            'ext_code' => $extension_service->ext_code,
                            'filename' => $fileName,
                        ]);
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }

        }

        \LogActivity::addToLog('Had updated an extension program/project/activity.');

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Extension program/project/activity has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionService $extension_service)
    {
        $this->authorize('delete', ExtensionService::class);

        if(LockController::isLocked($extension_service->id, 12)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(ExtensionInvite::where('ext_code', $extension_service->ext_code)->where('user_id', auth()->id())->pluck('is_owner')->first() == '1'){
            $extension_service->delete();
            ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->delete();
        }
        else{
            $extension_service->delete();
        }

        \LogActivity::addToLog('Had deleted an extension program/project/activity.');

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Extension program/project/activity has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExtensionService::class);

        ExtensionServiceDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of an extension program/project/activity.');

        // Storage::delete('documents/'.$filename);
        return true;
    }

    public function addExtension($extension_service_id, Request $request){
        $currentQuarterYear = Quarter::find(1);
        $currentQuarter = Quarter::find(1)->current_quarter;

        $id = $extension_service_id;

        $this->authorize('create', ExtensionService::class);

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");

        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");

        $dropdown_options = [];
        foreach($extensionServiceFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();


        $extension_service = ExtensionService::where('id', $id)->first();
        $value = $extension_service;
        $value->toArray();
        $value = collect($extension_service);
        $value = $value->except(['nature_of_involvement', 'college_id', 'department_id']);
        $value = $value->toArray();

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $notificationID = $request->get('id');

        $is_owner = 0;


        $extensionServiceDocuments = ExtensionServiceDocument::where('ext_code', $extension_service->ext_code)->get();
        if ($extension_service->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$extension_service->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        return view('extension-programs.extension-services.create-code', compact('value', 'extensionServiceFields', 'colleges', 'is_owner', 'notificationID', 'departments', 'collegeOfDepartment', 'extensionServiceDocuments', 'dropdown_options', 'currentQuarter'));
    }


    public function saveExtension($id, Request $request){
        $this->authorize('create', ExtensionService::class);

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $extensionService = ExtensionService::where('id', $id)->first();
        $extensionService = collect($extensionService);
        $extensionService = $extensionService->except(['id']);
        $extensionService = $extensionService->toArray();

        $request->merge([
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'notif_id']);


        $eService = ExtensionService::create($extensionService);
        $eService->update(['user_id' => auth()->id()]);
        $eService->update($input);

        ExtensionInvite::where('user_id', auth()->id())->where('extension_service_id', $id)->update([
            'status' => '1'
        ]);

        $receiver_user_id = ExtensionService::where("id", $id)->pluck('user_id')->first();
        $receiver = User::find($receiver_user_id);
        $sender = User::find(auth()->id());
        $url = route('extension-service.show', $id);

        $notificationData = [
            'receiver' => $receiver->first_name,
            'title' => '',
            'sender' => $sender->first_name.' '.$sender->middle_name.' '.$sender->last_name.' '.$sender->suffix,
            'url' => $url,
            'date' => date('F j, Y, g:i a'),
            'type' => 'ext-confirm'
        ];

        Notification::send($receiver, new ExtensionInviteNotification($notificationData));

        if($request->has('notif_id'))
            $sender->notifications()
                        ->where('id', $request->input('notif_id')) // and/or ->where('type', $notificationType)
                        ->get()
                        ->first()
                        ->delete();


        \LogActivity::addToLog('Extension program/project/activity was added.');

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Extension program/project/activity has been added.');
    }
}
