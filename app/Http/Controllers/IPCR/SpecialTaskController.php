<?php

namespace App\Http\Controllers\IPCR;

use App\Helpers\LogActivity;
use App\Models\Employee;
use App\Models\SpecialTask;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Department;
use App\Models\Maintenance\Quarter;
use App\Models\SpecialTaskDocument;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\IPCRForm;
use App\Models\FormBuilder\IPCRField;
use App\Models\Authentication\UserRole;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;
use App\Services\DateContentService;
use Exception;

class SpecialTaskController extends Controller
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
    public function index(Request $request)
    {
        $version = '';
        if($request->has('v')){
            $version = $request->get('v');
        }

        $currentQuarterYear = Quarter::find(1);

        $categories = DropdownOption::where('dropdown_id', 61)->get();

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTasks = SpecialTask::where('user_id', auth()->id())
                ->join('colleges', 'colleges.id', 'special_tasks.college_id')
                ->select('special_tasks.*', 'colleges.name as college_name')
                ->orderBy('special_tasks.updated_at', 'desc')
                ->where('admin_or_faculty', $version)
                ->get();

        $tasks_in_colleges = SpecialTask::whereNull('special_tasks.deleted_at')->join('colleges', 'special_tasks.college_id', 'colleges.id')
                                ->where('user_id', auth()->id())
                                ->select('colleges.name')->where('special_tasks.user_id', auth()->id())
                                ->distinct()
                                ->get();

        $submissionStatus = [];
        $submitRole = "";
        $reportdata = new ReportDataController;
            foreach ($specialTasks as $task) {
                if (LockController::isLocked($task->id, 30)) {
                    $submissionStatus[30][$task->id] = 1;
                    $submitRole[$task->id] = ReportDataController::getSubmitRole($task->id, 30);
                }
                else
                    $submissionStatus[30][$task->id] = 0;
                if (empty($reportdata->getDocuments(30, $task->id)))
                    $submissionStatus[30][$task->id] = 2;
            }

        return view('ipcr.special-tasks.index', compact('roles', 'currentQuarterYear', 'categories',
            'specialTasks', 'tasks_in_colleges', 'submissionStatus', 'version', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $version = '';
        if($request->has('v')){
            $version = $request->get('v');
        }

        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarter = Quarter::find(1)->current_quarter;

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
            ->where('i_p_c_r_fields.i_p_c_r_form_id', 3)->where('i_p_c_r_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
            ->orderBy('i_p_c_r_fields.order')->get();

        $dropdown_options = [];
        foreach($specialTaskFields as $field){
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

        return view('ipcr.special-tasks.create', compact('specialTaskFields', 'colleges', 'roles', 'departments', 'dropdown_options', 'version', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $version = '';
        if($request->has('v')){
            $version = $request->get('v');
        }

        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $type = 'ST-';
        $namePage = 'Special Task';
        if(in_array('3', $roles)){
            $type = 'ABO-';
            $namePage = 'Accomplishment Based on OPCR';
        }

        $currentQuarterYear = Quarter::find(1);

        $target_date = (new DateContentService())->checkDateContent($request, "target_date");
        $actual_date = (new DateContentService())->checkDateContent($request, "actual_date");

        $request->merge([
            'target_date' => $target_date,
            'actual_date' => $actual_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'admin_or_faculty' => $version,
        ]);


        $input = $request->except(['_token', '_method', 'document', 'v']);

        $taskdata = SpecialTask::create($input);
        $taskdata->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $this->storageFileController->abbrev($request->input('description')), $type , 'special-tasks.index');
                if(is_string($fileName)) {
                    SpecialTaskDocument::create(['special_task_id' => $taskdata->id, 'filename' => $fileName]);
                } else {
                    SpecialTaskDocument::where('where', $taskdata->id)->delete();
                    return $fileName;
                }
            }
        }

        // if($request->has('document')){
        //     try {
        //         $documents = $request->input('document');
        //         foreach($documents as $document){
        //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //             if($temporaryFile){
        //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //                 $ext = $info['extension'];
        //                 $fileName = $type.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();
        //                 SpecialTaskDocument::create([
        //                     'special_task_id' => $taskdata->id,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     } catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     }
        // }

        LogActivity::addToLog('Had added a '.$namePage.'.');
        $version;
        return redirect()->route('special-tasks.index', 'v='.$version)->with('success', 'Your accomplishment in '.$namePage.' has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SpecialTask $special_task, Request $request)
    {
        $version = '';
        if($request->has('v')){
            $version = $request->get('v');
        }

        if (auth()->id() !== $special_task->user_id)
            abort(403);

        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 3)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $documents = SpecialTaskDocument::where('special_task_id', $special_task->id)->get()->toArray();

        $values = $special_task->toArray();

        foreach($specialTaskFields as $field){
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

        return view('ipcr.special-tasks.show', compact('special_task', 'specialTaskFields', 'documents', 'values', 'roles', 'version'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SpecialTask $special_task, Request $request)
    {
        $currentQuarter = Quarter::find(1)->current_quarter;
        $version = '';
        if($request->has('v')){
            $version = $request->get('v');
        }

        if (auth()->id() !== $special_task->user_id)
            abort(403);

        if(LockController::isLocked($special_task->id, 30) || LockController::isLocked($special_task->id, 31) || LockController::isLocked($special_task->id, 32)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 3)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $dropdown_options = [];
        foreach($specialTaskFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;
            }
        }

        $values = $special_task->toArray();

        $documents = SpecialTaskDocument::where('special_task_id', $special_task->id)->get()->toArray();

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

         $departments = Department::whereIn('college_id', $colleges)->get();

        return view('ipcr.special-tasks.edit', compact('special_task', 'specialTaskFields', 'documents', 'values', 'colleges', 'roles', 'departments', 'dropdown_options', 'version', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpecialTask $special_task)
    {
        $currentQuarterYear = Quarter::find(1);
        $version = '';
        if($request->has('v')){
            $version = $request->get('v');
        }

        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $type = 'ST-';
        $namePage = 'Special Task';
        if(in_array('3', $roles)){
            $type = 'ABO-';
            $namePage = 'Accomplishment Based on OPCR';
        }

        $target_date = (new DateContentService())->checkDateContent($request, "target_date");
        $actual_date = (new DateContentService())->checkDateContent($request, "actual_date");

        $request->merge([
            'target_date' => $target_date,
            'actual_date' => $actual_date,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);


        $input = $request->except(['_token', '_method', 'document', 'v']);

        $special_task->update(['description' => '-clear']);

        $special_task->update($input);

        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $this->storageFileController->abbrev($request->input('description')), $type , 'special-tasks.index');
                if(is_string($fileName)) {
                    SpecialTaskDocument::create(['special_task_id' => $special_task->id, 'filename' => $fileName]);
                } else {
                    SpecialTaskDocument::where('special_task_id', $special_task->id)->delete();
                    return $fileName;
                }
            }
        }

        // if($request->has('document')){
        //     try {
        //         $documents = $request->input('document');
        //         foreach($documents as $document){
        //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //             if($temporaryFile){
        //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //                 $ext = $info['extension'];
        //                 $fileName = $type.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();
        //             SpecialTaskDocument::create([
        //                     'special_task_id' => $special_task->id,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     } catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     }
        // }

        LogActivity::addToLog('Had updated a '.$namePage.'.');

        return redirect()->route('special-tasks.index', 'v='.$version)->with('success', 'Your accomplishment in '.$namePage.' has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialTask $special_task)
    {
        if(session()->get('user_type') == 'Faculty Employee')
            $version = 'faculty';
        else
            $version = 'admin';

            if(LockController::isLocked($special_task->id, 30) || LockController::isLocked($special_task->id, 31) || LockController::isLocked($special_task->id, 32)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $namePage = 'Special Task';
        if(in_array('3', $roles)){
            $namePage = 'Accomplishment Based on OPCR';
        }
        SpecialTaskDocument::where('special_task_id', $special_task->id)->delete();
        $special_task->delete();

        LogActivity::addToLog('Had deleted a '.$namePage.'.');

        return redirect()->route('special-tasks.index', 'v='.$version)->with('success', 'Your accomplishment in '.$namePage.' has been deleted.');
    }

    public function removeDoc($filename){
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        SpecialTaskDocument::where('filename', $filename)->delete();

        LogActivity::addToLog('Special Task document deleted.');

        return true;
    }
}
