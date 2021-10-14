<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchComplete;
use App\Models\ResearchDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ResearchField;

class CompletedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 2)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 2)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchComplete::where('research_code', $research->research_code)->first();

        if($values == null){
            return redirect()->route('research.show', $research->research_code);
        }
        $values = array_merge($research->toArray(), $values->toArray());


        // dd($values);
        return view('research.completed.index', compact('research', 'researchFields', 'values', 'researchDocuments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 2)->where('is_active', 1)
        ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
        ->select('research_fields.*', 'field_types.name as field_type_name')
        ->orderBy('order')->get();
        $research->get()->except('description');
        return view('research.completed.create', compact('research', 'researchFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $input = $request->except(['_token', '_method', 'research_code', 'description', 'document']);

        $research->update($input);


        ResearchComplete::create([
            'research_code' => $research->research_code,
            'description' => $request->input('description')
        ]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$request->input('research_code').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_form_id' => 2,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.completed.index', $research->research_code)->with('success', 'Research Completed Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchComplete $completed)
    {   
        $researchFields = ResearchField::where('research_fields.research_form_id', 2)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
            ->select('research_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();
        
        $research = array_merge($research->toArray(), $completed->toArray());
        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 2)->get()->toArray();

        return view('research.completed.edit', compact('research', 'researchFields', 'researchDocuments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchComplete $completed)
    {
        $input = $request->except(['_token', '_method', 'research_code', 'description', 'document']);

        $research->update($input);


        $completed->update([
            'research_code' => $research->research_code,
            'description' => $request->input('description')
        ]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$request->input('research_code').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_form_id' => 2,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.completed.index', $research->research_code)->with('success', 'Research Completed Successfully');
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
