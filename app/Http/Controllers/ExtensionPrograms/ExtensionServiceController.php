<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtensionService;
use App\Models\ExtensionServiceDocument;
use App\Models\FormBuilder\ExtensionProgramField;
use Illuminate\Support\Facades\DB;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Maintenance\Department;
use App\Models\Maintenance\College;

class ExtensionServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        // $extensionServiceFields1 = ExtensionProgramField::where('extension_program_fields.extension_programs_form_id', 4)
        //                                 ->where('extension_program_fields.is_active', 1)
        //                                 ->whereBetween('extension_program_fields.id', [30, 47])
        //                                 ->join('field_types', 'field_types.id', 'extension_program_fields.field_type_id')
        //                                 ->select('extension_program_fields.*', 'field_types.name as field_type_name')
        //                                 ->orderBy('order')
        //                                 ->get();

        $extensionServiceFields1 = DB::select("CALL get_extension_program_fields_by_form_id_and_field_ids(4, 30, 47)");
        
        $extensionServiceFields2 = DB::select("CALL get_extension_program_fields_by_form_id_and_field_ids(4, 48, 49)"); 

        $departments = Department::all();
        $colleges = College::all();

        return view('extension-programs.extension-services.create', compact('extensionServiceFields1', 'extensionServiceFields2', 'departments', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', '_method', 'document', 'college_id']);

        $eService = ExtensionService::create($input);
        $eService->update(['user_id' => auth()->id()]);

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

        return redirect()->route('faculty.extension-service.index')->with('edit_eservice_success', 'Your Accomplishment in Extension Service has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExtensionService $extension_service)
    {
        $extensionServiceDocuments = ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->get()->toArray();
        $collegeAndDepartment = DB::select("CALL get_college_and_department_by_department_id(".$extension_service->department_id.")");
        $level = DB::select("CALL get_dropdown_name_by_id(".$extension_service->level.")");
        $status = DB::select("CALL get_dropdown_name_by_id(".$extension_service->status.")");
        $nature_of_involvement = DB::select("CALL get_dropdown_name_by_id(".$extension_service->nature_of_involvement.")");
        $classification = DB::select("CALL get_dropdown_name_by_id(".$extension_service->classification.")");
        $type = DB::select("CALL get_dropdown_name_by_id(".$extension_service->type.")");
        $type_of_funding = DB::select("CALL get_dropdown_name_by_id(".$extension_service->type_of_funding.")");
        
        return view('extension-programs.extension-services.show', compact('extension_service', 'extensionServiceDocuments', 'collegeAndDepartment', 'level',
                'status', 'nature_of_involvement', 'classification', 'type', 'type_of_funding'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtensionService $extension_service)
    {
        $extensionServiceFields1 = DB::select("CALL get_extension_program_fields_by_form_id_and_field_ids(4, 30, 47)");
        
        $extensionServiceFields2 = DB::select("CALL get_extension_program_fields_by_form_id_and_field_ids(4, 48, 49)"); 
        
        $extensionServiceDocuments = ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->get()->toArray();
        
        $departments = Department::all();
        $colleges = College::all();

        $collegeOfDepartment = Department::where('departments.id', $extension_service->department_id)
                                ->join('colleges', 'colleges.id', 'departments.college_id')
                                ->select('colleges.id', 'colleges.name')
                                ->first();

        $value = $extension_service;
        $value->toArray();
        $value = collect($extension_service);
        $value = $value->toArray();
        // dd($value);

        return view('extension-programs.extension-services.edit', compact('value', 'extensionServiceFields1', 'extensionServiceFields2', 'extensionServiceDocuments', 'departments', 'colleges', 'collegeOfDepartment'));
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
        $input = $request->except(['_token', '_method', 'document']);

        $extension_service->update($input);

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

        return redirect()->route('faculty.extension-service.index')->with('edit_eservice_success', 'Your accomplishment in Extension Service has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionService $extension_service)
    {
        $extension_service->delete();
        ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->delete();
        return redirect()->route('faculty.extension-service.index')->with('edit_eservice_success', 'Your accomplishment in Extension Service has been deleted.');
    }
}
