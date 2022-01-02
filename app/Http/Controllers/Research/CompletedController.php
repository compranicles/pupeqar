<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Support\Arr;
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
use App\Models\FormBuilder\DropdownOption;

class CompletedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        // dd($research);
        $this->authorize('viewAny', ResearchComplete::class);

        $researchFields = DB::select("CALL get_research_fields_by_form_id('2')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 2)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
    
                
        $values = ResearchComplete::where('research_code', $research->research_code)->first();
        // dd($values);
        if($values == null){
            return redirect()->route('research.completed.create', $research->id);
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
        return view('research.completed.index', compact('research', 'researchFields', 'value', 'researchDocuments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchComplete::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('2')");

        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 28)->first();

        return view('research.completed.create', compact('research', 'researchFields', 'researchStatus', 'value'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchComplete::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'completion_date' => 'after_or_equal:start_date|required_if:status, 28',
        ]);

        $input = $request->except(['_token', '_method', 'research_code', 'description', 'document']);
        $input = Arr::add($input, 'status', 28);
        $research->update($input);


        $completed = ResearchComplete::create([
            'research_code' => $research->research_code,
            'research_id' => $research->id
        ]);
        $completed->update([
            'description' => $request->input('description'),
        ]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RCP-'.$request->input('research_code').'-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 2,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.completed.index', $research->id)->with('success', 'Research completetion has been added.');
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
        $this->authorize('update', ResearchComplete::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('2')");
        
        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 2)->get()->toArray();
        
        $value = $research->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();
        $value = array_merge($value, $completed->toArray());
        
        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research['status'])->first();

        return view('research.completed.edit', compact('research', 'researchFields', 'researchDocuments', 'value', 'researchStatus'));
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
        $this->authorize('update', ResearchComplete::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'completion_date' => 'after_or_equal:start_date|required_if:status, 28',
        ]);
        
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
                    $fileName = 'RCP-'.$request->input('research_code').'-'.$completed->description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_id' => $research->id,
                        'research_form_id' => 2,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        return redirect()->route('research.completed.index', $research->id)->with('success', 'Research completetion has been updated.');
        

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
