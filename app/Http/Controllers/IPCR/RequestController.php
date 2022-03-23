<?php

namespace App\Http\Controllers\IPCR;

use Illuminate\Http\Request;
use App\Models\{
    TemporaryFile,
    Employee
};
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
use App\Http\Controllers\Maintenances\LockController;

class RequestController extends Controller
{
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
        return view('ipcr.request.index', compact('requests', 'requests_in_colleges', 'categories', 'currentQuarterYear'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', RequestModel::class);

        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        
        return view('ipcr.request.create', compact('requestFields', 'colleges'));
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

        $string = str_replace(' ', '-', $requestdata->description); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'Request-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    RequestDocument::create([
                        'request_id' => $requestdata->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Request & Queries Acted Upon added.');

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
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $documents = RequestDocument::where('request_id', $request->id)->get()->toArray();

        $values = $request->toArray();
    
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
        $this->authorize('update', RequestModel::class);

        if(LockController::isLocked($request->id, 17)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $values = $request->toArray();

        $documents = RequestDocument::where('request_id', $request->id)->get()->toArray();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        return view('ipcr.request.edit', compact('request', 'requestFields', 'documents', 'values', 'colleges'));
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
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        
        $input = $requestdata->except(['_token', '_method', 'document']);

        $request->update(['description' => '-clear']);

        $request->update($input);

        $string = str_replace(' ', '-', $request->description); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($requestdata->has('document')){
            
            $documents = $requestdata->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'Request-'.str_replace("/", "-", $request->description).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    RequestDocument::create([
                        'request_id' => $request->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Request & Queries Acted Upon updated.');

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
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        RequestDocument::where('request_id', $request->id)->delete();
        $request->delete();

        \LogActivity::addToLog('Request & Queries Acted Upon deleted.');

        return redirect()->route('request.index')->with('request_success', 'Your accomplishment in Request & Queries Acted Upon has been deleted.');
    }

    public function removeDoc($filename){
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        RequestDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Request & Queries Acted Upon document deleted.');

        return true;
    }
}
