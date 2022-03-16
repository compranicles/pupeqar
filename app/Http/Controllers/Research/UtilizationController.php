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
use App\Http\Controllers\Maintenances\LockController;

class UtilizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchUtilization::class);;

        $researchutilizations = ResearchUtilization::where('research_code', $research->research_code)->orderBy('updated_at', 'desc')->get();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
        return view('research.utilization.index', compact('research', 'researchutilizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchUtilization::class);
        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('6')");
        $research = collect($research);
        $research = $research->except(['description']);
        return view('research.utilization.create', compact('researchFields', 'research'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchUtilization::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $input = $request->except(['_token', '_method', 'document']);

        $utilization = ResearchUtilization::create($input);

        ResearchUtilization::where('id', $utilization->id)->update([
            'research_id' => $research->id,
        ]);

        $string = str_replace(' ', '-', $request->input('description')); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RU-'.$request->input('research_code').'-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 6,
                        'research_utilization_id' => $id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Research utilization of "'.$research->title.'" was added.');


        return redirect()->route('research.utilization.index', $research->id)->with('success', 'Research utilization has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research, ResearchUtilization $utilization)
    {
        $this->authorize('view', ResearchUtilization::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('6')");

        $researchDocuments = ResearchDocument::where('research_utilization_id', $utilization->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchUtilization::find($utilization->id);

        $values = array_merge($research->toArray(), $values->toArray());

        return view('research.utilization.show', compact('research', 'researchFields', 'values', 'researchDocuments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchUtilization $utilization)
    {
        $this->authorize('update', ResearchUtilization::class);
        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(LockController::isLocked($utilization->id, 6)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('6')");

        $researchDocuments = ResearchDocument::where('research_utilization_id', $utilization->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
            
                
        $values = ResearchUtilization::find($utilization->id);

        $values = array_merge($research->toArray(), $values->toArray());

        return view('research.utilization.edit', compact('research', 'researchFields', 'values', 'researchDocuments'));
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
        $this->authorize('update', ResearchUtilization::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        
        $input = $request->except(['_token', '_method', 'document']);

        $utilization->update(['description' => '-clear']);

        $utilization->update($input);

        $string = str_replace(' ', '-', $utilization->description); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RU-'.$request->input('research_code').'-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 6,
                        'research_utilization_id' => $utilization->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Research utilization of "'.$research->title.'" was updated.');

        return redirect()->route('research.utilization.show', [$research->id, $utilization->id])->with('success', 'Research Utilization Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research, ResearchUtilization $utilization)
    {
        $this->authorize('delete', ResearchUtilization::class);
        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(LockController::isLocked($utilization->id, 6)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $utilization->delete();

        \LogActivity::addToLog('Research utilization of "'.$research->title.'" was deleted.');

        return redirect()->route('research.utilization.index', $research->id)->with('success', 'Research utilization has been deleted.');
    }
}
