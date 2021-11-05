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

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 3)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 3)->get()->toArray();
        $research = Research::join('dropdown_options', 'dropdown_options.id', 'research.status')->where('research_code', $research->research_code)
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

        // $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();
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
        return view('research.publication.index', compact('research', 'researchFields', 'value', 'researchDocuments', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 3)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
            ->select('research_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();
        
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

        return view('research.publication.create', compact('researchFields', 'research', 'researchStatus', 'value', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
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

        return redirect()->route('research.publication.index', $research->research_code)->with('success', 'Research Published Successfully');
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
        $researchFields = ResearchField::where('research_fields.research_form_id', 3)->where('is_active', 1)
        ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
        ->select('research_fields.*', 'field_types.name as field_type_name')
        ->orderBy('order')->get();
    
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
        return view('research.publication.edit', compact('research', 'researchFields', 'researchDocuments', 'value', 'researchStatus', 'utilized', 'completed', 'presented', 'published', 'copyrighted', 'cited'));
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

        return redirect()->route('research.publication.index', $research->research_code)->with('success', 'Research Published Successfully');
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
