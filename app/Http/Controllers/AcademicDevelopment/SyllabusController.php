<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
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

class SyllabusController extends Controller
{
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

        return view('academic-development.syllabi.index', compact('syllabi', 'year', 'syllabiYearsFinished', 'currentQuarterYear'));
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

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        return view('academic-development.syllabi.create', compact('syllabusFields', 'colleges'));
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

        $date = date("Y-m-d", strtotime($request->input('date_finished')));
        $currentQuarterYear = Quarter::find(1);
        
        $request->merge([
            'date_finished' => $date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
        ]);
        $input = $request->except(['_token', '_method', 'document']);

        $syllabus = Syllabus::create($input);
        $syllabus->update(['user_id' => auth()->id()]);

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
                    $fileName = 'Syllabus-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Course Syllabus "'.$request->input('course_title').'" was added.');

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

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($syllabu->id, 16)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        $syllabusFields = DB::select("CALL get_academic_development_fields_by_form_id(2)");

        $syllabusDocuments = SyllabusDocument::where('syllabus_id', $syllabu->id)->get()->toArray();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        if ($syllabu->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$syllabu->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $value = $syllabu;
        $value->toArray();
        $value = collect($syllabu);
        $value = $value->toArray();
        
        return view('academic-development.syllabi.edit', compact('value', 'syllabusFields', 'syllabusDocuments', 'colleges', 'collegeOfDepartment'));
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

        $date = date("Y-m-d", strtotime($request->input('date_finished')));
        
        $request->merge([
            'date_finished' => $date,
        ]);
        
        $input = $request->except(['_token', '_method', 'document']);
        $syllabu->update(['description' => '-clear']);
        $syllabu->update($input);

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
                    $fileName = 'Syllabus-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Course Syllabus "'.$syllabu->course_title.'" was updated.');

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
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        $syllabu->delete();
        SyllabusDocument::where('syllabus_id', $syllabu->id)->delete();

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'Course syllabus')
                                ->with('action', 'deleted.');

        \LogActivity::addToLog('Course Syllabus "'.$syllabu->course_title.'" was deleted.');

    }

    public function removeDoc($filename){
        $this->authorize('delete', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        SyllabusDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Course Syllabus document was deleted.');

        return true;
    }

    public function syllabusYearFilter($year, $filter) {
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
