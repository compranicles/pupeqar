<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertServiceAcademic;
use App\Models\FormBuilder\ExtensionProgramField;
use App\Models\ExpertServiceAcademicDocument;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AcademicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expertServicesAcademic = ExpertServiceAcademic::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_academics.classification')
                                        ->select('expert_service_academics.*', 'dropdown_options.name as classification')
                                        ->get();

        return view('extension-programs.expert-services.academic.index', compact('expertServicesAcademic'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expertServiceAcademicFields = DB::select("CALL get_extension_program_fields_by_form_id('3')");

        return view('extension-programs.expert-services.academic.create', compact('expertServiceAcademicFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $esAcademic = ExpertServiceAcademic::create($input);
        $esAcademic->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ESAcademic-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceAcademicDocument::create([
                        'expert_service_academic_id' => $esAcademic->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('faculty.expert-service-in-academic.index')->with('edit_esacademic_success', 'Your Accomplishment in Expert Service in Academic Journals/Books/Publication/Newsletter/Creative Works has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertServiceAcademic $expert_service_in_academic)
    {
        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $expertServiceAcademicDocuments = ExpertServiceAcademicDocument::where('expert_service_academic_id', $expert_service_in_academic->id)->get()->toArray();
        
        return view('extension-programs.expert-services.academic.show', compact('expert_service_in_academic', 'expertServiceAcademicFields', 'expertServiceAcademicDocuments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertServiceAcademic $expert_service_in_academic)
    {
        $expertServiceAcademicFields = DB::select("CALL get_extension_program_fields_by_form_id('3')");

        $expertServiceAcademicDocuments = ExpertServiceAcademicDocument::where('expert_service_conference_id', $expert_service_in_academic->id)->get()->toArray();
        
        return view('extension-programs.expert-services.academic.edit', compact('expert_service_in_academic', 'expertServiceAcademicFields', 'expertServiceAcademicDocuments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertServiceAcademic $expert_service_in_academic)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $expert_service_in_academic->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ESAcademic-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceAcademicDocument::create([
                        'expert_service_academic_id' => $expert_service_in_academic->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('faculty.expert-service-in-academic.index')->with('edit_esacademic_success', 'Your accomplishment in Expert Service in Academic Journals/Books/Publication/Newsletter/Creative Works has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertServiceAcademic $expert_service_in_academic)
    {
        $expert_service_in_academic->delete();
        ExpertServiceAcademicDocument::where('expert_service_academic_id', $expert_service_in_academic->id)->delete();
        return redirect()->route('faculty.expert-service-in-academic.index')->with('edit_esacademic_success', 'Your accomplishment in Expert Service in Academic Journals/Books/Publication/Newsletter/Creative Works has been deleted.');
    }
}
