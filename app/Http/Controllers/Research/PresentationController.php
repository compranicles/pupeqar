<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchDocument;
use App\Http\Controllers\Controller;
use App\Models\ResearchPresentation;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ResearchField;
use App\Models\FormBuilder\DropdownOption;

class PresentationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 4)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 4)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                // dd($research);    
        $values = ResearchPresentation::where('research_code', $research->research_code)->first();
        if($values == null){
            return redirect()->route('research.show', $research->research_code);
        }
        // $values = array_merge($research->toArray(), $values->toArray());
        
        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();
        return view('research.presentation.index', compact('research', 'researchFields', 'value', 'researchDocuments', 'researchStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 4)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
            ->select('research_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();
        // $research = $research->first()->except('description');
        // $research = except($research['description']);
            // dd($research);
        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 29)->first();

        return view('research.presentation.create', compact('researchFields', 'research', 'researchStatus', 'value'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $input = $request->except(['_token', '_method', 'status', 'document']);

        $research->update([
            'status' => $request->input('status')
        ]);
        // dd($input);

        ResearchPresentation::create($input);

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
                        'research_form_id' => 4,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.presentation.index', $research->research_code)->with('success', 'Research Presentation Added Successfully');
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
    public function edit( Research $research, ResearchPresentation $presentation)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 4)->where('is_active', 1)
        ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
        ->select('research_fields.*', 'field_types.name as field_type_name')
        ->orderBy('order')->get();
    
        $research = array_merge($research->toArray(), $presentation->toArray());
        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 4)->get()->toArray();

        $value = $research;
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research['status'])->first();
        return view('research.presentation.edit', compact('research', 'researchFields', 'researchDocuments', 'value', 'researchStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchPresentation $presentation)
    {
        $input = $request->except(['_token', '_method', 'status', 'document']);

        $research->update([
            'status' => $request->input('status')
        ]);


        $presentation->update($input);

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
                        'research_form_id' => 4,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.presentation.index', $research->research_code)->with('success', 'Research Presentation Updated Successfully');
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
