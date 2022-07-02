<?php

namespace App\Http\Controllers\IPCR;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\CollegeFunction;
use App\Models\AttendanceFunction;
use App\Models\UniversityFunction;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\IPCRForm;
use App\Models\FormBuilder\IPCRField;
use App\Models\Maintenance\Department;
use App\Models\Authentication\UserRole;
use Illuminate\Support\Facades\Storage;
use App\Models\AttendanceFunctionDocument;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;
use App\Services\DateContentService;

class AttendanceFunctionController extends Controller
{

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
        $this->authorize('manage', AttendanceFunction::class);

        $currentQuarterYear = Quarter::find(1);

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $universityFunctions = UniversityFunction::all();
        $collegeFunctions = CollegeFunction::whereIn('college_functions.college_id', $colleges)
                            ->join('colleges', 'colleges.id', 'college_functions.college_id')
                            ->select('college_functions.*', 'colleges.name as college_name')->get();

        $attendedFunctions = AttendanceFunction::
                        join('dropdown_options', 'attendance_functions.classification', 'dropdown_options.id')->
                        where('attendance_functions.user_id', auth()->id())->
                        select('attendance_functions.*', 'dropdown_options.name as classification_name')->get();

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($attendedFunctions as $attendedFunction) {
            if (LockController::isLocked($attendedFunction->id, 33))
                $submissionStatus[33][$attendedFunction->id] = 1;
            else
                $submissionStatus[33][$attendedFunction->id] = 0;
            if (empty($reportdata->getDocuments(33, $attendedFunction->id)))
                $submissionStatus[33][$attendedFunction->id] = 2;
        }

        return view('ipcr.attendance-function.index',
                        compact('colleges',
                                    'attendedFunctions', 'currentQuarterYear',
                                    'roles', 'universityFunctions', 'collegeFunctions',
                                    'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('manage', AttendanceFunction::class);

        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $fields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
            ->where('i_p_c_r_fields.i_p_c_r_form_id', 4)->where('i_p_c_r_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
            ->orderBy('i_p_c_r_fields.order')->get();

         // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
         $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

         $departments = Department::whereIn('college_id', $colleges)->get();
        $values = [];
        if($request->get('type') == 'uni'){
            $values = UniversityFunction::where('id', $request->get('id'))->first()->toArray();
        }
        if($request->get('type') == 'college'){
            $values = CollegeFunction::where('id', $request->get('id'))->first()->toArray();
            $colleges = College::where('id', $values['college_id'])->get();
        }

        $classtype = $request->get('type');

        return view('ipcr.attendance-function.create', compact('fields', 'colleges', 'values', 'classtype', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', AttendanceFunction::class);

        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarterYear = Quarter::find(1);
        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'user_id' => auth()->id(),
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $attendance = AttendanceFunction::create($input);

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

                    AttendanceFunctionDocument::create([
                        'attendance_function_id' => $attendance->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a Attendance in University and College Function.');

        return redirect()->route('attendance-function.index')->with('success', 'Your Accomplishment in Attendance in University and College Functions has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceFunction $attendance_function)
    {
        $this->authorize('manage', AttendanceFunction::class);

        if (auth()->id() !== $attendance_function->user_id)
            abort(403);

        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $fields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 4)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $documents = AttendanceFunctionDocument::where('attendance_function_id', $attendance_function->id)->get()->toArray();

        $values = $attendance_function->toArray();

        return view('ipcr.attendance-function.show', compact('attendance_function', 'fields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceFunction $attendance_function)
    {
        if (auth()->id() !== $attendance_function->user_id)
            abort(403);

        $this->authorize('manage', AttendanceFunction::class);

        if(LockController::isLocked($attendance_function->id, 33)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $fields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
            ->where('i_p_c_r_fields.i_p_c_r_form_id', 4)->where('i_p_c_r_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
            ->orderBy('i_p_c_r_fields.order')->get();

         // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
         $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

         $departments = Department::whereIn('college_id', $colleges)->get();
        $values = $attendance_function->toArray();
        $classtype = '';
        if($attendance_function->classification == 293){
            $classtype = 'uni';
        }
        elseif($attendance_function->classification == 294){
            $classtype = 'college';
            $colleges = College::where('id', $values['college_id'])->get();
        }
        elseif($attendance_function->classification == 295){
            $classtype = 'dept';
            $colleges = College::where('id', $values['college_id'])->get();
        }

        $documents = AttendanceFunctionDocument::where('attendance_function_id', $attendance_function->id)->get()->toArray();

        return view('ipcr.attendance-function.edit', compact('fields', 'colleges', 'values', 'classtype', 'documents', 'attendance_function', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendanceFunction $attendance_function)
    {
        $this->authorize('manage', AttendanceFunction::class);

        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarterYear = Quarter::find(1);
        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");
        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $attendance_function->update(['description' => '-clear']);

        $attendance_function->update($input);

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

                    AttendanceFunctionDocument::create([
                        'attendance_function_id' => $attendance_function->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated a Attendance in University and College Function).');

        return redirect()->route('attendance-function.index')->with('success', 'Your Accomplishment in Attendance in University and College Functions has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceFunction $attendance_function)
    {
        $this->authorize('manage', AttendanceFunction::class);

        if(LockController::isLocked($attendance_function->id, 33)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        AttendanceFunctionDocument::where('attendance_function_id', $attendance_function->id)->delete();
        $attendance_function->delete();

        \LogActivity::addToLog('Had deleted a Special Task (Admin).');

        return redirect()->route('attendance-function.index')->with('success', 'Your accomplishment in Attendance in University and College Function has been deleted.');
    }

    public function removeDoc($filename){

        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        AttendanceFunctionDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Attendance Function document deleted.');

        return true;
    }
}
