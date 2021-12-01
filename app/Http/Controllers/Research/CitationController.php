<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchCitation;
use App\Models\ResearchComplete;
use App\Models\ResearchDocument;
use App\Models\ResearchCopyright;
use Illuminate\Support\Facades\DB;
use App\Models\ResearchPublication;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\ResearchPresentation;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ResearchForm;
use App\Models\FormBuilder\ResearchField;

class CitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchCitation::class);
 

        $researchcitations = ResearchCitation::where('research_code', $research->research_code)->get();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
        return view('research.citation.index', compact('research', 'researchcitations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('5')");

        return view('research.citation.create', compact('researchFields', 'research'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'article_title' => 'required',
            'article_author' => 'required',
            // 'journal_title' => '',
            // 'journal_publisher' => '',
            'volume' => 'numeric',
            'issue' => 'numeric',
            'page' => 'required|numeric',
            // 'year' => ''
            // 'indexing_platform' => '',
            // 'description' => 'required',
        ]);

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

        return redirect()->route('research.citation.index', $research->id)->with('success', 'Research Citation Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research, ResearchCitation $citation)
    {
        $this->authorize('view', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('5')");

        $researchDocuments = ResearchDocument::where('research_citation_id', $citation->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchCitation::find($citation->id);

        $values = array_merge($research->toArray(), $values->toArray());
        return view('research.citation.show', compact('research', 'researchFields', 'values', 'researchDocuments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchCitation $citation)
    {
        $this->authorize('update', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('5')");

        $researchDocuments = ResearchDocument::where('research_citation_id', $citation->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchCitation::find($citation->id);

        $values = array_merge($research->toArray(), $values->toArray());

        return view('research.citation.edit', compact('research', 'researchFields', 'values', 'researchDocuments'));

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
        $this->authorize('update', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'article_title' => 'required',
            'article_author' => 'required',
            // 'journal_title' => '',
            // 'journal_publisher' => '',
            'volume' => 'numeric',
            'issue' => 'numeric',
            'page' => 'required|numeric',
            // 'year' => ''
            // 'indexing_platform' => '',
            // 'description' => 'required',
        ]);

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

        return redirect()->route('research.citation.index', $research->id)->with('success', 'Research Citation Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research, ResearchCitation $citation)
    {
        $this->authorize('delete', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $citation->delete();

        return redirect()->route('research.citation.index', $research->id)->with('success', 'Research Citation Deleted Successfully');
    }
}
