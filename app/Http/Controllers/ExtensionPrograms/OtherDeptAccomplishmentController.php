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
    OtherDeptAccomplishment,
    OtherDeptAccomplishmentDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
    Dean,
    Chairperson,
};
use App\Services\DateContentService;
use Exception;

class OtherDeptAccomplishmentController extends Controller
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
        $this->authorize('manage', OtherDeptAccomplishment::class);

        $currentQuarterYear = Quarter::find(1);

        $otherAccomplishments = OtherDeptAccomplishment::where('user_id', auth()->id())
                                ->join('dropdown_options', 'dropdown_options.id', 'other_dept_accomplishments.level')
                                ->join('colleges', 'colleges.id', 'other_dept_accomplishments.college_id')
                                ->select(DB::raw('other_dept_accomplishments.*, dropdown_options.name as accomplishment_level, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = array();
        $reportdata = new ReportDataController;
        foreach ($otherAccomplishments as $otherAccomplishment) {
            if (LockController::isLocked($otherAccomplishment->id, 39))
                $submissionStatus[39][$otherAccomplishment->id] = 1;
            else
                $submissionStatus[39][$otherAccomplishment->id] = 0;
            if (empty($reportdata->getDocuments(39, $otherAccomplishment->id)))
                $submissionStatus[39][$otherAccomplishment->id] = 2;
        }

        return view('extension-programs.other-dept-accomplishments.index', compact('otherAccomplishments', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', OtherDeptAccomplishment::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(ExtensionProgramForm::where('id', 11)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('11')");

        $dropdown_options = [];
        foreach($otherAccomplishmentFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('extension-programs.other-dept-accomplishments.create', compact('otherAccomplishmentFields', 'colleges', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', OtherDeptAccomplishment::class);

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        if(ExtensionProgramForm::where('id', 11)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $otherDeptAccomplishment = OtherDeptAccomplishment::create($input);
        $otherDeptAccomplishment->update(['user_id' => auth()->id()]);

        if($request->has('document')){


            try {
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
    
                        OtherDeptAccomplishmentDocument::create([
                            'other_dept_accomplishment_id' => $otherDeptAccomplishment->id,
                            'filename' => $fileName,
                        ]);
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }

           
        }
        \LogActivity::addToLog('Had added other department/college accomplishment.');

        return redirect()->route('other-dept-accomplishment.index')->with('other_dept_success', 'Other department/college accomplishment has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OtherDeptAccomplishment $otherDeptAccomplishment)
    {
        $this->authorize('manage', OtherDeptAccomplishment::class);

        if (auth()->id() !== $otherDeptAccomplishment->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 11)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('11')");

        $documents = OtherDeptAccomplishmentDocument::where('other_dept_accomplishment_id', $otherDeptAccomplishment->id)->get()->toArray();

        $values = $otherDeptAccomplishment->toArray();

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

        return view('extension-programs.other-dept-accomplishments.show', compact('otherDeptAccomplishment', 'otherAccomplishmentFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherDeptAccomplishment $otherDeptAccomplishment)
    {
        $this->authorize('manage', OtherDeptAccomplishment::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if (auth()->id() !== $otherDeptAccomplishment->user_id)
            abort(403);

        if(LockController::isLocked($otherDeptAccomplishment->id, 39)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 11)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('11')");

        $dropdown_options = [];
        foreach($otherAccomplishmentFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $otherDeptAccomplishment->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $otherDeptAccomplishment->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();
        $documents = OtherDeptAccomplishmentDocument::where('other_dept_accomplishment_id', $otherDeptAccomplishment->id)->get()->toArray();

        return view('extension-programs.other-dept-accomplishments.edit', compact('otherDeptAccomplishment', 'otherAccomplishmentFields', 'documents', 'values', 'colleges', 'collegeAndDepartment', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OtherDeptAccomplishment $otherDeptAccomplishment)
    {
        $this->authorize('manage', OtherDeptAccomplishment::class);
        $currentQuarterYear = Quarter::find(1);

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);


        if(ExtensionProgramForm::where('id', 11)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $otherDeptAccomplishment->update(['description' => '-clear']);

        $otherDeptAccomplishment->update($input);

        if($request->has('document')){
            try {
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

                        OtherDeptAccomplishmentDocument::create([
                            'other_dept_accomplishment_id' => $otherDeptAccomplishment->id,
                            'filename' => $fileName,
                        ]);
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }
        }

        \LogActivity::addToLog('Had updated other department/college accomplishment.');


        return redirect()->route('other-dept-accomplishment.index')->with('other_dept_success', 'Other department/college accomplishment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OtherDeptAccomplishment $otherDeptAccomplishment)
    {
        $this->authorize('manage', OtherDeptAccomplishment::class);

        if(LockController::isLocked($otherDeptAccomplishment->id, 39)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 11)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherDeptAccomplishmentDocument::where('other_dept_accomplishment_id', $otherDeptAccomplishment->id)->delete();
        $otherDeptAccomplishment->delete();
        \LogActivity::addToLog('Had deleted other department/college accomplishment.');

        return redirect()->route('other-dept-accomplishment.index')->with('other_dept_success', 'Other department/college accomplishment has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('manage', OtherDeptAccomplishment::class);

        if(ExtensionProgramForm::where('id', 11)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherDeptAccomplishmentDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of other department/college accomplishment.');

        return true;
    }
}
