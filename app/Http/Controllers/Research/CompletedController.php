<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Research,
    ResearchCitation,
    ResearchComplete,
    ResearchCopyright,
    ResearchDocument,
    ResearchPresentation,
    ResearchPublication,
    ResearchUtilization,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ResearchField,
    FormBuilder\ResearchForm,
    Maintenance\Quarter,
};

class CompletedController extends Controller
{
    protected $storageFileController;

    public function __construct(StorageFileController $storageFileController){
        $this->storageFileController = $storageFileController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchComplete::class);

        $researchFields = DB::select("CALL get_research_fields_by_form_id('2')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 2)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        $values = ResearchComplete::where('research_code', $research->research_code)->first();

        if($values == null){
            return redirect()->route('research.completed.create', $research->id);
        }
        $values = collect($values->toArray());
        $values = $values->except(['research_code']);
        $values = $values->toArray();

        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description']);
        $value = $value->toArray();

        $value = array_merge($value, $values);

        $submissionStatus = [];
        $reportdata = new ReportDataController;
            if (LockController::isLocked($value['id'], 2))
                $submissionStatus[2][$value['id']] = 1;
            else
                $submissionStatus[2][$value['id']] = 0;
            if (empty($reportdata->getDocuments(2, $value['id'])))
                $submissionStatus[2][$value['id']] = 2;

        return view('research.completed.index', compact('research', 'researchFields',
            'value', 'researchDocuments', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchComplete::class);

        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

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

        $completion_date = date("Y-m-d", strtotime($request->input('completion_date')));
        $currentQuarterYear = Quarter::find(1);

        $is_submit = '';
        if($request->has('o')){
            $is_submit = 'yes';
        }

        $request->merge([
            'completion_date' => $completion_date,
        ]);

        $request->validate([
            'completion_date' => 'after_or_equal:start_date|required_if:status, 28',
        ]);

        $input = $request->except(['_token', '_method', 'research_code', 'description', 'document', 'o']);
        $input = Arr::add($input, 'status', 28);
        Research::where('research_code', $research->research_code)->update($input);

        $completed = ResearchComplete::create([
            'research_code' => $research->research_code,
            'research_id' => $research->id,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
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
                    $fileName = 'RCP-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
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

        \LogActivity::addToLog('Had marked the research "'.$research->title.'" as completed.');

        if($is_submit == 'yes'){
            return redirect(url('submissions/check/2/'.$research->id).'?r=research.completed.index');
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

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(LockController::isLocked($completed->id, 2)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

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

        $completion_date = date("Y-m-d", strtotime($request->input('completion_date')));

        $is_submit = '';
        if($request->has('o')){
            $is_submit = 'yes';
        }

        $request->merge([
            'completion_date' => $completion_date,
        ]);

        $request->validate([
            'completion_date' => 'after_or_equal:start_date|required_if:status, 28',
        ]);

        $input = $request->except(['_token', '_method', 'research_code', 'description', 'document', 'o']);

        Research::where('research_code', $research->research_code)->update($input);

        $completed->update(['description' => '-clear']);

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
                    $fileName = 'RCP-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_id' => $research->id,
                        'research_code' => $request->input('research_code'),
                        'research_form_id' => 2,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated the completion details of research "'.$research->title.'".');

        if($is_submit == 'yes'){
            return redirect(url('submissions/check/2/'.$research->id).'?r=research.completed.index');
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
