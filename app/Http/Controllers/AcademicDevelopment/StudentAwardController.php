<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Models\StudentAward;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StudentAwardDocument;
use Illuminate\Support\Facades\Storage;

class StudentAwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student_awards = StudentAward::where('user_id', auth()->id())->get();
        return view('academic-development.student-awards.index', compact('student_awards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(3)");

        return view('academic-development.student-awards.create', compact('studentFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $student_award = StudentAward::create($input);
        $student_award->update(['user_id' => auth()->id()]);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'StudentAward-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    StudentAwardDocument::create([
                        'student_award_id' => $student_award->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('student-award.index')->with('student_success', 'Your Accomplishment in Student Award and Recognition has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAward $student_award)
    {
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(3)");

        $documents = StudentAwardDocument::where('student_award_id', $student_award->id)->get()->toArray();

        $values = $student_award->toArray();

        return view('academic-development.student-awards.show', compact('studentFields', 'student_award', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentAward $student_award)
    {
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(3)");

        $documents = StudentAwardDocument::where('student_award_id', $student_award->id)->get()->toArray();

        $values = $student_award->toArray();

        return view('academic-development.student-awards.edit', compact('studentFields', 'student_award', 'documents', 'values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAward $student_award)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $student_award->update($input);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'StudentAward-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    StudentAwardDocument::create([
                        'student_award_id' => $student_award->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('student-award.index')->with('student_success', 'Your Accomplishment in Student Award and Recognition has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAward $student_award)
    {
        StudentAwardDocument::where('student_award_id', $student_award->id)->delete();
        $student_award->delete();
        return redirect()->route('student-award.index')->with('student_success', 'Your accomplishment in Student Award and Recognition has been deleted.');
    }

    public function removeDoc($filename){
        StudentAwardDocument::where('filename', $filename)->delete();
        return true;
    }
}
