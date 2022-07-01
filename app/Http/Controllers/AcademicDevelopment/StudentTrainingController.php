<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Models\Dean;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\StudentTraining;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\StudentTrainingDocument;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\StorageFileController;
use App\Models\FormBuilder\AcademicDevelopmentForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class StudentTrainingController extends Controller
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
        $this->authorize('viewAny', StudentTraining::class);
        $currentQuarterYear = Quarter::find(1);

        $student_trainings = StudentTraining::where('user_id', auth()->id())
                        ->orderBy('student_trainings.updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($student_trainings as $student_training) {
            if (LockController::isLocked($student_training->id, 19))
                $submissionStatus[19][$student_training->id] = 1;
            else
                $submissionStatus[19][$student_training->id] = 0;
            if (empty($reportdata->getDocuments(19, $student_training->id)))
                $submissionStatus[19][$student_training->id] = 2;
        }

        return view('academic-development.student-training.index', compact('student_trainings', 'currentQuarterYear',
            'submissionStatus'));
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

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('academic-development.student-training.create', compact('studentFields', 'colleges'));
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

        $start_date = date("Y-m-d", strtotime($request->input('start_date')));
        $end_date = date("Y-m-d", strtotime($request->input('end_date')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'budget' => $value,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
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

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ST-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Had added a student attended seminar and training "'.$request->input('title').'".');

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudentTraining  $stdnt_training)
    {
		$student_training = $stdnt_training;
        $this->authorize('view', StudentTraining::class);

        if (auth()->id() !== $student_training->user_id)
            abort(403);

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
    public function edit(StudentTraining $stdnt_training)
    {
		$student_training = $stdnt_training;
        $this->authorize('update', StudentTraining::class);

        if (auth()->id() !== $student_training->user_id)
            abort(403);

        if(LockController::isLocked($student_training->id, 19)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(4)");

        $documents = StudentTrainingDocument::where('student_training_id', $student_training->id)->get()->toArray();

        $values = $student_training->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('academic-development.student-training.edit', compact('studentFields', 'student_training', 'documents', 'values', 'colleges'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentTraining $stdnt_training)
    {
		$student_training = $stdnt_training;
        $this->authorize('update', StudentTraining::class);

        $value = $request->input('budget');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $start_date = date("Y-m-d", strtotime($request->input('start_date')));
        $end_date = date("Y-m-d", strtotime($request->input('end_date')));

        $request->merge([
            'budget' => $value,
            'start_date' => $start_date,
            'end_date' => $end_date,
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

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ST-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Had updated the student attended seminar and training "'.$student_training->title.'".');

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentTraining  $stdnt_training)
    {
		$student_training = $stdnt_training;
        $this->authorize('delete', StudentTraining::class);

        if(LockController::isLocked($student_training->id, 19)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        StudentTrainingDocument::where('student_training_id', $student_training->id)->delete();
        $student_training->delete();

        \LogActivity::addToLog('Had deleted the student attended seminar and training "'.$student_training->title.'".');

        return redirect()->route('student-training.index')->with('student_success', 'Student attended seminar and training has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', StudentTraining::class);

        if(AcademicDevelopmentForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        StudentTrainingDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a student attended seminar and training.');

        return true;
    }
}
