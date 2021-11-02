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

class UtilizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $researchutilizations = ResearchUtilization::where('research_code', $research->research_code)->get();

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
        return view('research.utilization.index', compact('research', 'researchutilizations', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 6)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
            ->select('research_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();

            $has_completion = ResearchComplete::where('research_code', $research->research_code)->first();

            $completed = 0;
            if ($has_completion) {
                $completed = 1;
            }
            else {
                $completed = 0;
            }
            
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
        return view('research.utilization.create', compact('researchFields', 'research', 'completed', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
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

        $id = ResearchUtilization::insertGetId($input);

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
                        'research_form_id' => 6,
                        'research_utilization_id' => $id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.utilization.index', $research->research_code)->with('success', 'Research Utilization Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research, ResearchUtilization $utilization)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 6)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_utilization_id', $utilization->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchUtilization::find($utilization->id);

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
        return view('research.utilization.show', compact('research', 'researchFields', 'values', 'researchDocuments', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchUtilization $utilization)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 6)
        ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
        ->select('research_fields.*', 'field_types.name as field_type_name')
        ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_utilization_id', $utilization->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchUtilization::find($utilization->id);

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

        return view('research.utilization.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchUtilization $utilization)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $utilization->update($input);

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
                        'research_form_id' => 6,
                        'research_utilization_id' => $id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.utilization.index', $research->research_code)->with('success', 'Research Utilization Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research, ResearchUtilization $utilization)
    {
        $utilization->delete();
        return redirect()->route('research.utilization.index', $research->research_code)->with('success', 'Research Utilization Deleted Successfully');
    }
}
