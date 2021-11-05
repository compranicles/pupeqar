<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ResearchField;
use App\Models\ResearchComplete;
use App\Models\ResearchPresentation;
use App\Models\ResearchPublication;
use App\Models\ResearchUtilization;
use App\Models\ResearchCopyright;
use App\Models\ResearchCitation;

class CopyrightedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 7)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 7)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchCopyright::where('research_code', $research->research_code)->first();
        if($values == null){
            return redirect()->route('research.copyrighted.create', $research->research_code);
        }
        $values = array_merge($research->toArray(), $values->toArray());
        // dd($values);

        $has_completion = ResearchComplete::where('research_code', $research->research_code)->first();
        $has_presentation = ResearchPresentation::where('research_code', $research->research_code)->first();
        $has_publication = ResearchPublication::where('research_code', $research->research_code)->first();
        $has_citation = ResearchCitation::where('research_code', $research->research_code)->first();
        $has_copyright = ResearchCopyright::where('research_code', $research->research_code)->first();
        $has_utilization = ResearchUtilization::where('research_code', $research->research_code)->first();
        $utilized = 0;
        $completed = 0;
        $presented = 0;
        $published = 0;
        $cited = 0;
        $copyrighted = 0;

        if ($has_utilization) {
            $utilized = 1;
        }
        else {
            $utilized = 0;
        }

        if ($has_completion) {
            $completed = 1;
        }
        else {
            $completed = 0;
        }

        if ($has_presentation) {
            $presented = 1;
        }
        else {
            $presented = 0;
        }

        if ($has_publication) {
            $published = 1;
        }
        else {
            $published = 0;
        }

        if ($has_copyright) {
            $copyrighted = 1;
        }
        else {
            $copyrighted = 0;
        }

        if ($has_citation) {
            $cited = 1;
        }
        else {
            $cited= 0;
        }
        return view('research.copyrighted.index', compact('research', 'researchFields', 'values', 'researchDocuments', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 7)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
            ->select('research_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();
        // $research = $research->first()->except('description');
        // $research = except($research['description']);
            // dd($research);

            $has_completion = ResearchComplete::where('research_code', $research->research_code)->first();
            $has_presentation = ResearchPresentation::where('research_code', $research->research_code)->first();
            $has_publication = ResearchPublication::where('research_code', $research->research_code)->first();
            $has_citation = ResearchCitation::where('research_code', $research->research_code)->first();
            $has_copyright = ResearchCopyright::where('research_code', $research->research_code)->first();
            $has_utilization = ResearchUtilization::where('research_code', $research->research_code)->first();
            $utilized = 0;
            $completed = 0;
            $presented = 0;
            $published = 0;
            $cited = 0;
            $copyrighted = 0;
    
            if ($has_utilization) {
                $utilized = 1;
            }
            else {
                $utilized = 0;
            }
    
            if ($has_completion) {
                $completed = 1;
            }
            else {
                $completed = 0;
            }
    
            if ($has_presentation) {
                $presented = 1;
            }
            else {
                $presented = 0;
            }
    
            if ($has_publication) {
                $published = 1;
            }
            else {
                $published = 0;
            }
    
            if ($has_copyright) {
                $copyrighted = 1;
            }
            else {
                $copyrighted = 0;
            }
    
            if ($has_citation) {
                $cited = 1;
            }
            else {
                $cited= 0;
            }
        return view('research.copyrighted.create', compact('researchFields', 'research', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $input = $request->except(['_token', '_method', 'document']);

        ResearchCopyright::create($input);

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
                        'research_form_id' => 7,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.copyrighted.index', $research->research_code)->with('success', 'Research Copyrighted Added Successfully');
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
    public function edit(Research $research, ResearchCopyright $copyrighted)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 7)->where('is_active', 1)
        ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
        ->select('research_fields.*', 'field_types.name as field_type_name')
        ->orderBy('order')->get();
    
        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 7)->get()->toArray();
        

        $has_completion = ResearchComplete::where('research_code', $research->research_code)->first();
        $has_presentation = ResearchPresentation::where('research_code', $research->research_code)->first();
        $has_publication = ResearchPublication::where('research_code', $research->research_code)->first();
        $has_citation = ResearchCitation::where('research_code', $research->research_code)->first();
        $has_copyright = ResearchCopyright::where('research_code', $research->research_code)->first();
        $has_utilization = ResearchUtilization::where('research_code', $research->research_code)->first();
        $utilized = 0;
        $completed = 0;
        $presented = 0;
        $published = 0;
        $cited = 0;
        $varcopyrighted = 0;

        if ($has_utilization) {
            $utilized = 1;
        }
        else {
            $utilized = 0;
        }

        if ($has_completion) {
            $completed = 1;
        }
        else {
            $completed = 0;
        }

        if ($has_presentation) {
            $presented = 1;
        }
        else {
            $presented = 0;
        }

        if ($has_publication) {
            $published = 1;
        }
        else {
            $published = 0;
        }

        if ($has_copyright) {
            $varcopyrighted = 1;
        }
        else {
            $varcopyrighted = 0;
        }

        if ($has_citation) {
            $cited = 1;
        }
        else {
            $cited= 0;
        }

        $value = array_merge($research->toArray(), $copyrighted->toArray());
        return view('research.copyrighted.edit', compact('research', 'researchFields', 'value', 'researchDocuments', 'utilized', 'completed', 'presented', 'published', 'varcopyrighted', 'cited'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchCopyright $copyrighted)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $copyrighted->update($input);

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
                        'research_form_id' => 7,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.copyrighted.index', $research->research_code)->with('success', 'Research Copyrighted Updated Successfully');
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
