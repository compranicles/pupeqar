<?php

namespace App\Http\Controllers\AcademicDevelopment;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\StudentTraining;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StudentTrainingDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\AcademicDevelopmentForm;

class StudentTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', StudentTraining::class);

        $student_trainings = StudentTraining::where('user_id', auth()->id())->orderBy('student_trainings.updated_at', 'desc')->get();

        return view('academic-development.student-training.index', compact('student_trainings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(4)");

        return view('academic-development.student-training.create', compact('studentFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', StudentTraining::class);

        $value = $request->input('budget');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $request->merge([
            'budget' => $value,
        ]);

        $request->validate([
            // 'budget' => 'numeric',
            'end_date' => 'after_or_equal:start_date',
            'total_hours' => 'numeric',
        ]);
        
        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);
        // dd($input);

        $student_training = StudentTraining::create($input);
        $student_training->update(['user_id' => auth()->id()]);

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
                    $fileName = 'StudentTraining-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    StudentTrainingDocument::create([
                        'student_training_id' => $student_training->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudentTraining $student_training)
    {
        $this->authorize('view', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(4)");

        $documents = StudentTrainingDocument::where('student_training_id', $student_training->id)->get()->toArray();

        $values = $student_training->toArray();

        return view('academic-development.student-training.show', compact('studentFields', 'student_training', 'documents', 'values'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentTraining $student_training)
    {
        $this->authorize('update', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(4)");

        $documents = StudentTrainingDocument::where('student_training_id', $student_training->id)->get()->toArray();

        $values = $student_training->toArray();

        return view('academic-development.student-training.edit', compact('studentFields', 'student_training', 'documents', 'values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentTraining $student_training)
    {
        $this->authorize('update', StudentTraining::class);

        $value = $request->input('budget');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $request->merge([
            'budget' => $value,
        ]);
        
        $request->validate([
            // 'budget' => 'numeric',
            'end_date' => 'after_or_equal:start_date',
            'total_hours' => 'numeric',
        ]);
        
        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);


        $student_training->update(['description' => '-clear']);

        $student_training->update($input);

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
                    $fileName = 'StudentTraining-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    StudentTrainingDocument::create([
                        'student_training_id' => $student_training->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentTraining $student_training)
    {
        $this->authorize('delete', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        StudentTrainingDocument::where('student_training_id', $student_training->id)->delete();
        $student_training->delete();
        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        StudentTrainingDocument::where('filename', $filename)->delete();
        return true;
    }
}
