<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\ExpertServiceConsultant;
use Illuminate\Support\Facades\Storage;
use App\Models\ExpertServiceConsultantDocument;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Models\FormBuilder\DropdownOption;
use App\Models\FormBuilder\ExtensionProgramField;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', ExpertServiceConsultant::class);

        $classifications = DropdownOption::where('dropdown_id', 14)->get();
        
        $expertServicesConsultant = ExpertServiceConsultant::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_consultants.classification')
                                        ->join('colleges', 'colleges.id', 'expert_service_consultants.college_id')
                                        ->whereYear('expert_service_consultants.updated_at', date('Y'))
                                        ->select(DB::raw('expert_service_consultants.*, dropdown_options.name as classification_name, colleges.name as college_name, QUARTER(expert_service_consultants.updated_at) as quarter'))
                                        ->orderBy('expert_service_consultants.updated_at', 'desc')
                                        ->get();

        $consultant_in_colleges = ExpertServiceConsultant::join('colleges', 'expert_service_consultants.college_id', 'colleges.id')
                                ->select('colleges.name')
                                ->distinct()
                                ->get();
        
        return view('extension-programs.expert-services.consultant.index', compact('expertServicesConsultant', 'classifications', 'consultant_in_colleges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceConsultantFields = DB::select("CALL get_extension_program_fields_by_form_id('1')");

        $colleges = College::all();

        return view('extension-programs.expert-services.consultant.create', compact('expertServiceConsultantFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'to' => 'after_or_equal:from',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $esConsultant = ExpertServiceConsultant::create($input);
        $esConsultant->update(['user_id' => auth()->id()]);

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
                    $fileName = 'ESConsultant-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceConsultantDocument::create([
                        'expert_service_consultant_id' => $esConsultant->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('expert-service-as-consultant.index')->with('edit_esconsultant_success', 'Expert service rendered as consultant has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('view', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $expertServiceConsultantFields = DB::select("CALL get_extension_program_fields_by_form_id('1')");
       
        $documents = ExpertServiceConsultantDocument::where('expert_service_consultant_id', $expert_service_as_consultant->id)->get()->toArray();
        
        $values = $expert_service_as_consultant->toArray();

        return view('extension-programs.expert-services.consultant.show', compact('expertServiceConsultantFields','expert_service_as_consultant', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('update', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        // dd($expert_service_as_consultant);
        $expertServiceConsultantFields = DB::select("CALL get_extension_program_fields_by_form_id('1')");

        $expertServiceConsultantDocuments = ExpertServiceConsultantDocument::where('expert_service_consultant_id', $expert_service_as_consultant->id)->get()->toArray();

        $colleges = College::all();

        $value = $expert_service_as_consultant;
        $value->toArray();
        $value = collect($expert_service_as_consultant);
        $value = $value->toArray();
        
        return view('extension-programs.expert-services.consultant.edit', compact('expert_service_as_consultant', 'expertServiceConsultantFields', 'expertServiceConsultantDocuments', 'colleges', 'value'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('update', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'to' => 'after_or_equal:from',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $expert_service_as_consultant->update(['description' => '-clear']);

        $expert_service_as_consultant->update($input);

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
                    $fileName = 'ESConsultant-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceConsultantDocument::create([
                        'expert_service_consultant_id' => $expert_service_as_consultant->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('expert-service-as-consultant.index')->with('edit_esconsultant_success', 'Expert service rendered as consultant has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('delete', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expert_service_as_consultant->delete();
        ExpertServiceConsultantDocument::where('expert_service_consultant_id', $expert_service_as_consultant->id)->delete();
        return redirect()->route('expert-service-as-consultant.index')->with('edit_esconsultant_success', 'Expert service rendered as consultant has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        ExpertServiceConsultantDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);
        return true;
    }
}
