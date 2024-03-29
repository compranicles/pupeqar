<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Helpers\LogActivity;
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
    Report,
    Syllabus,
    SyllabusDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\AcademicDevelopmentForm,
    Maintenance\College,
    Maintenance\Quarter,
    Maintenance\Department,
};
use App\Services\CommonService;
use App\Services\DateContentService;
use Exception;

class SyllabusController extends Controller
{
    protected $storageFileController;
    private $commonService;

    public function __construct(StorageFileController $storageFileController, CommonService $commonService){
        $this->storageFileController = $storageFileController;
        $this->commonService = $commonService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Syllabus::class);

        $currentQuarterYear = Quarter::find(1);
        $year = "created";
        $syllabi = Syllabus::where('user_id', auth()->id())
                    ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                    ->join('colleges', 'colleges.id', 'syllabi.college_id')
                    ->select(DB::raw('syllabi.*, dropdown_options.name as assigned_task_name, colleges.name as college_name'))
                    ->orderBy('syllabi.updated_at', 'desc')
                    ->get();

        $syllabiYearsFinished = Syllabus::where('user_id', auth()->id())
                    ->where('syllabi.deleted_at', null)
                    ->selectRaw("YEAR(syllabi.date_finished) as finished")->where('syllabi.user_id', auth()->id())
                    ->distinct()
                    ->get();

        $submissionStatus = array();
        $submitRole = array();
        $reportdata = new ReportDataController;
        foreach ($syllabi as $syllabus) {
            if (LockController::isLocked($syllabus->id, 16)) {
                $submissionStatus[16][$syllabus->id] = 1;
                $submitRole[$syllabus->id] = ReportDataController::getSubmitRole($syllabus->id, 16);
            }
            else
                $submissionStatus[16][$syllabus->id] = 0;
            if (empty($reportdata->getDocuments(16, $syllabus->id)))
                $submissionStatus[16][$syllabus->id] = 2;
        }

        return view('academic-development.syllabi.index', compact('syllabi', 'year',
            'syllabiYearsFinished', 'currentQuarterYear', 'submissionStatus', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Syllabus::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $syllabusFields = DB::select("CALL get_academic_development_fields_by_form_id(2)");

        $dropdown_options = [];
        foreach($syllabusFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();
        return view('academic-development.syllabi.create', compact('syllabusFields', 'colleges', 'departments' , 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = (new DateContentService())->checkDateContent($request, "date_finished");
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'date_finished' => $date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $syllabus = Syllabus::create($input);
        $syllabus->update(['user_id' => auth()->id()]);

        LogActivity::addToLog('Had added a course syllabus "'.$request->input('course_title').'".');

        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), 'S-', 'syllabus.index');
                if(is_string($fileName)) SyllabusDocument::create(['syllabus_id' => $syllabus->id, 'filename' => $fileName]);
                else return $fileName;
            }
        }

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'Course syllabus')
                                ->with('action', 'added.');

