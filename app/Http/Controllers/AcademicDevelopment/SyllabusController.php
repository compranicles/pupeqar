<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Syllabus;
use Illuminate\Support\Facades\DB;
use App\Models\SyllabusDocument;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Department;
use App\Models\FormBuilder\DropdownOption;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $syllabusFields1 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(2, 15, 17)");
        
        $syllabusFields2 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(2, 18, 19)");
        
        $departments = Department::all();
        $colleges = College::all();
        return view('academic-development.syllabi.create', compact('syllabusFields1', 'syllabusFields2', 'colleges', 'departments'));
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
        return redirect()->route('faculty.syllabus.index')->with('edit_syllabus_success', 'course syllabus')
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
        $syllabusFields1 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(2, 15, 17)");
        
        $syllabusFields2 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(2, 18, 19)");

        $syllabusDocuments = SyllabusDocument::where('syllabus_id', $syllabu->id)->get()->toArray();

        $departments = Department::all();
        $colleges = College::all();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$syllabu->department_id.")");

        $value = $syllabu;
        $value->toArray();
        $value = collect($syllabu);
        $value = $value->toArray();
        
        return view('academic-development.syllabi.edit', compact('value', 'syllabusFields1', 'syllabusFields2', 'syllabusDocuments', 'colleges', 'departments', 'collegeOfDepartment'));
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
        $input = $request->except(['_token', '_method', 'document', 'college_id']);

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

        return redirect()->route('faculty.syllabus.index')->with('edit_syllabus_success', 'course syllabus')
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
        $syllabu->delete();
        SyllabusDocument::where('syllabus_id', $syllabu->id)->delete();

        return redirect()->route('faculty.syllabus.index')->with('edit_syllabus_success', 'course syllabus')
                                ->with('action', 'deleted.');
    }
}