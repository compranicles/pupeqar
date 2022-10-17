<?php

namespace App\Http\Controllers\IPCR;

use App\Helpers\LogActivity;
use App\Models\Dean;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\RequestDocument;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\IPCRForm;
use App\Models\FormBuilder\IPCRField;
use App\Models\Maintenance\Department;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;
use Exception;

class RequestController extends Controller
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
        $this->authorize('viewAny', RequestModel::class);

        $categories = DropdownOption::where('dropdown_id', 48)->get();

        $currentQuarterYear = Quarter::find(1);

        $requests = RequestModel::where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'requests.category')
                ->join('colleges', 'colleges.id', 'requests.college_id')
                ->select('requests.*', 'dropdown_options.name as category', 'colleges.name as college_name')
                ->orderBy('requests.updated_at', 'desc')
                ->get();

        $requests_in_colleges = RequestModel::whereNull('requests.deleted_at')->join('colleges', 'requests.college_id', 'colleges.id')
                                ->where('user_id', auth()->id())
                                ->select('colleges.name')->where('requests.user_id', auth()->id())
                                ->distinct()
                                ->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($requests as $request) {
            if (LockController::isLocked($request->id, 17))
                $submissionStatus[17][$request->id] = 1;
            else
                $submissionStatus[17][$request->id] = 0;
            if (empty($reportdata->getDocuments(17, $request->id)))
                $submissionStatus[17][$request->id] = 2;
        }

        return view('ipcr.request.index', compact('requests', 'requests_in_colleges', 'categories',
            'currentQuarterYear', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', RequestModel::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $dropdown_options = [];
        foreach($requestFields as $field){
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

        return view('ipcr.request.create', compact('requestFields', 'colleges', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', RequestModel::class);
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $requestdata = RequestModel::create($input);
        $requestdata->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $this->storageFileController->abbrev($request->input('description')), 'R-', 'request.index');
                if(is_string($fileName)) {
                    RequestDocument::create(['request_id' => $requestdata->id, 'filename' => $fileName]);
                } else {
                    RequestDocument::where('request_id', $requestdata->id)->delete();
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
        //                 $fileName = 'R-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();
        //                 RequestDocument::create([
        //                     'request_id' => $requestdata->id,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     } catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     }
        // }

        LogActivity::addToLog('Had added a Request & Queries Acted Upon.');

        return redirect()->route('request.index')->with('request_success', 'Your Accomplishment in Request & Queries Acted Upon has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RequestModel $request)
    {
        $this->authorize('view', RequestModel::class);

        if (auth()->id() !== $request->user_id)
            abort(403);

        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $documents = RequestDocument::where('request_id', $request->id)->get()->toArray();

        $values = $request->toArray();

        foreach($requestFields as $field){
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

        return view('ipcr.request.show', compact('request', 'requestFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestModel $request)
    {
        $currentQuarter = Quarter::find(1)->current_quarter;

        $this->authorize('update', RequestModel::class);

        if (auth()->id() !== $request->user_id)
            abort(403);

        if(LockController::isLocked($request->id, 17)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $dropdown_options = [];
        foreach($requestFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $values = $request->toArray();

        $documents = RequestDocument::where('request_id', $request->id)->get()->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('ipcr.request.edit', compact('request', 'requestFields', 'documents', 'values', 'colleges', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $requestdata, RequestModel $request)
    {
        $this->authorize('update', RequestModel::class);
        $currentQuarterYear = Quarter::find(1);

        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->merge([
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $requestdata->except(['_token', '_method', 'document']);

        $request->update(['description' => '-clear']);

        $request->update($input);

        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $this->storageFileController->abbrev($request->input('description')), 'R-', 'request.index');
                if(is_string($fileName)) {
                    RequestDocument::create(['request_id' => $request->id,'filename' => $fileName]);
                } else {
                    RequestDocument::where('request_id', $request->id)->delete();
                    return $fileName;
                }
            }
        }

        // if($requestdata->has('document')){
        //     try {
        //         $documents = $requestdata->input('document');
        //         foreach($documents as $document){
        //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //             if($temporaryFile){
        //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //                 $ext = $info['extension'];
        //                 $fileName = 'R-'.$this->storageFileController->abbrev($requestdata->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();

        //                 RequestDocument::create([
        //                     'request_id' => $request->id,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     } catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     }

            
        // }

        LogActivity::addToLog('Had updated a Request & Queries Acted Upon.');

        return redirect()->route('request.index')->with('request_success', 'Your accomplishment in Request & Queries Acted Upon has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestModel $request)
    {
        $this->authorize('delete', RequestModel::class);
        if(LockController::isLocked($request->id, 17)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        RequestDocument::where('request_id', $request->id)->delete();
        $request->delete();

        LogActivity::addToLog('Had deleted a Request & Queries Acted Upon.');

        return redirect()->route('request.index')->with('request_success', 'Your accomplishment in Request & Queries Acted Upon has been deleted.');
    }

    public function removeDoc($filename){
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        RequestDocument::where('filename', $filename)->delete();

        LogActivity::addToLog('Request & Queries Acted Upon document deleted.');

        return true;
    }
}
