<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Models\Report;
use App\Models\Syllabus;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\SyllabusDocument;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Models\FormBuilder\AcademicDevelopmentForm;
use App\Http\Controllers\Maintenances\LockController;

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

        $year = "created";
        $syllabi = Syllabus::where('user_id', auth()->id())
                    ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                    ->join('colleges', 'colleges.id', 'syllabi.college_id')
                    ->select(DB::raw('syllabi.*, dropdown_options.name as assigned_task_name, colleges.name as college_name, QUARTER(syllabi.updated_at) as quarter'))
                    ->orderBy('syllabi.updated_at', 'desc')
                    ->get();
        // dd($syllabi);
        $syllabus_in_colleges = Syllabus::join('colleges', 'syllabi.college_id', 'colleges.id')
                                ->select('colleges.name')
                                ->distinct()
                                ->get();
        
        $syllabiTask = DropdownOption::where('dropdown_id', 39)->get();
        
        $syllabiYearsAdded = Syllabus::selectRaw("YEAR(syllabi.created_at) as created")->where('syllabi.user_id', auth()->id())
                        ->distinct()
                        ->get();
        $syllabiYearsFinished = Syllabus::selectRaw("YEAR(syllabi.date_finished) as finished")->where('syllabi.user_id', auth()->id())
                        ->distinct()
                        ->get();
                        // dd($syllabiYearsFinished);

        return view('academic-development.syllabi.index', compact('syllabi', 'syllabiTask', 'syllabus_in_colleges', 'year', 'syllabiYearsAdded', 'syllabiYearsFinished'));
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

        $colleges = College::all();
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
        
        $request->merge([
            'date_finished' => $date,
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

        \LogActivity::addToLog('Course Syllabus added.');

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

        $colleges = College::all();

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
        
        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
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

        \LogActivity::addToLog('Course Syllabus updated.');

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

        \LogActivity::addToLog('Course Syllabus deleted.');

    }

    public function removeDoc($filename){
        $this->authorize('delete', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        SyllabusDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);

        \LogActivity::addToLog('Course Syllabus document deleted.');

        return true;
    }

    public function syllabusYearFilter($year, $filter) {
        if($filter == "created") {
            if ($year == "created") {
                return redirect()->route('syllabus.index');
            }
            else {
                $syllabi = Syllabus::where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                ->join('colleges', 'colleges.id', 'syllabi.college_id')
                ->whereYear('syllabi.created_at', $year)
                ->select(DB::raw('syllabi.*, dropdown_options.name as assigned_task_name, colleges.name as college_name, QUARTER(syllabi.updated_at) as quarter'))
                ->orderBy('syllabi.updated_at', 'desc')
                ->get();
            }   
        }
        elseif ($filter == "finished") {
            $syllabi = Syllabus::where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                ->join('colleges', 'colleges.id', 'syllabi.college_id')
                ->whereYear('syllabi.date_finished', $year)
                ->select(DB::raw('syllabi.*, dropdown_options.name as assigned_task_name, colleges.name as college_name, QUARTER(syllabi.updated_at) as quarter'))
                ->orderBy('syllabi.updated_at', 'desc')
                ->get();
        }
        else {
            return redirect()->route('syllabus.index');
        }

        $syllabiTask = DropdownOption::where('dropdown_id', 39)->get();
        
        $syllabiYearsAdded = Syllabus::selectRaw("YEAR(syllabi.created_at) as created")->where('syllabi.user_id', auth()->id())
                        ->distinct()
                        ->get();
        $syllabiYearsFinished = Syllabus::selectRaw("YEAR(syllabi.date_finished) as finished")->where('syllabi.user_id', auth()->id())
                        ->distinct()
                        ->get();
        $syllabus_in_colleges = Syllabus::join('colleges', 'syllabi.college_id', 'colleges.id')
                        ->select('colleges.name')
                        ->distinct()
                        ->get();

        return view('academic-development.syllabi.index', compact('syllabi', 'syllabiTask', 'syllabus_in_colleges', 'year', 'syllabiYearsAdded', 'syllabiYearsFinished'));
    }
}
