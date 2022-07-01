<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Models\Dean;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\CommunityEngagement;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\CommunityEngagementDocument;
use App\Http\Controllers\StorageFileController;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class CommunityEngagementController extends Controller
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
    public function index()
    {
        $currentQuarterYear = Quarter::find(1);

        $communityEngagements = CommunityEngagement::where('user_id', auth()->id())
                                ->join('colleges', 'colleges.id', 'community_engagements.college_id')
                                ->select(DB::raw('community_engagements.*, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($communityEngagements as $communityEngagement) {
            if (LockController::isLocked($communityEngagement->id, 37))
                $submissionStatus[37][$communityEngagement->id] = 1;
            else
                $submissionStatus[37][$communityEngagement->id] = 0;
            if (empty($reportdata->getDocuments(37, $communityEngagement->id)))
                $submissionStatus[37][$communityEngagement->id] = 2;
        }

        return view('extension-programs.community-engagements.index', compact('communityEngagements', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(ExtensionProgramForm::where('id', 9)->pluck('is_active')->first() == 0)
            return view('inactive');
        $communityEngagementFields = DB::select("CALL get_extension_program_fields_by_form_id('9')");

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('extension-programs.community-engagements.create', compact('communityEngagementFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        if(ExtensionProgramForm::where('id', 9)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $communityEngagement = CommunityEngagement::create($input);
        $communityEngagement->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'CEC-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    CommunityEngagementDocument::create([
                        'community_engagement_id' => $communityEngagement->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added a community engagement conducted by college/department.');

        return redirect()->route('community-engagement.index')->with('community_success', 'Community engagement conducted by college/department has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CommunityEngagement $communityEngagement)
    {
        if (auth()->id() !== $communityEngagement->user_id)
        abort(403);

        if(ExtensionProgramForm::where('id', 9)->pluck('is_active')->first() == 0)
            return view('inactive');
        $communityEngagementFields = DB::select("CALL get_extension_program_fields_by_form_id('9')");

        $documents = CommunityEngagementDocument::where('community_engagement_id', $communityEngagement->id)->get()->toArray();

        $values = $communityEngagement->toArray();

        return view('extension-programs.community-engagements.show', compact('communityEngagement', 'communityEngagementFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CommunityEngagement $communityEngagement)
    {
        if (auth()->id() !== $communityEngagement->user_id)
            abort(403);

        if(LockController::isLocked($communityEngagement->id, 37)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 9)->pluck('is_active')->first() == 0)
            return view('inactive');
        $communityEngagementFields = DB::select("CALL get_extension_program_fields_by_form_id('9')");

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $communityEngagement->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $communityEngagement->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        $documents = CommunityEngagementDocument::where('community_engagement_id', $communityEngagement->id)->get()->toArray();

        return view('extension-programs.community-engagements.edit', compact('communityEngagement', 'communityEngagementFields', 'documents', 'values', 'colleges', 'collegeAndDepartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommunityEngagement $communityEngagement)
    {
        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));

        $request->merge([
            'from' => $from,
            'to' => $to,
        ]);


        if(ExtensionProgramForm::where('id', 9)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $communityEngagement->update(['description' => '-clear']);

        $communityEngagement->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'CEC-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    CommunityEngagementDocument::create([
                        'community_engagement_id' => $communityEngagement->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated a community engagement conducted by college/department.');


        return redirect()->route('community-engagement.index')->with('community_success', 'Community engagement conducted by college/department has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommunityEngagement $communityEngagement)
    {
        if(LockController::isLocked($communityEngagement->id, 37)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 9)->pluck('is_active')->first() == 0)
            return view('inactive');
        CommunityEngagementDocument::where('community_engagement_id', $communityEngagement->id)->delete();
        $communityEngagement->delete();

        \LogActivity::addToLog('Had deleted a community engagement conducted by college/department.');

        return redirect()->route('community_engagement.index')->with('community_success', 'Community engagement conducted by college/department has been deleted.');
    }

    public function removeDoc($filename){
        // $this->authorize('delete', Mobility::class);

        if(ExtensionProgramForm::where('id', 9)->pluck('is_active')->first() == 0)
            return view('inactive');
        CommunityEngagementDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a community engagement.');

        return true;
    }
}
