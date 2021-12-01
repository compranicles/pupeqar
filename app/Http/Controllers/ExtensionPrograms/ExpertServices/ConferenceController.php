<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ExpertServiceConference;
use Illuminate\Support\Facades\Storage;
use App\Models\ExpertServiceConferenceDocument;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Models\FormBuilder\ExtensionProgramField;


class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', ExpertServiceConference::class);

        $expertServicesConference = ExpertServiceConference::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_conferences.nature')
                                        ->select('expert_service_conferences.*', 'dropdown_options.name as nature')
                                        ->get();

        return view('extension-programs.expert-services.conference.index', compact('expertServicesConference'));
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

        return view('extension-programs.expert-services.conference.create', compact('expertServiceConferenceFields'));
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

        $request->validate([
            'nature' => 'required',
            'level' => 'required',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'title' => 'required',
            // 'venue' => '',
            'partner_agency' => '',
            // 'description' => 'required',
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
                    $fileName = 'ESConference-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('faculty.expert-service-in-conference.index')->with('edit_esconference_success', 'Your Accomplishment in Expert Service in Conference/Workshop/Training Course  has been saved.');
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
        $expertServiceConferenceDocuments = ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->get()->toArray();
        
        $nature = DB::select("CALL get_dropdown_name_by_id(".$expert_service_in_conference->nature.")");

        $level = DB::select("CALL get_dropdown_name_by_id(".$expert_service_in_conference->level.")");

        return view('extension-programs.expert-services.conference.show', compact('expert_service_in_conference', 'expertServiceConferenceDocuments', 'nature', 'level'));
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

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $expertServiceConferenceDocuments = ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->get()->toArray();
        
        return view('extension-programs.expert-services.conference.edit', compact('expert_service_in_conference', 'expertServiceConferenceFields', 'expertServiceConferenceDocuments'));
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

        $request->validate([
            'nature' => 'required',
            'level' => 'required',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'title' => 'required',
            // 'venue' => '',
            'partner_agency' => '',
            // 'description' => 'required',
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $expert_service_in_conference->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ESConference-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('faculty.expert-service-in-conference.index')->with('edit_esconference_success', 'Your accomplishment in Expert Service in Conference/Workshop/Training Course has been updated.');
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

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expert_service_in_conference->delete();
        ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->delete();
        return redirect()->route('faculty.expert-service-in-conference.index')->with('edit_esconference_success', 'Your accomplishment in Expert Service in Conference/Workshop/Training Cours has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        ExpertServiceConferenceDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);
        return true;
    }
}
