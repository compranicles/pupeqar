<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Models\Syllabus;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\SyllabusDocument;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\AcademicDevelopmentForm;

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

        $syllabi = Syllabus::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'syllabi.assigned_task')
                                        ->select('syllabi.*', 'dropdown_options.name as assigned_task_name')
                                        ->get();
        
        return view('academic-development.syllabi.index', compact('syllabi'));
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
      
        $request->validate([
            'course_title' => 'required',
            'assigned_task' => 'required',
            'date_finished' => 'required|date',
            'college_id' => 'required',
            'department_id' => 'required',
            // 'description' => 'required',
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
                    $fileName = 'Syllabus-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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
        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'course syllabus')
                                ->with('action', 'saved.');
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

        $collegeAndDepartment = DB::select("CALL get_college_and_department_by_department_id(".$syllabu->department_id.")");

        $assigned_task = DB::select("CALL get_dropdown_name_by_id(".$syllabu->assigned_task.")");
        
        return view('academic-development.syllabi.show', compact('syllabu', 'syllabusDocuments', 'assigned_task', 'collegeAndDepartment'));
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
        $syllabusFields = DB::select("CALL get_academic_development_fields_by_form_id(2)");

        $syllabusDocuments = SyllabusDocument::where('syllabus_id', $syllabu->id)->get()->toArray();

        $colleges = College::all();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$syllabu->department_id.")");
        // dd($collegeOfDepartment);
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
        $request->validate([
            'course_title' => 'required',
            'assigned_task' => 'required',
            'date_finished' => 'required|date',
            'college_id' => 'required',
            'department_id' => 'required',
            // 'description' => '',
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $syllabu->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'Syllabus-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'course syllabus')
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
        $syllabu->delete();
        SyllabusDocument::where('syllabus_id', $syllabu->id)->delete();

        return redirect()->route('syllabus.index')->with('edit_syllabus_success', 'course syllabus')
                                ->with('action', 'deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', Syllabus::class);

        if(AcademicDevelopmentForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        SyllabusDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);
        return true;
    }
}
