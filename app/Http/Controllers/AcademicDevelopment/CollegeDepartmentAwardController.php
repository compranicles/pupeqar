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
    Chairperson,
    Dean,
    CollegeDepartmentAward,
    CollegeDepartmentAwardDocument,
    TemporaryFile,
    FormBuilder\AcademicDevelopmentForm,
    FormBuilder\DropdownOption,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};
use App\Services\DateContentService;

class CollegeDepartmentAwardController extends Controller
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
        $this->authorize('viewAny', CollegeDepartmentAward::class);

        $currentQuarterYear = Quarter::find(1);
        $college_department_awards = CollegeDepartmentAward::where('user_id', auth()->id())
                                    ->join('colleges', 'colleges.id', 'college_department_awards.college_id')
                                    ->orderBy('college_department_awards.updated_at', 'desc')
                                    ->select('college_department_awards.*', 'colleges.name as college_name')
                                    ->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($college_department_awards as $college_department_award) {
            if (LockController::isLocked($college_department_award->id, 21))
                $submissionStatus[21][$college_department_award->id] = 1;
            else
                $submissionStatus[21][$college_department_award->id] = 0;
            if (empty($reportdata->getDocuments(21, $college_department_award->id)))
                $submissionStatus[21][$college_department_award->id] = 2;
        }

        return view('academic-development.college-department-award.index', compact('college_department_awards', 'currentQuarterYear', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $awardFields = DB::select("CALL get_academic_development_fields_by_form_id(6)");

        $dropdown_options = [];
        foreach($awardFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('academic-development.college-department-award.create', compact('awardFields', 'colleges', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = (new DateContentService())->checkDateContent($request, "date");

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'date' => $date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $college_department_award = CollegeDepartmentAward::create($input);
        $college_department_award->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'CDA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    CollegeDepartmentAwardDocument::create([
                        'college_department_award_id' => $college_department_award->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added an award and recognition received by the college and dept.');

        return redirect()->route('college-department-award.index')->with('award_success', 'Awards and recognition received by the college and department has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CollegeDepartmentAward $college_department_award)
    {
        $this->authorize('view', CollegeDepartmentAward::class);

        if (auth()->id() !== $college_department_award->user_id) {
            abort(403);
        }

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $awardFields = DB::select("CALL get_academic_development_fields_by_form_id(6)");

        $documents = CollegeDepartmentAwardDocument::where('college_department_award_id', $college_department_award->id)->get()->toArray();

        $values = $college_department_award->toArray();

        foreach($awardFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $values[$field->name])->pluck('name')->first();
                if($dropdownOptions == null)
                    $dropdownOptions = "-";
                $values[$field->name] = $dropdownOptions;
            }
            elseif($field->field_type_name == "college"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $college = College::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $college;
                }
            }
            elseif($field->field_type_name == "department"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $department = Department::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $department;
                }
            }
        }

        return view('academic-development.college-department-award.show', compact('awardFields', 'college_department_award', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CollegeDepartmentAward $college_department_award)
    {
        $this->authorize('update', CollegeDepartmentAward::class);

        if (auth()->id() !== $college_department_award->user_id) {
            abort(403);
        }

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($college_department_award->id, 21)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        $awardFields = DB::select("CALL get_academic_development_fields_by_form_id(6)");

        $dropdown_options = [];
        foreach($awardFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $documents = CollegeDepartmentAwardDocument::where('college_department_award_id', $college_department_award->id)->get()->toArray();

        $values = $college_department_award->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('academic-development.college-department-award.edit', compact('awardFields', 'college_department_award', 'documents', 'values', 'colleges', 'dropdown_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CollegeDepartmentAward $college_department_award)
    {
        $this->authorize('update', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = (new DateContentService())->checkDateContent($request, "date");

        $request->merge([
            'date' => $date,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $college_department_award->update(['description' => '-clear']);

        $college_department_award->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'CDAward-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    CollegeDepartmentAwardDocument::create([
                        'college_department_award_id' => $college_department_award->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated an award and recognition received by the college and dept.');

        return redirect()->route('college-department-award.index')->with('award_success', 'Awards and recognition received by the college and department has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollegeDepartmentAward $college_department_award)
    {
        $this->authorize('delete', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($college_department_award->id, 21)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        CollegeDepartmentAwardDocument::where('college_department_award_id', $college_department_award->id)->delete();
        $college_department_award->delete();

        \LogActivity::addToLog('Had deleted an award and recognition received by the college and dept.');

        return redirect()->route('college-department-award.index')->with('award_success', 'Awards and recognition received by the college and department has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        CollegeDepartmentAwardDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of an award and recognition received by the college and dept.');

        return true;
    }
}
