<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Employee,
    ExpertServiceConference,
    ExpertServiceConferenceDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ExtensionProgramField,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Quarter,
};

class ConferenceController extends Controller
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
        $this->authorize('viewAny', ExpertServiceConference::class);
        
        $currentQuarterYear = Quarter::find(1);

        $expertServicesConference = ExpertServiceConference::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_conferences.nature')
                                        ->join('colleges', 'colleges.id', 'expert_service_conferences.college_id')
                                        ->select(DB::raw('expert_service_conferences.*, dropdown_options.name as nature, colleges.name as college_name'))
                                        ->orderBy('expert_service_conferences.updated_at', 'desc')
                                        ->get();

        return view('extension-programs.expert-services.conference.index', compact('expertServicesConference', 'currentQuarterYear'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        return view('extension-programs.expert-services.conference.create', compact('expertServiceConferenceFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $request->validate([
            'to' => 'after_or_equal:from',
            'title' => 'max:500',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);


        $input = $request->except(['_token', '_method', 'document']);

        $esConference = ExpertServiceConference::create($input);
        $esConference->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ESCF-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceConferenceDocument::create([
                        'expert_service_conference_id' => $esConference->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added an expert service rendered in conference "'.$request->input('title').'".');

        return redirect()->route('expert-service-in-conference.index')->with('edit_esconference_success', 'Expert service rendered in conference, workshop, or training course has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('view', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        
        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $documents = ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->get()->toArray();
        
        $values = $expert_service_in_conference->toArray();
        

        return view('extension-programs.expert-services.conference.show', compact('expertServiceConferenceFields','expert_service_in_conference', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('update', ExpertServiceConference::class);

        if(LockController::isLocked($expert_service_in_conference->id, 10)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $expertServiceConferenceDocuments = ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->get()->toArray();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        
        $value = $expert_service_in_conference;
        $value->toArray();
        $value = collect($expert_service_in_conference);
        $value = $value->toArray();

        return view('extension-programs.expert-services.conference.edit', compact('expert_service_in_conference', 'expertServiceConferenceFields', 'expertServiceConferenceDocuments', 'colleges', 'value'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('update', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));

        $request->merge([
            'from' => $from,
            'to' => $to,
        ]);
        
        $request->validate([
            'to' => 'after_or_equal:from',
            'title' => 'max:500',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $expert_service_in_conference->update(['description' => '-clear']);

        $expert_service_in_conference->update($input);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ESCF-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceConferenceDocument::create([
                        'expert_service_conference_id' => $expert_service_in_conference->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated the expert service rendered in conference "'.$expert_service_in_conference->title.'".');


        return redirect()->route('expert-service-in-conference.index')->with('edit_esconference_success', 'Expert service rendered in conference, workshop, or training course has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('delete', ExpertServiceConference::class);

        if(LockController::isLocked($expert_service_in_conference->id, 10)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }


        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expert_service_in_conference->delete();
        ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->delete();

        \LogActivity::addToLog('Had deleted the expert service rendered in conference "'.$expert_service_in_conference->title.'".');

        return redirect()->route('expert-service-in-conference.index')->with('edit_esconference_success', 'Expert service rendered in conference, workshop, or training course has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        ExpertServiceConferenceDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of an expert service rendered in conference.');

        // Storage::delete('documents/'.$filename);
        return true;
    }
}
