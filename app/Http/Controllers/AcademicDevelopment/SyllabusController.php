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
use App\Services\DateContentService;

class SyllabusController extends Controller
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

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($syllabi as $syllabus) {
            if (LockController::isLocked($syllabus->id, 16))
                $submissionStatus[16][$syllabus->id] = 1;
            else
                $submissionStatus[16][$syllabus->id] = 0;
            if (empty($reportdata->getDocuments(16, $syllabus->id)))
                $submissionStatus[16][$syllabus->id] = 2;
        }

        return view('academic-development.syllabi.index', compact('syllabi', 'year',
            'syllabiYearsFinished', 'currentQuarterYear', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $syllabusFields = DB::select("CALL get_academic_development_fields_by_form_id(2)");

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();
        return view('academic-development.syllabi.create', compact('syllabusFields', 'colleges', 'departments'));
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

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
        ]);
        $input = $request->except(['_token', '_method', 'document']);

        $syllabus = Syllabus::create($input);
        $syllabus->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'S-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    SyllabusDocument::create([
                        'syllabus_id' => $syllabus->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a course syllabus "'.$request->input('course_title').'".');

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'Course syllabus')
                                ->with('action', 'added.');
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

        if (auth()->id() !== $syllabu->user_id) {
            abort(403);
        }

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($syllabu->id, 16)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        $syllabusFields = DB::select("CALL get_academic_development_fields_by_form_id(2)");

        $syllabusDocuments = SyllabusDocument::where('syllabus_id', $syllabu->id)->get()->toArray();

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        if ($syllabu->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$syllabu->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $value = collect($syllabu);
        $value = $value->toArray();

        return view('academic-development.syllabi.edit', compact('value', 'syllabusFields', 'syllabusDocuments', 'colleges', 'collegeOfDepartment', 'departments'));
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

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = (new DateContentService())->checkDateContent($request, "date_finished");

        $request->merge([
            'date_finished' => $date,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'document']);
        $syllabu->update(['description' => '-clear']);
        $syllabu->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'S-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    SyllabusDocument::create([
                        'syllabus_id' => $syllabu->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated the course syllabus "'.$syllabu->course_title.'".');

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'Course syllabus')
                                    ->with('action', 'updated.');
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

        \LogActivity::addToLog('Had deleted the course syllabus "'.$syllabu->course_title.'".');

    }

    public function removeDoc($filename){
        $this->authorize('delete', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        SyllabusDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a course syllabus.');

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
