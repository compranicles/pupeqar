<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Models\Mobility;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\MobilityDocument;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;

class MobilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Mobility::class);

        $mobilities = Mobility::where('user_id', auth()->id())->orderBy('updated_at', 'desc')->get();
        return view('extension-programs.mobility.index', compact('mobilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Mobility::class);

        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $colleges = College::all();
        return view('extension-programs.mobility.create', compact('mobilityFields', 'colleges'));
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

        $request->validate([
            'nature_of_engagement' => 'required',
            'type' => 'required',
            'host_name' => 'required',
            // 'host_address' => '',
            'mobility_description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'college_id' => 'required',
            'department_id' => 'required',
            // 'description' => 'required',
        ]);

        $input = $request->except(['_token', '_method', 'document', 'college_id']);

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
                    $fileName = 'Mobility-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('mobility.index')->with('mobility_success', 'Your Accomplishment in Inter-Country Mobility has been saved.');
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

        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $documents = MobilityDocument::where('mobility_id', $mobility->id)->get()->toArray();
    
        $values = $mobility->toArray();
        
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

        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $mobility->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $mobility->toArray();

        $colleges = College::all();

        $documents = MobilityDocument::where('mobility_id', $mobility->id)->get()->toArray();

        return view('extension-programs.mobility.edit', compact('mobility', 'mobilityFields', 'documents', 'values', 'colleges', 'collegeAndDepartment'));
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

        $request->validate([
            'nature_of_engagement' => 'required',
            'type' => 'required',
            'host_name' => 'required',
            // 'host_address' => '',
            'mobility_description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'college_id' => 'required',
            'department_id' => 'required',
            // 'description' => 'required',
        ]);
        
        $input = $request->except(['_token', '_method', 'document', 'college_id']);

        $mobility->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'Mobility-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('mobility.index')->with('mobility_success', 'Your accomplishment in Inter-Country Mobility has been updated.');
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

        MobilityDocument::where('mobility_id', $mobility->id)->delete();
        $mobility->delete();
        return redirect()->route('mobility.index')->with('mobility_success', 'Your accomplishment in Inter-Country Mobility has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', Mobility::class);

        MobilityDocument::where('filename', $filename)->delete();
        return true;
    }
}
