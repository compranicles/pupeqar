<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Employee,
    Reference,
    ReferenceDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\AcademicDevelopmentForm,
    Maintenance\College,
    Maintenance\Quarter,
    Maintenance\Department,
};

class ReferenceController extends Controller
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
        $this->authorize('viewAny', Reference::class);

        $currentQuarterYear = Quarter::find(1);

        $allRtmmi = Reference::where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'references.category')
                                    ->join('colleges', 'colleges.id', 'references.college_id')
                                    ->select('references.*', 'dropdown_options.name as category_name', 'colleges.name as college_name')
                                    ->orderBy('references.updated_at', 'desc')
                                    ->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($allRtmmi as $rtmmi) {
            if (LockController::isLocked($rtmmi->id, 15))
                $submissionStatus[15][$rtmmi->id] = 1;
            else
                $submissionStatus[15][$rtmmi->id] = 0;
            if (empty($reportdata->getDocuments(15, $rtmmi->id)))
                $submissionStatus[15][$rtmmi->id] = 2;
        }

        return view('academic-development.references.index', compact('allRtmmi', 'currentQuarterYear', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $referenceFields = DB::select("CALL get_academic_development_fields_by_form_id(1)");

        $dropdown_options = [];
        foreach($referenceFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        if(session()->get('user_type') == 'Faculty Employee') 
            $colleges = Employee::where('type', 'F')->where('user_id', auth()->id())->pluck('college_id')->all();
        else if(session()->get('user_type') == 'Admin Employee') 
            $colleges = Employee::where('type', 'A')->where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('academic-development.references.create', compact('referenceFields', 'colleges', 'departments', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_started = date("Y-m-d", strtotime($request->input('date_started')));
        $date_completed = date("Y-m-d", strtotime($request->input('date_completed')));
        $date_published = date("Y-m-d", strtotime($request->input('date_published')));

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'date_started' => $date_started,
            'date_completed' => $date_completed,
            'date_published' => $date_published,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $rtmmi = Reference::create($input);
        $rtmmi->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RTMMI-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ReferenceDocument::create([
                        'reference_id' => $rtmmi->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        $accomplished = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $accomplished = collect($accomplished);
        $accomplishment = $accomplished->pluck('name');

        \LogActivity::addToLog('Had added '.$request->input('category').' entitled "'.$request->input('title').'".');


        return redirect()->route('rtmmi.index')->with(['edit_rtmmi_success' => ucfirst($accomplishment[0]), 'action' => 'added.' ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Reference $rtmmi)
    {
        $this->authorize('view', Reference::class);

        if (auth()->id() !== $rtmmi->user_id)
            abort(403);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $referenceDocuments = ReferenceDocument::where('reference_id', $rtmmi->id)->get()->toArray();

        $category = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $referenceFields = DB::select("CALL get_academic_development_fields_by_form_id(1)");

        $values = $rtmmi->toArray();

        foreach($referenceFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $values[$field->name])->pluck('name')->first();
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


        return view('academic-development.references.show', compact('rtmmi', 'referenceDocuments', 'category', 'referenceFields', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Reference $rtmmi)
    {
        $this->authorize('update', Reference::class);

        if (auth()->id() !== $rtmmi->user_id)
            abort(403);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($rtmmi->id, 15)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        $referenceFields = DB::select("CALL get_academic_development_fields_by_form_id(1)");

        $dropdown_options = [];
        foreach($referenceFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $referenceDocuments = ReferenceDocument::where('reference_id', $rtmmi->id)->get()->toArray();

        $category = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        if(session()->get('user_type') == 'Faculty Employee') 
            $colleges = Employee::where('type', 'F')->where('user_id', auth()->id())->pluck('college_id')->all();
        else if(session()->get('user_type') == 'Admin Employee') 
            $colleges = Employee::where('type', 'A')->where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        if ($rtmmi->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$rtmmi->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $value = $rtmmi;
        $value->toArray();
        $value = collect($rtmmi);
        $value = $value->toArray();

        return view('academic-development.references.edit', compact('value', 'referenceFields', 'referenceDocuments', 'colleges', 'category', 'collegeOfDepartment', 'departments', 'dropdown_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request,
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reference $rtmmi)
    {
        $this->authorize('update', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_started = date("Y-m-d", strtotime($request->input('date_started')));
        $date_completed = date("Y-m-d", strtotime($request->input('date_completed')));
        $date_published = date("Y-m-d", strtotime($request->input('date_published')));

        $request->merge([
            'date_started' => $date_started,
            'date_completed' => $date_completed,
            'date_published' => $date_published,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $rtmmi->update(['description' => '-clear']);

        $rtmmi->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RTMMI-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ReferenceDocument::create([
                        'reference_id' => $rtmmi->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        $accomplished = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $accomplished = collect($accomplished);
        $accomplishment = $accomplished->pluck('name');

        \LogActivity::addToLog('Had updated the '.$rtmmi->category.' entitled "'.$rtmmi->title.'".');

        return redirect()->route('rtmmi.index')->with('edit_rtmmi_success', ucfirst($accomplishment[0]))
                                ->with('action', 'updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reference $rtmmi)
    {
        $this->authorize('delete', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($rtmmi->id, 15)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        $rtmmi->delete();
        ReferenceDocument::where('reference_id', $rtmmi->id)->delete();

        $accomplished = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $accomplished = collect($accomplished);
        $accomplishment = $accomplished->pluck('name');

        \LogActivity::addToLog('Had deleted the '.$rtmmi->category.' entitled "'.$rtmmi->title.'".');

        return redirect()->route('rtmmi.index')->with('edit_rtmmi_success', ucfirst($accomplishment[0]))
                            ->with('action', 'deleted.');
    }

    public function removeDoc($filename){

        $this->authorize('delete', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        ReferenceDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);

        \LogActivity::addToLog('Had deleted a document of a reference, textbook, module, monograph, or IM.');

        return true;
    }
}
