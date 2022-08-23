<?php

namespace App\Http\Controllers\IPCR;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\AdminSpecialTask;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\IPCRForm;
use App\Services\DateContentService;
use App\Models\FormBuilder\IPCRField;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminSpecialTaskDocument;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class AdminSpecialTaskController extends Controller
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
        $this->authorize('manage', AdminSpecialTask::class);

        $currentQuarterYear = Quarter::find(1);

        $adminSpecialTasks = AdminSpecialTask::where('user_id', auth()->id())
            ->join('colleges', 'colleges.id', 'admin_special_tasks.college_id')
            ->select('admin_special_tasks.*', 'colleges.name as college_name')
            ->orderBy('admin_special_tasks.updated_at', 'desc')
            ->get();
        $tasksInColleges = AdminSpecialTask::whereNull('admin_special_tasks.deleted_at')->join('colleges', 'admin_special_tasks.college_id', 'colleges.id')
            ->where('user_id', auth()->id())
            ->select('colleges.name')->where('admin_special_tasks.user_id', auth()->id())
            ->distinct()
            ->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($adminSpecialTasks as $adminTask) {
            if (LockController::isLocked($adminTask->id, 29))
                $submissionStatus[29][$adminTask->id] = 1;
            else
                $submissionStatus[29][$adminTask->id] = 0;
            if (empty($reportdata->getDocuments(29, $adminTask->id)))
                $submissionStatus[29][$adminTask->id] = 2;
        }
        return view('ipcr.admin-special-tasks.index', compact('currentQuarterYear', 'adminSpecialTasks',
            'tasksInColleges', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', AdminSpecialTask::class);

        if(IPCRForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
            ->where('i_p_c_r_fields.i_p_c_r_form_id', 2)->where('i_p_c_r_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
            ->orderBy('i_p_c_r_fields.order')->get();

        $dropdown_options = [];
        foreach($specialTaskFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

         $departments = Department::whereIn('college_id', $colleges)->get();

        return view('ipcr.admin-special-tasks.create', compact('specialTaskFields', 'colleges', 'departments', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', AdminSpecialTask::class);

        if(IPCRForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarterYear = Quarter::find(1);

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $taskdata = AdminSpecialTask::create($input);
        $taskdata->update(['user_id' => auth()->id()]);

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

                    AdminSpecialTaskDocument::create([
                        'special_task_id' => $taskdata->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a Special Task (Admin).');

        return redirect()->route('admin-special-tasks.index')->with('success', 'Your Accomplishment in Special Tasks has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AdminSpecialTask $admin_special_task)
    {
        if (auth()->id() !== $admin_special_task->user_id)
            abort(403);

        $this->authorize('manage', AdminSpecialTask::class);

        if(IPCRForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 2)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $documents = AdminSpecialTaskDocument::where('special_task_id', $admin_special_task->id)->get()->toArray();

        $values = $admin_special_task->toArray();

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

        return view('ipcr.admin-special-tasks.show', compact('admin_special_task', 'specialTaskFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminSpecialTask $admin_special_task)
    {
        if (auth()->id() !== $admin_special_task->user_id)
            abort(403);

        $this->authorize('manage', AdminSpecialTask::class);

        if(LockController::isLocked($admin_special_task->id, 29)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(IPCRForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 2)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $dropdown_options = [];
        foreach($specialTaskFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $values = $admin_special_task->toArray();

        $documents = AdminSpecialTaskDocument::where('special_task_id', $admin_special_task->id)->get()->toArray();

        $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

         $departments = Department::whereIn('college_id', $colleges)->get();

        return view('ipcr.admin-special-tasks.edit', compact('admin_special_task', 'specialTaskFields', 'documents', 'values', 'colleges', 'departments', 'dropdown_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminSpecialTask $admin_special_task)
    {
        $this->authorize('manage', AdminSpecialTask::class);

        if(IPCRForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $from = (new DateContentService())->checkDateContent($request, "from");
        $to = (new DateContentService())->checkDateContent($request, "to");

        $request->merge([
            'from' => $from,
            'to' => $to,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);
        $input = $request->except(['_token', '_method', 'document']);

        $admin_special_task->update(['description' => '-clear']);

        $admin_special_task->update($input);

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
                    AdminSpecialTaskDocument::create([
                        'special_task_id' => $admin_special_task->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated a Special Task (Admin).');

        return redirect()->route('admin-special-tasks.index')->with('success', 'Your accomplishment in Special Task has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminSpecialTask $admin_special_task)
    {
        $this->authorize('manage', AdminSpecialTask::class);

        if(LockController::isLocked($admin_special_task->id, 29)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(IPCRForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        AdminSpecialTaskDocument::where('special_task_id', $admin_special_task->id)->delete();
        $admin_special_task->delete();

        \LogActivity::addToLog('Had deleted a Special Task (Admin).');

        return redirect()->route('admin-special-tasks.index')->with('success', 'Your accomplishment in Special Task has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('manage', AdminSpecialTask::class);

        if(IPCRForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        AdminSpecialTaskDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Special Task document deleted.');

        return true;
    }
}
