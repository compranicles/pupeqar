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

class CitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $researchcitations = ResearchCitation::where('research_code', $research->research_code)->get();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
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
        return view('research.citation.index', compact('research', 'researchcitations', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 5)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
            ->select('research_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();

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
        return view('research.citation.create', compact('researchFields', 'research', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
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

        $id = ResearchCitation::insertGetId($input);

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
                        'research_form_id' => 5,
                        'research_citation_id' => $id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.citation.index', $research->research_code)->with('success', 'Research Citation Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research, ResearchCitation $citation)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 5)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_citation_id', $citation->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchCitation::find($citation->id);

        $values = array_merge($research->toArray(), $values->toArray());

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
        return view('research.citation.show', compact('research', 'researchFields', 'values', 'researchDocuments', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchCitation $citation)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 5)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_citation_id', $citation->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchCitation::find($citation->id);

        $values = array_merge($research->toArray(), $values->toArray());

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

        return view('research.citation.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchCitation $citation)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $citation->update($input);

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
                        'research_form_id' => 5,
                        'research_citation_id' => $citation->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.citation.index', $research->research_code)->with('success', 'Research Citation Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research, ResearchCitation $citation)
    {
        $citation->delete();

        return redirect()->route('research.citation.index', $research->research_code)->with('success', 'Research Citation Deleted Successfully');
    }
}
