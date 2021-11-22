<?php

namespace App\Http\Controllers\Inventions;

use App\Models\Invention;
use Illuminate\Http\Request;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\FormBuilder\InventionField;

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
        return view('inventions.index', compact('inventions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inventionsFields = InventionField::where('invention_fields.invention_form_id', 1)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'invention_fields.field_type_id')
            ->select('invention_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();
        // dd($inventionsfields);

        $departments = Department::all();
        $colleges = College::all();
        return view('inventions.create', compact('inventionsFields', 'departments', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', '_method', 'document']);

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
                        'invention_id' => $esConsultant->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('faculty.invention-innovation-creative.index')->with('edit_esconsultant_success', 'Your Accomplishment in Expert Service as Consultant has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invention $invention_innovation_creative)
    {
        $inventionsFields = InventionField::where('invention_fields.invention_form_id', 1)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'invention_fields.field_type_id')
            ->select('invention_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();

        $inventionDocuments = InventionDocument::where('expert_service_consultant_id', $invention_innovation_creative->id)->get()->toArray();
        
        return view('inventions.show', compact('invention_innovation_creative', 'inventionsFields', 'inventionDocuments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Invention $invention_innovation_creative)
    {
        $inventionsFields = InventionField::where('invention_fields.invention_form_id', 1)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'invention_fields.field_type_id')
            ->select('invention_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();

        $inventionDocuments = InventionDocument::where('expert_service_consultant_id', $invention_innovation_creative->id)->get()->toArray();
        
        return view('inventions.edit', compact('invention_innovation_creative', 'inventionsFields', 'inventionDocuments'));
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
        $input = $request->except(['_token', '_method', 'document']);

        $inventions_innovation_creative->update($input);

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
                        'invention_id' => $expert_service_as_consultant->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('faculty.invention-innovation-creative.index')->with('edit_esconsultant_success', 'Your accomplishment in Invention, Innovation, and Creative Work has been updated.');
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
        return redirect()->route('faculty.invention-innovation-creative.index')->with('edit_esconsultant_success', 'Your accomplishment in Invention, Innovation, and Creative Work has been deleted.');
    }
}
