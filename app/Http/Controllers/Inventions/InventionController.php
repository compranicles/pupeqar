<?php

namespace App\Http\Controllers\Inventions;

use App\Models\Invention;
use Illuminate\Http\Request;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\FormBuilder\InventionField;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;
use App\Models\InventionDocument;
use Illuminate\Support\Facades\DB;

class InventionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventions = Invention::where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'inventions.status')
        ->select('inventions.*', 'dropdown_options.name as status_name')->orderBy('inventions.updated_at', 'desc')->get();

        $classifications = Invention::where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'inventions.classification')
        ->select('dropdown_options.name as classification_name')->orderBy('inventions.updated_at', 'desc')->get();

        return view('inventions.index', compact('inventions', 'classifications'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inventionFields = DB::select("CALL get_invention_fields_by_form_id(1)");

        $colleges = College::all();
        return view('inventions.create', compact('inventionFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', '_method', 'document', 'college_id']);

        $iicw = Invention::create($input);
        $iicw->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IICW-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    InventionDocument::create([
                        'invention_id' => $iicw->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('faculty.invention-innovation-creative.index')->with('edit_iicw_success', 'Your Accomplishment in Invention, Innovation, and Creative Works has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invention $invention_innovation_creative)
    {
        // dd($fields);
        $classification = DB::select("CALL get_dropdown_name_by_id(".$invention_innovation_creative->classification.")");
        
        $funding_type = DB::select("CALL get_dropdown_name_by_id(".$invention_innovation_creative->funding_type.")");


        $status = DB::select("CALL get_dropdown_name_by_id(".$invention_innovation_creative->status.")");


        $inventionDocuments = InventionDocument::where('invention_id', $invention_innovation_creative->id)->get()->toArray();

        $collegeAndDepartment = DB::select("CALL get_college_and_department_by_department_id(".$invention_innovation_creative->department_id.")");

        return view('inventions.show', compact('invention_innovation_creative', 'classification', 'funding_type',
                    'status', 'inventionDocuments', 'collegeAndDepartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Invention $invention_innovation_creative)
    {
        $inventionFields = DB::select("CALL get_invention_fields_by_form_id(1)");

        $inventionDocuments = InventionDocument::where('invention_id', $invention_innovation_creative->id)->get()->toArray();
        
        $colleges = College::all();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$invention_innovation_creative->department_id.")");

        $value = $invention_innovation_creative;
        $value->toArray();
        $value = collect($invention_innovation_creative);
        $value = $value->toArray();

        return view('inventions.edit', compact('value', 'inventionFields', 'inventionDocuments', 'colleges', 'collegeOfDepartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invention $invention_innovation_creative)
    {
        $input = $request->except(['_token', '_method', 'document', 'college_id']);

        $invention_innovation_creative->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IICW-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    InventionDocument::create([
                        'invention_id' => $invention_innovation_creative->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('faculty.invention-innovation-creative.index')->with('edit_iicw_success', 'Your Accomplishment in Invention, Innovation, and Creative Works has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invention $invention_innovation_creative)
    {
        $invention_innovation_creative->delete();
        InventionDocument::where('invention_id', $invention_innovation_creative->id)->delete();
        return redirect()->route('faculty.invention-innovation-creative.index')->with('edit_iicw_success', 'Your Accomplishment in Invention, Innovation, and Creative Works has been deleted.');
    }

    public function removeDoc($filename){
        InventionDocument::where('filename', $filename)->delete();
        Storage::delete('documents/'.$filename);
        return true;
    }
}