        // if($request->has('document')){
        //     try {
        //         $documents = $request->input('document');
        //         foreach($documents as $document){
        //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //             dd($temporaryFile);
        //             if($temporaryFile){
        //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //                 $ext = $info['extension'];
        //                 $fileName = 'S-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();
        //                 SyllabusDocument::create([
        //                     'syllabus_id' => $syllabus->id,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     } catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     }
        // }
    }

    /**
     * Display the specified resource.p
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Syllabus $syllabu)
    {
        $this->authorize('view', Syllabus::class);

        if (auth()->id() !== $syllabu->user_id) {
            abort(403);
        }

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $syllabusDocuments = SyllabusDocument::where('syllabus_id', $syllabu->id)->get()->toArray();
        $syllabusFields = DB::select("CALL get_academic_development_fields_by_form_id(2)");
        $values = $syllabu->toArray();

        foreach($syllabusFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $values[$field->name])->where('is_active', 1)->pluck('name')->first();
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

        return view('academic-development.syllabi.show', compact('syllabu', 'syllabusDocuments', 'syllabusFields', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Syllabus $syllabu)
    {
        $this->authorize('update', Syllabus::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if (auth()->id() !== $syllabu->user_id) {
            abort(403);
        }

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($syllabu->id, 16)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        $syllabusFields = DB::select("CALL get_academic_development_fields_by_form_id(2)");

        $dropdown_options = [];
        foreach($syllabusFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $syllabusDocuments = SyllabusDocument::where('syllabus_id', $syllabu->id)->get()->toArray();

        $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        if ($syllabu->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$syllabu->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $value = collect($syllabu);
        $value = $value->toArray();

        return view('academic-development.syllabi.edit', compact('value', 'syllabusFields', 'syllabusDocuments', 'colleges', 'collegeOfDepartment', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Syllabus $syllabu)
    {
        $this->authorize('update', Syllabus::class);
        $currentQuarterYear = Quarter::find(1);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = (new DateContentService())->checkDateContent($request, "date_finished");

        $request->merge([
            'date_finished' => $date,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $request->except(['_token', '_method', 'document']);
        $syllabu->update(['description' => '-clear']);
        $syllabu->update($input);

        LogActivity::addToLog('Had updated the course syllabus "'.$syllabu->course_title.'".');

        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), 'S-', 'syllabus.index');
                if(is_string($fileName)) SyllabusDocument::create(['syllabus_id' => $syllabu->id, 'filename' => $fileName]);
                else return $fileName;
            }
        }

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'Course syllabus')
                                    ->with('action', 'updated.');

        // if($request->has('document')){
        //     try {
        //         $documents = $request->input('document');
        //         foreach($documents as $document){
        //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //             if($temporaryFile){
        //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //                 $ext = $info['extension'];
        //                 $fileName = 'S-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();
        //                 SyllabusDocument::create([
        //                     'syllabus_id' => $syllabu->id,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     } catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     }
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Syllabus $syllabu)
    {
        $this->authorize('delete', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($syllabu->id, 16)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        $syllabu->delete();
        SyllabusDocument::where('syllabus_id', $syllabu->id)->delete();

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'Course syllabus')
                                ->with('action', 'deleted.');

        LogActivity::addToLog('Had deleted the course syllabus "'.$syllabu->course_title.'".');

    }

    public function removeDoc($filename){
        $this->authorize('delete', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        SyllabusDocument::where('filename', $filename)->delete();

        LogActivity::addToLog('Had deleted a document of a course syllabus.');

        return true;
    }

    public function syllabusYearFilter($year, $filter) {
        $this->authorize('viewAny', Syllabus::class);

        if ($year == "finished") {
            return redirect()->route('syllabus.index');
        }

        $currentQuarterYear = Quarter::find(1);

        if ($filter == "finished") {
            $syllabi = Syllabus::where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                ->join('colleges', 'colleges.id', 'syllabi.college_id')
                ->whereYear('syllabi.date_finished', $year)
                ->select(DB::raw('syllabi.*, dropdown_options.name as assigned_task_name, colleges.name as college_name'))
                ->orderBy('syllabi.updated_at', 'desc')
                ->get();
        }
        else {
            return redirect()->route('syllabus.index');
        }

        $syllabiTask = DropdownOption::where('dropdown_id', 39)->get();

        $syllabiYearsAdded = Syllabus::where('user_id', auth()->id())
                        ->where('syllabi.deleted_at', null)
                        ->selectRaw("YEAR(syllabi.created_at) as created")->where('syllabi.user_id', auth()->id())
                        ->distinct()
                        ->get();
        $syllabiYearsFinished = Syllabus::where('user_id', auth()->id())
                        ->where('syllabi.deleted_at', null)
                        ->selectRaw("YEAR(syllabi.date_finished) as finished")->where('syllabi.user_id', auth()->id())
                        ->distinct()
                        ->get();
        return view('academic-development.syllabi.index', compact('syllabi', 'syllabiTask', 'year', 'syllabiYearsAdded', 'syllabiYearsFinished', 'currentQuarterYear'));
    }
}
