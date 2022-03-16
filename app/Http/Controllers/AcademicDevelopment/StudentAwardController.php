<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Models\StudentAward;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StudentAwardDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\AcademicDevelopmentForm;
use App\Http\Controllers\Maintenances\LockController;

class StudentAwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', StudentAward::class);

        $student_awards = StudentAward::where('user_id', auth()->id())
                            ->select(DB::raw('student_awards.*, QUARTER(student_awards.updated_at) as quarter'))
                            ->orderBy('student_awards.updated_at', 'desc')->get();
        return view('academic-development.student-awards.index', compact('student_awards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', StudentAward::class);

        if(AcademicDevelopmentForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
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
        $this->authorize('create', StudentAward::class);

        if(AcademicDevelopmentForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = date("Y-m-d", strtotime($request->input('date')));
        
        $request->merge([
            'date' => $date,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $student_award = StudentAward::create($input);
        $student_award->update(['user_id' => auth()->id()]);

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
                    $fileName = 'StudentAward-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Student award and recognition "'.$request->input('name_of_award').'" was added.');

        return redirect()->route('student-award.index')->with('student_success', 'Student award and recognition has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAward $student_award)
    {
        $this->authorize('view', StudentAward::class);

        if(AcademicDevelopmentForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(3)");

        $documents = StudentAwardDocument::where('student_award_id', $student_award->id)->get()->toArray();

        $values = $student_award->toArray();

        return view('academic-development.student-awards.show', compact('student_award', 'documents', 'values', 'studentFields'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentAward $student_award)
    {
        $this->authorize('update', StudentAward::class);

        if(AcademicDevelopmentForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($student_award->id, 18)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

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
        $this->authorize('update', StudentAward::class);
        
        if(AcademicDevelopmentForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        
        $date = date("Y-m-d", strtotime($request->input('date')));
        
        $request->merge([
            'date' => $date,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $student_award->update(['description' => '-clear']);

        $student_award->update($input);

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
                    $fileName = 'StudentAward-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Student award and recognition "'.$student_award->name_of_award.'" was updated.');

        return redirect()->route('student-award.index')->with('student_success', 'Student award and recognition has been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAward $student_award)
    {
        $this->authorize('delete', StudentAward::class);

        if(LockController::isLocked($student_award->id, 18)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        StudentAwardDocument::where('student_award_id', $student_award->id)->delete();
        $student_award->delete();

        \LogActivity::addToLog('Student award and recognition "'.$student_award->name_of_award.'" was deleted.');

        return redirect()->route('student-award.index')->with('student_success', 'Student award and recognition has been saved.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', StudentAward::class);

        StudentAwardDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Student award and recognition document was deleted.');

        return true;
    }
}
