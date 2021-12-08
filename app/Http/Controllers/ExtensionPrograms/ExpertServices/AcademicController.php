<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\ExpertServiceAcademic;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\ExpertServiceAcademicDocument;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Models\FormBuilder\ExtensionProgramField;

class AcademicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', ExpertServiceAcademic::class);

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
        $this->authorize('create', ExpertServiceAcademic::class);

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceAcademicFields = DB::select("CALL get_extension_program_fields_by_form_id(3)");

        $colleges = College::all();

        return view('extension-programs.expert-services.academic.create', compact('expertServiceAcademicFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ExpertServiceAcademic::class);
        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'classification' => 'required',
            'nature' => 'required',
            'other_nature' => 'required_if:nature,86',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            // 'publication_or_audio_visual' => '',
            'copyright_no' => 'required',
            // 'indexing' => '',
            'level' => 'required',
            'college_id' => 'required',
            // 'department_id' => '',
            // 'description' => 'required',
        ]);

        $input = $request->except(['_token', '_method', 'document', 'other_nature']);

        $esAcademic = ExpertServiceAcademic::create($input);
        $esAcademic->update(['user_id' => auth()->id()]);
        $esAcademic->update(['other_nature' => $request->input('other_nature')]);

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

        return redirect()->route('expert-service-in-academic.index')->with('edit_esacademic_success', 'Your Accomplishment in Expert Service in Academic Journals/Books/Publication/Newsletter/Creative Works has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertServiceAcademic $expert_service_in_academic)
    {
        $this->authorize('view', ExpertServiceAcademic::class);

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $expertServiceAcademicFields = DB::select("CALL get_extension_program_fields_by_form_id(3)");
        
        $documents = ExpertServiceAcademicDocument::where('expert_service_academic_id', $expert_service_in_academic->id)->get()->toArray();

        $values = $expert_service_in_academic->toArray();
         

        return view('extension-programs.expert-services.academic.show', compact('expertServiceAcademicFields', 'expert_service_in_academic', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertServiceAcademic $expert_service_in_academic)
    {
        $this->authorize('update', ExpertServiceAcademic::class);

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceAcademicFields = DB::select("CALL get_extension_program_fields_by_form_id(3)");

        $expertServiceAcademicDocuments = ExpertServiceAcademicDocument::where('expert_service_academic_id', $expert_service_in_academic->id)->get()->toArray();
        
        $colleges = College::all();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$expert_service_in_academic->department_id.")");

        $value = $expert_service_in_academic;
        $value->toArray();
        $value = collect($expert_service_in_academic);
        $value = $value->toArray();

        return view('extension-programs.expert-services.academic.edit', compact('value', 'expertServiceAcademicFields', 'expertServiceAcademicDocuments',
            'colleges', 'collegeOfDepartment'));
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
        $this->authorize('update', ExpertServiceAcademic::class);

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'classification' => 'required',
            'nature' => 'required',
            'other_nature' => 'required_if:nature,86',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            // 'publication_or_audio_visual' => '',
            'copyright_no' => 'required',
            // 'indexing' => '',
            'level' => 'required',
            'college_id' => 'required',
            // 'department_id' => '',
            // 'description' => 'required',
        ]);

        $input = $request->except(['_token', '_method', 'document', 'other_nature']);
        
        $expert_service_in_academic->update($input);
        $expert_service_in_academic->update(['other_nature' => $request->input('other_nature')]);

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

        return redirect()->route('expert-service-in-academic.index')->with('edit_esacademic_success', 'Your accomplishment in Expert Service in Academic Journals/Books/Publication/Newsletter/Creative Works has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertServiceAcademic $expert_service_in_academic)
    {
        $this->authorize('delete', ExpertServiceAcademic::class);

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expert_service_in_academic->delete();
        ExpertServiceAcademicDocument::where('expert_service_academic_id', $expert_service_in_academic->id)->delete();
        return redirect()->route('expert-service-in-academic.index')->with('edit_esacademic_success', 'Your accomplishment in Expert Service in Academic Journals/Books/Publication/Newsletter/Creative Works has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExpertServiceAcademic::class);

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        ExpertServiceAcademicDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);
        return true;
    }
}
