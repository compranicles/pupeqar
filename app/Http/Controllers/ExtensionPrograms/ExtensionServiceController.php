<?php

namespace App\Http\Controllers\ExtensionPrograms;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ExtensionService;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\ExtensionServiceDocument;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Models\FormBuilder\ExtensionProgramField;

class ExtensionServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', ExtensionService::class);

        $extensionServices = ExtensionService::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'extension_services.status')
                                        ->select('extension_services.*', 'dropdown_options.name as status')
                                        ->get();

        return view('extension-programs.extension-services.index', compact('extensionServices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $this->authorize('create', ExtensionService::class);

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        // $extensionServiceFields1 = ExtensionProgramField::where('extension_program_fields.extension_programs_form_id', 4)
        //                                 ->where('extension_program_fields.is_active', 1)
        //                                 ->whereBetween('extension_program_fields.id', [30, 47])
        //                                 ->join('field_types', 'field_types.id', 'extension_program_fields.field_type_id')
        //                                 ->select('extension_program_fields.*', 'field_types.name as field_type_name')
        //                                 ->orderBy('order')
        //                                 ->get();

        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");

        $colleges = College::all();

        return view('extension-programs.extension-services.create', compact('extensionServiceFields', 'colleges'));
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

        $request->validate([
            'level' => 'required',
            'status' => 'required',
            'nature_of_involvement' => 'required',
            'classification' => 'required',
            'other_classification' => 'required_if:classification,119',
            'type' => 'required',
            // 'title_of_extension_program' => '',
            // 'title_of_extension_project' => '',
            // 'title_of_extension_activity' => '',
            'type_of_funding' => 'required',
            'funding_agency' => 'required_if:funding_type,123',
            'currency_amount_of_funding' => 'required',
            'amount_of_funding' => 'numeric',
            'status' => 'required',
            'from' => 'required_unless:status, 107|date',
            'to' => 'date|after_or_equal:from',
            'no_of_trainees_or_beneficiaries' => 'numeric',
            'total_no_of_hours' => 'numeric',
            'classification_of_trainees_or_beneficiaries' => 'required',
            'other_classification_of_trainees' => 'required_if:classification_of_trainees_or_beneficiaries,130',
            // 'place_or_venue' => '',
            'keywords' => 'required',
            'college_id' => 'required',
            // 'department_id' => 'required',
            // 'description' => 'required',
            'quality_poor' => 'numeric',
            'quality_fair' => 'numeric',
            'quality_satisfactory' => 'numeric',
            'quality_very_satisfactory' => 'numeric',
            'quality_outstanding' => 'numeric',
            'timeliness_poor' => 'numeric',
            'timeliness_fair' => 'numeric',
            'timeliness_satisfactory' => 'numeric',
            'timeliness_very_satisfactory' => 'numeric',
            'timeliness_outstanding' => 'numeric',
        ]);

        $input = $request->except(['_token', '_method', 'document', 'other_classification', 'other_classification_of_trainees']);

        $eService = ExtensionService::create($input);
        $eService->update(['user_id' => auth()->id()]);
        $eService->update([
            'other_classification' => $request->input('other_classification'),
            'other_classification_of_trainees' => $request->input('other_classification_of_trainees'),
        ]);
        

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'EService-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExtensionServiceDocument::create([
                        'extension_service_id' => $eService->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Your Accomplishment in Extension Service has been saved.');
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

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extensionServiceDocuments = ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->get()->toArray();
        
        $values = $expert_service_in_academic->toArray();
        
        return view('extension-programs.extension-services.show', compact('extension_service', 'extensionServiceDocuments', 'values'));
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

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");

        $extensionServiceDocuments = ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->get()->toArray();
        
        $colleges = College::all();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$extension_service->department_id.")");

        $value = $extension_service;
        $value->toArray();
        $value = collect($extension_service);
        $value = $value->toArray();
        // dd($value);

        return view('extension-programs.extension-services.edit', compact('value', 'extensionServiceFields', 'extensionServiceDocuments', 'colleges', 'collegeOfDepartment'));
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


        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
      
            $request->validate([
                'level' => 'required',
                'status' => 'required',
                'nature_of_involvement' => 'required',
                'classification' => 'required',
                'other_classification' => 'required_if:classification,119',
                'type' => 'required',
                // 'title_of_extension_program' => '',
                // 'title_of_extension_project' => '',
                // 'title_of_extension_activity' => '',
                'type_of_funding' => 'required',
                'funding_agency' => 'required_if:funding_type,123',
                'currency_amount_of_funding' => 'required',
                'amount_of_funding' => 'numeric',
                'status' => 'required',
                'from' => 'required_unless:status, 107|date',
                'to' => 'date|after_or_equal:from',
                'no_of_trainees_or_beneficiaries' => 'numeric',
                'total_no_of_hours' => 'numeric',
                'classification_of_trainees_or_beneficiaries' => 'required',
                'other_classification_of_trainees' => 'required_if:classification_of_trainees_or_beneficiaries,130',
                // 'place_or_venue' => '',
                'keywords' => 'required',
                'college_id' => 'required',
                // 'department_id' => 'required',
                // 'description' => 'required',
                'quality_poor' => 'numeric',
                'quality_fair' => 'numeric',
                'quality_satisfactory' => 'numeric',
                'quality_very_satisfactory' => 'numeric',
                'quality_outstanding' => 'numeric',
                'timeliness_poor' => 'numeric',
                'timeliness_fair' => 'numeric',
                'timeliness_satisfactory' => 'numeric',
                'timeliness_very_satisfactory' => 'numeric',
                'timeliness_outstanding' => 'numeric',
            ]);
    
            $input = $request->except(['_token', '_method', 'document', 'other_classification', 'other_classification_of_trainees']);
            
            $extension_service->update($input);
            $extension_service->update([
                'other_classification' => $request->input('other_classification'),
                'other_classification_of_trainees' => $request->input('other_classification_of_trainees'),
            ]);


        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'EService-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExtensionServiceDocument::create([
                        'extension_service_id' => $extension_service->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Your accomplishment in Extension Service has been updated.');
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

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extension_service->delete();
        ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->delete();
        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Your accomplishment in Extension Service has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExtensionService::class);

        ExtensionServiceDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);
        return true;
    }
}
