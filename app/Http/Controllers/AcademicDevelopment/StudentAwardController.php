<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Models\Dean;
use App\Models\Chairperson;
use App\Models\StudentAward;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\StudentAwardDocument;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\StorageFileController;
use App\Models\FormBuilder\AcademicDevelopmentForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class StudentAwardController extends Controller
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
        $this->authorize('viewAny', StudentAward::class);
        $currentQuarterYear = Quarter::find(1);

        $student_awards = StudentAward::where('user_id', auth()->id())
                            ->orderBy('student_awards.updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($student_awards as $student_award) {
            if (LockController::isLocked($student_award->id, 18))
                $submissionStatus[18][$student_award->id] = 1;
            else
                $submissionStatus[18][$student_award->id] = 0;
            if (empty($reportdata->getDocuments(18, $student_award->id)))
                $submissionStatus[18][$student_award->id] = 2;
        }

        return view('academic-development.student-awards.index', compact('student_awards', 'currentQuarterYear',
            'submissionStatus'));
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

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('academic-development.student-awards.create', compact('studentFields', 'colleges'));
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

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'date' => $date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

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
                    $fileName = 'SA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Had added a student award and recognition "'.$request->input('name_of_award').'".');

        return redirect()->route('student-award.index')->with('student_success', 'Student award and recognition has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAward $stdnt_award)
    {
		$student_award = $stdnt_award;
        $this->authorize('view', StudentAward::class);

        if (auth()->id() !== $student_award->user_id)
            abort(403);

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
    public function edit(StudentAward $stdnt_award)
    {
		$student_award = $stdnt_award;
        $this->authorize('update', StudentAward::class);

        if (auth()->id() !== $student_award->user_id)
            abort(403);

        if(AcademicDevelopmentForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($student_award->id, 18)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        $studentFields = DB::select("CALL get_academic_development_fields_by_form_id(3)");

        $documents = StudentAwardDocument::where('student_award_id', $student_award->id)->get()->toArray();

        $values = $student_award->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                ->select('colleges.*')->get();

        return view('academic-development.student-awards.edit', compact('studentFields', 'student_award', 'documents', 'values', 'colleges'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAward $stdnt_award)
    {
		$student_award = $stdnt_award;
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

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'SA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Had updated the student award and recognition "'.$student_award->name_of_award.'".');

        return redirect()->route('student-award.index')->with('student_success', 'Student award and recognition has been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAward $stdnt_award)
    {
		$student_award = $stdnt_award;
        $this->authorize('delete', StudentAward::class);

        if(LockController::isLocked($student_award->id, 18)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        StudentAwardDocument::where('student_award_id', $student_award->id)->delete();
        $student_award->delete();

        \LogActivity::addToLog('Had deleted the student award and recognition "'.$student_award->name_of_award.'".');

        return redirect()->route('student-award.index')->with('student_success', 'Student award and recognition has been saved.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', StudentAward::class);

        StudentAwardDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a student award and recognition.');

        return true;
    }
}
