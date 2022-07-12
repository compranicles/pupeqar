<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Storage,
};
use App\Models\{
    Employee,
    Partnership,
    PartnershipDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ExtensionProgramField,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};
use App\Services\DateContentService;

class PartnershipController extends Controller
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
        $this->authorize('viewAny', Partnership::class);

        $currentQuarterYear = Quarter::find(1);

        $partnerships = Partnership::where('user_id', auth()->id())
                            ->join('colleges', 'colleges.id', 'partnerships.college_id')
                            ->select(DB::raw('partnerships.*, colleges.name as college_name'))
                            ->orderBy('partnerships.updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($partnerships as $partnership) {
            if (LockController::isLocked($partnership->id, 13))
                $submissionStatus[13][$partnership->id] = 1;
            else
                $submissionStatus[13][$partnership->id] = 0;
            if (empty($reportdata->getDocuments(13, $partnership->id)))
                $submissionStatus[13][$partnership->id] = 2;
        }

        return view('extension-programs.partnership.index', compact('partnerships', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Partnership::class);

        if(ExtensionProgramForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $partnershipFields = DB::select("CALL get_extension_program_fields_by_form_id('5')");

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('extension-programs.partnership.create', compact('partnershipFields', 'colleges' , 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Partnership::class);

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");

        $currentQuarterYear = Quarter::find(1);

        $is_submit = '';
        if($request->has('o')){
            $is_submit = 'yes';
        }

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        if(ExtensionProgramForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document', 'o']);

        $partnership = Partnership::create($input);
        $partnership->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'P-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    PartnershipDocument::create([
                        'partnership_id' => $partnership->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added a partnership/linkage/network "'.$request->input('title_of_partnership').'".');

        if($is_submit == 'yes'){
            return redirect(url('submissions/check/13/'.$partnership->id).'?r=partnership.index');
        }

        return redirect()->route('partnership.index')->with('partnership_success', 'Partnership, linkages, and network has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Partnership $partnership)
    {
        $this->authorize('view', Partnership::class);

        if (auth()->id() !== $partnership->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $partnershipFields = DB::select("CALL get_extension_program_fields_by_form_id('5')");

        $documents = PartnershipDocument::where('partnership_id', $partnership->id)->get()->toArray();

        $values = $partnership->toArray();

        return view('extension-programs.partnership.show', compact('partnership', 'partnershipFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Partnership $partnership)
    {
        $this->authorize('update', Partnership::class);

        if (auth()->id() !== $partnership->user_id)
            abort(403);

        if(LockController::isLocked($partnership->id, 13)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $partnershipFields = DB::select("CALL get_extension_program_fields_by_form_id('5')");

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $partnership->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $partnership->toArray();

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $documents = PartnershipDocument::where('partnership_id', $partnership->id)->get()->toArray();

        return view('extension-programs.partnership.edit', compact('partnership', 'partnershipFields', 'documents', 'values', 'colleges', 'collegeAndDepartment', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partnership $partnership)
    {
        $this->authorize('update', Partnership::class);

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");

        $is_submit = '';
        if($request->has('o')){
            $is_submit = 'yes';
        }

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        if(ExtensionProgramForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $input = $request->except(['_token', '_method', 'document', 'o']);

        $partnership->update(['description' => '-clear']);

        $partnership->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'P-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    PartnershipDocument::create([
                        'partnership_id' => $partnership->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated the partnership/linkage/network "'.$partnership->title_of_partnership.'".');

        if($is_submit == 'yes'){
            return redirect(url('submissions/check/13/'.$partnership->id).'?r=partnership.index');
        }

        return redirect()->route('partnership.index')->with('partnership_success', 'Partnership, linkages, and network has been updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partnership $partnership)
    {
        $this->authorize('delete', Partnership::class);

        if(LockController::isLocked($partnership->id, 13)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        PartnershipDocument::where('partnership_id', $partnership->id)->delete();
        $partnership->delete();

        \LogActivity::addToLog('Had deleted the partnership/linkage/network "'.$partnership->title_of_partnership.'".');

        return redirect()->route('partnership.index')->with('partnership_success', 'Partnership, linkages, and network has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', Partnership::class);

        if(ExtensionProgramForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        PartnershipDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a partnership/linkage/network.');

        return true;
    }
}
