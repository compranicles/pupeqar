<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Rules\Keyword;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ExtensionService;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\ExtensionServiceDocument;
use App\Models\FormBuilder\DropdownOption;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Models\FormBuilder\ExtensionProgramField;
use App\Http\Controllers\Maintenances\LockController;

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

        $status = DropdownOption::where('dropdown_id', 24)->get();

        $extensionServices = ExtensionService::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'extension_services.status')
                                        ->join('colleges', 'colleges.id', 'extension_services.college_id')
                                        ->select(DB::raw('extension_services.*, dropdown_options.name as status, colleges.name as college_name, QUARTER(extension_services.updated_at) as quarter'))
                                        ->orderBy('extension_services.updated_at', 'desc')
                                        ->get();

        $eservice_in_colleges = ExtensionService::join('colleges', 'extension_services.college_id', 'colleges.id')
                                        ->select('colleges.name')
                                        ->distinct()
                                        ->get();

        return view('extension-programs.extension-services.index', compact('extensionServices', 'eservice_in_colleges', 'status'));
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

        $value = $request->input('amount_of_funding');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'amount_of_funding' => $value,
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->report_quarter,
            'report_year' => $currentQuarterYear->report_year,
        ]);

        $request->validate([
            'keywords' => new Keyword,
            'college_id' => 'required',
            'department_id' => 'required'
        ]);
        
        if ($request->input('total_no_of_hours') != '') {
            $request->validate([
                'total_no_of_hours' => 'numeric',
            ]);
        }

        $input = $request->except(['_token', '_method', 'document', 'other_classification', 'other_classification_of_trainees']);

        $eService = ExtensionService::create($input);
        $eService->update(['user_id' => auth()->id()]);
        $eService->update([
            'other_classification' => $request->input('other_classification'),
            'other_classification_of_trainees' => $request->input('other_classification_of_trainees'),
        ]);
        
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
                    $fileName = 'EService-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Extension service was added.');


        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Extension service has been added.');
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
        
        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");
        $extensionServiceDocuments = ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->get()->toArray();
        
        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id('4')");
        
        $values = $extension_service->toArray();
        
        // dd($extensionServiceFields);
        return view('extension-programs.extension-services.show', compact('extension_service', 'extensionServiceDocuments', 'values', 'extensionServiceFields'));
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

        if(LockController::isLocked($extension_service->id, 12)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extensionServiceFields = DB::select("CALL get_extension_program_fields_by_form_id(4)");

        $extensionServiceDocuments = ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->get()->toArray();
        
        $colleges = College::all();

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
      
            $value = $request->input('amount_of_funding');
            $value = (float) str_replace(",", "", $value);
            $value = number_format($value,2,'.','');

            $from = date("Y-m-d", strtotime($request->input('from')));
            $to = date("Y-m-d", strtotime($request->input('to')));
    
            $request->merge([
                'amount_of_funding' => $value,
                'from' => $from,
                'to' => $to,
            ]);

            $request->validate([
                'keywords' => new Keyword,
                'college_id' => 'required',
                'department_id' => 'required'
            ]);

            if ($request->input('total_no_of_hours') != '') {
                $request->validate([
                    'total_no_of_hours' => 'numeric',
                ]);
            }
    
            $input = $request->except(['_token', '_method', 'document', 'other_classification', 'other_classification_of_trainees']);
            
            $extension_service->update(['description' => '-clear']);

            $extension_service->update($input);
            $extension_service->update([
                'other_classification' => $request->input('other_classification'),
                'other_classification_of_trainees' => $request->input('other_classification_of_trainees'),
            ]);

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
                    $fileName = 'EService-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Extension service was updated.');

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Extension service has been updated.');
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
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(ExtensionProgramForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extension_service->delete();
        ExtensionServiceDocument::where('extension_service_id', $extension_service->id)->delete();

        \LogActivity::addToLog('Extension service was deleted.');

        return redirect()->route('extension-service.index')->with('edit_eservice_success', 'Extension service has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExtensionService::class);

        ExtensionServiceDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Extension service document was deleted.');

        // Storage::delete('documents/'.$filename);
        return true;
    }
}
