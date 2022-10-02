<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Models\Dean;
use App\Models\Employee;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\IntraMobility;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Services\DateContentService;
use App\Models\IntraMobilityDocument;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class IntraMobilityController extends Controller
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
        $this->authorize('manage', IntraMobility::class);

        $currentQuarterYear = Quarter::find(1);

        $intraMobilities = IntraMobility::where('user_id', auth()->id())
                                ->join('colleges', 'colleges.id', 'intra_mobilities.college_id')
                                ->select(DB::raw('intra_mobilities.*, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $submitRole = "";
        $reportdata = new ReportDataController;
        foreach ($intraMobilities as $intraMobility) {
            if($intraMobility->classification_of_person == '298'){
                if (LockController::isLocked($intraMobility->id, 36))
                    $submissionStatus[36][$intraMobility->id] = 1;
                else
                    $submissionStatus[36][$intraMobility->id] = 0;
                if (empty($reportdata->getDocuments(36, $intraMobility->id)))
                    $submissionStatus[36][$intraMobility->id] = 2;
            }
            else{
                if (LockController::isLocked($intraMobility->id, 34)) {
                    $submissionStatus[34][$intraMobility->id] = 1;
                    $submitRole[$intraMobility->id] = ReportDataController::getSubmitRole($intraMobility->id, 34);
                }
                else
                    $submissionStatus[34][$intraMobility->id] = 0;
                if (empty($reportdata->getDocuments(34, $intraMobility->id)))
                    $submissionStatus[34][$intraMobility->id] = 2;
            }
        }

        return view('extension-programs.intra-mobilities.index', compact('intraMobilities', 'currentQuarterYear',
            'submissionStatus', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', IntraMobility::class);

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('8')");

        $dropdown_options = [];
        foreach($mobilityFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $colaccomp = 0;

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        if(in_array(5, $roles)){

            $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
            $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
            $colleges = array_merge($deans, $chairpersons);
            $colleges = College::whereIn('id', array_values($colleges))
                        ->select('colleges.*')->get();
            $departments = [];
            $colaccomp = 1;
        }
        elseif(in_array(6, $roles)){

            $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
            $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
            $colleges = array_merge($deans, $chairpersons);
            $colleges = College::whereIn('id', array_values($colleges))
                        ->select('colleges.*')->get();
            $departments = [];
            $colaccomp = 1;
        }
        else{
            $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();
            $departments = Department::whereIn('college_id', $colleges)->get();
            $colaccomp = 0;
        }
        return view('extension-programs.intra-mobilities.create', compact('mobilityFields', 'colleges', 'departments', 'colaccomp', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', IntraMobility::class);

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");
        $currentQuarterYear = Quarter::find(1);

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        if(in_array(6, $roles)|| in_array(5, $roles)){
            $request->merge([
                'start_date' => $start_date,
                'end_date' => $end_date,
                'report_quarter' => $currentQuarterYear->current_quarter,
                'report_year' => $currentQuarterYear->current_year,
            ]);
        }
        else{
            $request->merge([
                'start_date' => $start_date,
                'end_date' => $end_date,
                'report_quarter' => $currentQuarterYear->current_quarter,
                'report_year' => $currentQuarterYear->current_year,
                'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            ]);
        }

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
        return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $intraMobility = IntraMobility::create($input);
        $intraMobility->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IntraM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    IntraMobilityDocument::create([
                        'intra_mobility_id' => $intraMobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added an intra-country mobility.');

        return redirect()->route('intra-mobility.index')->with('mobility_success', 'Intra-Country mobility has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        if (auth()->id() !== $intraMobility->user_id)
        abort(403);

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('8')");

        $documents = IntraMobilityDocument::where('intra_mobility_id', $intraMobility->id)->get()->toArray();

        $values = $intraMobility->toArray();

        foreach($mobilityFields as $field){
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

        return view('extension-programs.intra-mobilities.show', compact('intraMobility', 'mobilityFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        if (auth()->id() !== $intraMobility->user_id)
            abort(403);

        if($intraMobility->classification_of_person == '298') {
            if(LockController::isLocked($intraMobility->id, 36)){
                return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
            }
        }
        else {
            if(LockController::isLocked($intraMobility->id, 34)){
                return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
            }
        }

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('8')");

        $dropdown_options = [];
        foreach($mobilityFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $intraMobility->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $intraMobility->toArray();

        $colaccomp = 0;

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        if(in_array(5, $roles) || in_array(6, $roles)){

            $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
            $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
            $colleges = array_merge($deans, $chairpersons);
            $colleges = College::whereIn('id', array_values($colleges))
                        ->select('colleges.*')->get();
            $departments = [];
            $colaccomp = 1;
        }
        else{
            $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();
            $departments = Department::whereIn('college_id', $colleges)->get();
            $colaccomp = 0;
        }

        $documents = IntraMobilityDocument::where('intra_mobility_id', $intraMobility->id)->get()->toArray();

        return view('extension-programs.intra-mobilities.edit', compact('intraMobility', 'mobilityFields', 'documents', 'values', 'colleges', 'collegeAndDepartment', 'departments', 'colaccomp', 'dropdown_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        if(in_array(6, $roles)|| in_array(5, $roles)){
            $request->merge([
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
        }
        else{
            $request->merge([
                'start_date' => $start_date,
                'end_date' => $end_date,
                'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            ]);
        }

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $intraMobility->update(['description' => '-clear']);

        $intraMobility->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IntraM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    IntraMobilityDocument::create([
                        'intra_mobility_id' => $intraMobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated an intra-country mobility.');


        return redirect()->route('intra-mobility.index')->with('mobility_success', 'Intra-Country mobility has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        if(LockController::isLocked($intraMobility->id, 34)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        IntraMobilityDocument::where('intra_mobility_id', $intraMobility->id)->delete();
        $intraMobility->delete();

        \LogActivity::addToLog('Had deleted an intra-country mobility.');

        return redirect()->route('intra-mobility.index')->with('mobility_success', 'Intra-Country mobility has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('manage', IntraMobility::class);

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        IntraMobilityDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of an intra-country mobility.');

        return true;
    }
}
