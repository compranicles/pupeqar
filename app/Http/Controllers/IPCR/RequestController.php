<?php

namespace App\Http\Controllers\IPCR;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\RequestDocument;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\IPCRForm;
use App\Models\FormBuilder\IPCRField;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = RequestModel::where('user_id', auth()->id())
        ->join('dropdown_options', 'dropdown_options.id', 'requests.category')
        ->select('requests.*', 'dropdown_options.name as category')
        ->orderBy('requests.updated_at', 'desc')
        ->get();
        return view('ipcr.request.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();
        return view('ipcr.request.create', compact('requestFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $requestdata = RequestModel::create($input);
        $requestdata->update(['user_id' => auth()->id()]);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'request-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $requestFields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
                        ->where('i_p_c_r_fields.i_p_c_r_form_id', 1)->where('i_p_c_r_fields.is_active', 1)
                        ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                        ->orderBy('i_p_c_r_fields.order')->get();

        $values = $request->toArray();

        $documents = RequestDocument::where('request_id', $request->id)->get()->toArray();

        return view('ipcr.request.edit', compact('request', 'requestFields', 'documents', 'values'));
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
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        
        $input = $requestdata->except(['_token', '_method', 'document']);

        $request->update($input);

        if($requestdata->has('document')){
            
            $documents = $requestdata->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'request-'.$requestdata->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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
        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        RequestDocument::where('request_id', $request->id)->delete();
        $request->delete();
        return redirect()->route('request.index')->with('request_success', 'Your accomplishment in Request & Queries Acted Upon has been deleted.');
    }

    public function removeDoc($filename){

        if(IPCRForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        RequestDocument::where('filename', $filename)->delete();
        return true;
    }
}
