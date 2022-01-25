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

class CopyrightedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchCopyright::class);

        $researchFields = DB::select("CALL get_research_fields_by_form_id('7')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 7)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchCopyright::where('research_code', $research->research_code)->first();
        if($values == null){
            return redirect()->route('research.copyrighted.create', $research->id);
        }
        $values = array_merge($research->toArray(), $values->toArray());
    
        return view('research.copyrighted.index', compact('research', 'researchFields', 'values', 'researchDocuments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchCopyright::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('7')");
           
        return view('research.copyrighted.create', compact('researchFields', 'research'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchCopyright::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_parts = explode('-', $research->completion_date);

        $request->validate([
            'copyright_year' => 'after_or_equal:'.$date_parts[0],
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $copyright = ResearchCopyright::create($input);
        $copyright->update([
            'research_id' => $research->id,
        ]);

        $string = str_replace(' ', '-', $copyright->description); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RCP-'.$request->input('research_code').'-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 7,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.copyrighted.index', $research->id)->with('success', 'Research copyright has been added.');
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
        $this->authorize('update', ResearchCopyright::class);
        if(LockController::isLocked($copyrighted->id, 7)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('7')");

        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 7)->get()->toArray();

        $value = array_merge($research->toArray(), $copyrighted->toArray());
        return view('research.copyrighted.edit', compact('research', 'researchFields', 'value', 'researchDocuments'));
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
        $this->authorize('update', ResearchCopyright::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_parts = explode('-', $research->completion_date);

        $request->validate([
            'copyright_year' => 'after_or_equal:'.$date_parts[0],
        ]);
        
        $input = $request->except(['_token', '_method', 'document']);

        $copyrighted->update(['description' => '-clear']);

        $copyrighted->update($input);

        $string = str_replace(' ', '-', $copyrighted->description); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RCR-'.$request->input('research_code').'-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 7,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.copyrighted.index', $research->id)->with('success', 'Research copyright has been updated.');
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
