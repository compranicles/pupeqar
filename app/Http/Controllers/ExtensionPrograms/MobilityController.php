<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Models\Dean;
use App\Models\Employee;
use App\Models\Mobility;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\MobilityDocument;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Services\DateContentService;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class MobilityController extends Controller
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
        $this->authorize('viewAny', Mobility::class);

        $currentQuarterYear = Quarter::find(1);

        $mobilities = Mobility::where('user_id', auth()->id())
                                ->join('colleges', 'colleges.id', 'mobilities.college_id')
                                ->select(DB::raw('mobilities.*, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($mobilities as $mobility) {
            if($mobility->classification_of_person == '298'){
                if (LockController::isLocked($mobility->id, 35))
                    $submissionStatus[35][$mobility->id] = 1;
                else
                    $submissionStatus[35][$mobility->id] = 0;
                if (empty($reportdata->getDocuments(35, $mobility->id)))
                    $submissionStatus[35][$mobility->id] = 2;
            }
            else{
                if (LockController::isLocked($mobility->id, 14))
                    $submissionStatus[14][$mobility->id] = 1;
                else
                    $submissionStatus[14][$mobility->id] = 0;
                if (empty($reportdata->getDocuments(14, $mobility->id)))
                    $submissionStatus[14][$mobility->id] = 2;
            }
        }

        return view('extension-programs.mobility.index', compact('mobilities', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Mobility::class);

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $dropdown_options = [];
        foreach($mobilityFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
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

        return view('extension-programs.mobility.create', compact('mobilityFields', 'colleges', 'departments', 'colaccomp', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Mobility::class);

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

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $mobility = Mobility::create($input);
        $mobility->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'InterM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    MobilityDocument::create([
                        'mobility_id' => $mobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added an inter-country mobility.');

        return redirect()->route('mobility.index')->with('mobility_success', 'Inter-Country mobility has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mobility $mobility)
    {
        $this->authorize('view', Mobility::class);

        if (auth()->id() !== $mobility->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $documents = MobilityDocument::where('mobility_id', $mobility->id)->get()->toArray();

        $values = $mobility->toArray();

        foreach($mobilityFields as $field){
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

        return view('extension-programs.mobility.show', compact('mobility', 'mobilityFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Mobility $mobility)
    {
        $this->authorize('update', Mobility::class);

        if (auth()->id() !== $mobility->user_id)
            abort(403);

        if($mobility->classification_of_person == '298')
            if(LockController::isLocked($mobility->id, 35)){
                return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
            }
        else
            if(LockController::isLocked($mobility->id, 14)){
                return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
            }


        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $dropdown_options = [];
        foreach($mobilityFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $mobility->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $mobility->toArray();

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

        $documents = MobilityDocument::where('mobility_id', $mobility->id)->get()->toArray();

        return view('extension-programs.mobility.edit', compact('mobility', 'mobilityFields', 'documents', 'values', 'colleges', 'collegeAndDepartment', 'departments', 'colaccomp', 'dropdown_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mobility $mobility)
    {
        $this->authorize('update', Mobility::class);

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

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $mobility->update(['description' => '-clear']);

        $mobility->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'InterM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    MobilityDocument::create([
                        'mobility_id' => $mobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated an inter-country mobility.');


        return redirect()->route('mobility.index')->with('mobility_success', 'Inter-Country mobility has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mobility $mobility)
    {
        $this->authorize('delete', Mobility::class);

        if(LockController::isLocked($mobility->id, 14)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        MobilityDocument::where('mobility_id', $mobility->id)->delete();
        $mobility->delete();
        \LogActivity::addToLog('Had deleted an inter-country mobility.');

        return redirect()->route('mobility.index')->with('mobility_success', 'Inter-Country mobility has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', Mobility::class);

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        MobilityDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of an inter-country mobility.');

        return true;
    }
}
