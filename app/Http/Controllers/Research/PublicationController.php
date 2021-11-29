<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ResearchField;
use App\Models\FormBuilder\DropdownOption;
use App\Models\ResearchComplete;
use App\Models\ResearchPresentation;
use App\Models\ResearchPublication;
use App\Models\ResearchUtilization;
use App\Models\ResearchCopyright;
use App\Models\ResearchCitation;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchPublication::class);

        $researchFields = DB::select("CALL get_research_fields_by_form_id('3')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 3)->get()->toArray();
        $research = Research::join('dropdown_options', 'dropdown_options.id', 'research.status')
            ->where('research_code', $research->research_code)->where('user_id', auth()->id())
            ->select('research.*', 'dropdown_options.name as status_name')->first();
                
        $values = ResearchPublication::where('research_code', $research->research_code)->first();
        if($values == null){
            return redirect()->route('research.show', $research->research_code);
        }

        $values = collect($values->toArray());
        $values = $values->except(['research_code']);
        $values = $values->toArray();

        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $value = array_merge($value, $values);

        return view('research.publication.index', compact('research', 'researchFields', 'value', 'researchDocuments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchPublication::class);

        $researchFields = DB::select("CALL get_research_fields_by_form_id('3')");
        
        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $presentationChecker = ResearchPresentation::where('research_code', $research->research_code)->first();

        if($presentationChecker == null){
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 30)->first();
        }
        else{
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 31)->first();
        }

        return view('research.publication.create', compact('researchFields', 'research', 'researchStatus', 'value'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchPublication::class);

        $request->validate([
            'status' => 'required',
            'publisher' => 'required',
            'journal_name' => 'required',
            // 'editor' => '',
            'level' => 'required',
            'publish_date' => 'required|date', 
            // 'issn' => '',
            'page' => 'required',
            'volume' => 'numeric',
            'issue' => 'numeric',
            // 'indexing_platform' => '',
            'description' => 'required',
        ]);

        $input = $request->except(['_token', '_method', 'status', 'document']);


        $presentationChecker = ResearchPresentation::where('research_code', $research->research_code)->first();

        if($presentationChecker == null){
            $researchStatus = 30;
        }
        else{
            $researchStatus = 31;
        }
        
        $research->update([
            'status' => $researchStatus
        ]);


        ResearchPublication::create($input);

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
                        'research_form_id' => 3,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.publication.index', $research->id)->with('success', 'Research Published Successfully');
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
    public function edit(Research $research, ResearchPublication $publication)
    {
        $this->authorize('update', ResearchPublication::class);

        $researchFields = DB::select("CALL get_research_fields_by_form_id('3')");
    
        // $research = array_merge($research->toArray(), $publication->toArray());
        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 3)->get()->toArray();

        $value = $research->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();
        $value = array_merge($value, $publication->toArray());

        $presentationChecker = ResearchPresentation::where('research_code', $research->research_code)->first();

        if($presentationChecker == null){
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 30)->first();
        }
        else{
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 31)->first();
        }
        
        return view('research.publication.edit', compact('research', 'researchFields', 'researchDocuments', 'value', 'researchStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchPublication $publication)
    {
        $this->authorize('update', ResearchPublication::class);

        $request->validate([
            'status' => 'required',
            'publisher' => 'required',
            'journal_name' => 'required',
            // 'editor' => '',
            'level' => 'required',
            'publish_date' => 'required|date', 
            // 'issn' => '',
            'page' => 'required',
            'volume' => 'numeric',
            'issue' => 'numeric',
            // 'indexing_platform' => '',
            'description' => 'required',
        ]);

        $input = $request->except(['_token', '_method', 'status', 'document']);

        $publication->update($input);

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
                        'research_form_id' => 3,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.publication.index', $research->id)->with('success', 'Research Published Successfully');
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
