<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\ExpertServiceAcademic;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Models\ExpertServiceAcademicDocument;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Models\FormBuilder\ExtensionProgramField;
use App\Http\Controllers\Maintenances\LockController;

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

        $classifications = DropdownOption::where('dropdown_id', 19)->get();

        $expertServicesAcademic = ExpertServiceAcademic::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_academics.classification')
                                        ->join('colleges', 'colleges.id', 'expert_service_academics.college_id')
                                        ->select(DB::raw('expert_service_academics.*, dropdown_options.name as classification, colleges.name as college_name, QUARTER(expert_service_academics.updated_at) as quarter'))
                                        ->orderBy('expert_service_academics.updated_at', 'desc')
                                        ->get();

        $esacademic_in_colleges = ExpertServiceAcademic::join('colleges', 'expert_service_academics.college_id', 'colleges.id')
                                        ->select('colleges.name')
                                        ->distinct()
                                        ->get();

        return view('extension-programs.expert-services.academic.index', compact('expertServicesAcademic', 'esacademic_in_colleges', 'classifications'));
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

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->report_quarter,
            'report_year' => $currentQuarterYear->report_year,
        ]);

        $request->validate([
            'other_nature' => 'required_if:nature,86',
            'to' => 'after_or_equal:from',
            'copyright_no' => 'max:100',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document', 'other_nature']);

        $esAcademic = ExpertServiceAcademic::create($input);
        $esAcademic->update(['user_id' => auth()->id()]);
        $esAcademic->update(['other_nature' => $request->input('other_nature')]);

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
                    $fileName = 'ESAcademic-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        $classification = DB::select("CALL get_dropdown_name_by_id($esAcademic->classification)");

        \LogActivity::addToLog('Expert service rendered in academic '.strtolower($classification[0]->name).' was added.');


        return redirect()->route('expert-service-in-academic.index')->with('edit_esacademic_success', 'Expert service rendered in academic '.strtolower($classification[0]->name).' has been added.');
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

        if(LockController::isLocked($expert_service_in_academic->id, 11)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceAcademicFields = DB::select("CALL get_extension_program_fields_by_form_id(3)");

        $expertServiceAcademicDocuments = ExpertServiceAcademicDocument::where('expert_service_academic_id', $expert_service_in_academic->id)->get()->toArray();
        
        $colleges = College::all();

        if ($expert_service_in_academic->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$expert_service_in_academic->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }
      
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

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));

        $request->merge([
            'from' => $from,
            'to' => $to,
        ]);

        $request->validate([
            'other_nature' => 'required_if:nature,86',
            'to' => 'after_or_equal:from',
            'copyright_no' => 'max:100',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document', 'other_nature']);
        
        $expert_service_in_academic->update(['description' => '-clear']);

        $expert_service_in_academic->update($input);
        $expert_service_in_academic->update(['other_nature' => $request->input('other_nature')]);

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
                    $fileName = 'ESAcademic-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        $classification = DB::select("CALL get_dropdown_name_by_id($expert_service_in_academic->classification)");

        \LogActivity::addToLog('Expert service rendered in academic '.strtolower($classification[0]->name).' was updated.');


        return redirect()->route('expert-service-in-academic.index')->with('edit_esacademic_success', 'Expert service rendered in academic '.strtolower($classification[0]->name).' has been updated.');
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

        if(LockController::isLocked($expert_service_in_academic->id, 11)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expert_service_in_academic->delete();
        $classification = DB::select("CALL get_dropdown_name_by_id($expert_service_in_academic->classification)");
        ExpertServiceAcademicDocument::where('expert_service_academic_id', $expert_service_in_academic->id)->delete();

        \LogActivity::addToLog('Expert service rendered in academic '.strtolower($classification[0]->name).' was deleted.');

        return redirect()->route('expert-service-in-academic.index')->with('edit_esacademic_success', 'Expert service rendered in academic '.strtolower($classification[0]->name).' has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExpertServiceAcademic::class);

        if(ExtensionProgramForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        ExpertServiceAcademicDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);

        \LogActivity::addToLog('Expert service rendered in academic document was deleted.');

        return true;
    }
}
