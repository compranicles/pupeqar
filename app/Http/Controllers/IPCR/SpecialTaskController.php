<?php

namespace App\Http\Controllers\IPCR;

use App\Models\Employee;
use App\Models\SpecialTask;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
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
    public function index()
    {
        $currentQuarterYear = Quarter::find(1);

        $categories = DropdownOption::where('dropdown_id', 61)->get();

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTasks = SpecialTask::where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'special_tasks.commitment_measure')
                ->join('colleges', 'colleges.id', 'special_tasks.college_id')
                ->select('special_tasks.*', 'dropdown_options.name as commitment_measure_name', 'colleges.name as college_name')
                ->orderBy('special_tasks.updated_at', 'desc')
                ->get();

        $tasks_in_colleges = SpecialTask::whereNull('special_tasks.deleted_at')->join('colleges', 'special_tasks.college_id', 'colleges.id')
                                ->where('user_id', auth()->id())
                                ->select('colleges.name')->where('special_tasks.user_id', auth()->id())
                                ->distinct()
                                ->get();

        return view('ipcr.special-tasks.index', compact('roles', 'currentQuarterYear', 'categories', 'specialTasks', 'tasks_in_colleges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
            ->where('i_p_c_r_fields.i_p_c_r_form_id', 3)->where('i_p_c_r_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
            ->orderBy('i_p_c_r_fields.order')->get();
        
        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        return view('ipcr.special-tasks.create', compact('specialTaskFields', 'colleges', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $target_date = date("Y-m-d", strtotime($request->input('target_date')));
        $actual_date = date("Y-m-d", strtotime($request->input('actual_date')));
        $request->merge([
            'target_date' => $target_date,
            'actual_date' => $actual_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);


        $input = $request->except(['_token', '_method', 'document']);

        $taskdata = SpecialTask::create($input);
        $taskdata->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = $type.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    SpecialTaskDocument::create([
                        'special_task_id' => $taskdata->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a '.$namePage.'.');

        return redirect()->route('special-tasks.index')->with('success', 'Your accomplishment in '.$namePage.' has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SpecialTask $special_task)
    {
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 3)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();
        
        $documents = SpecialTaskDocument::where('special_task_id', $special_task->id)->get()->toArray();

        $values = $special_task->toArray();
    
        return view('ipcr.special-tasks.show', compact('special_task', 'specialTaskFields', 'documents', 'values', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SpecialTask $special_task)
    {
        if(LockController::isLocked($special_task->id, 30)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $specialTaskFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 3)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $values = $special_task->toArray();

        $documents = SpecialTaskDocument::where('special_task_id', $special_task->id)->get()->toArray();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        return view('ipcr.special-tasks.edit', compact('special_task', 'specialTaskFields', 'documents', 'values', 'colleges', 'roles'));
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
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $type = 'ST-';
        $namePage = 'Special Task';
        if(in_array('3', $roles)){
            $type = 'ABO-';
            $namePage = 'Accomplishment Based on OPCR';
        }
            
        $target_date = date("Y-m-d", strtotime($request->input('target_date')));
        $actual_date = date("Y-m-d", strtotime($request->input('actual_date')));
        $request->merge([
            'target_date' => $target_date,
            'actual_date' => $actual_date,
        ]);


        $input = $request->except(['_token', '_method', 'document']);

        $special_task->update(['description' => '-clear']);

        $special_task->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = $type.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();
                   SpecialTaskDocument::create([
                        'special_task_id' => $special_task->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated a '.$namePage.'.');

        return redirect()->route('special-tasks.index')->with('success', 'Your accomplishment in '.$namePage.' has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialTask $special_task)
    {
        if(LockController::isLocked($special_task->id, 30)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
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

        \LogActivity::addToLog('Had deleted a '.$namePage.'.');

        return redirect()->route('special-tasks.index')->with('success', 'Your accomplishment in '.$namePage.' has been deleted.');
    }

    public function removeDoc($filename){
        if(IPCRForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        SpecialTaskDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Special Task document deleted.');

        return true;
    }
}
