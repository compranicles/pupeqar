<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Storage,
};
use App\Models\{
    Employee,
    OtherAccomplishment,
    OtherAccomplishmentDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};

use App\Services\DateContentService;

class OtherAccomplishmentController extends Controller
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
        $this->authorize('manage', OtherAccomplishment::class);

        $currentQuarterYear = Quarter::find(1);

        $otherAccomplishments = OtherAccomplishment::where('user_id', auth()->id())
                                ->join('dropdown_options', 'dropdown_options.id', 'other_accomplishments.level')
                                ->join('colleges', 'colleges.id', 'other_accomplishments.college_id')
                                ->select(DB::raw('other_accomplishments.*, dropdown_options.name as accomplishment_level, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $submitRole = "";
        $reportdata = new ReportDataController;
        foreach ($otherAccomplishments as $otherAccomplishment) {
            if (LockController::isLocked($otherAccomplishment->id, 38)) {
                $submissionStatus[38][$otherAccomplishment->id] = 1;
                $submitRole[$otherAccomplishment->id] = ReportDataController::getSubmitRole($otherAccomplishment->id, 38);
            }
            else
                $submissionStatus[38][$otherAccomplishment->id] = 0;
            if (empty($reportdata->getDocuments(38, $otherAccomplishment->id)))
                $submissionStatus[38][$otherAccomplishment->id] = 2;
        }


        return view('extension-programs.other-accomplishments.index', compact('otherAccomplishments', 'currentQuarterYear',
            'submissionStatus', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', OtherAccomplishment::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $dropdown_options = [];
        foreach($otherAccomplishmentFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();
        return view('extension-programs.other-accomplishments.create', compact('otherAccomplishmentFields', 'colleges', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', OtherAccomplishment::class);

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
        return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);
        // dd($input);

        $otherAccomplishment = OtherAccomplishment::create($input);
        $otherAccomplishment->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'OA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    OtherAccomplishmentDocument::create([
                        'other_accomplishment_id' => $otherAccomplishment->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added other individual accomplishment.');

        return redirect()->route('other-accomplishment.index')->with('other_success', 'Other accomplishment has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OtherAccomplishment $otherAccomplishment)
    {
        $this->authorize('manage', OtherAccomplishment::class);

        if (auth()->id() !== $otherAccomplishment->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $documents = OtherAccomplishmentDocument::where('other_accomplishment_id', $otherAccomplishment->id)->get()->toArray();

        $values = $otherAccomplishment->toArray();

        foreach($otherAccomplishmentFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $values[$field->name])->where('is_active', 1)->pluck('name')->first();
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

        return view('extension-programs.other-accomplishments.show', compact('otherAccomplishment', 'otherAccomplishmentFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherAccomplishment $otherAccomplishment)
    {
        $this->authorize('manage', OtherAccomplishment::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if (auth()->id() !== $otherAccomplishment->user_id)
            abort(403);

        if(LockController::isLocked($otherAccomplishment->id, 38)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $dropdown_options = [];
        foreach($otherAccomplishmentFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $otherAccomplishment->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $otherAccomplishment->toArray();

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $documents = OtherAccomplishmentDocument::where('other_accomplishment_id', $otherAccomplishment->id)->get()->toArray();

        return view('extension-programs.other-accomplishments.edit', compact('otherAccomplishment', 'otherAccomplishmentFields', 'documents', 'values', 'colleges', 'collegeAndDepartment', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OtherAccomplishment $otherAccomplishment)
    {
        $this->authorize('manage', OtherAccomplishment::class);
        $currentQuarterYear = Quarter::find(1);

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");

        $request->merge([
            'from' => $from,
            'to' => $to,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $otherAccomplishment->update(['description' => '-clear']);

        $otherAccomplishment->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'OA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    OtherAccomplishmentDocument::create([
                        'other_accomplishment_id' => $otherAccomplishment->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated other accomplishment.');


        return redirect()->route('other-accomplishment.index')->with('other_success', 'Other accomplishment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OtherAccomplishment $otherAccomplishment)
    {
        $this->authorize('manage', OtherAccomplishment::class);

        if(LockController::isLocked($otherAccomplishment->id, 38)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherAccomplishmentDocument::where('other_accomplishment_id', $otherAccomplishment->id)->delete();
        $otherAccomplishment->delete();
        \LogActivity::addToLog('Had deleted other accomplishment.');

        return redirect()->route('other-accomplishment.index')->with('other_success', 'Other accomplishment has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('manage', OtherAccomplishment::class);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherAccomplishmentDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of other accomplishment.');

        return true;
    }
}
