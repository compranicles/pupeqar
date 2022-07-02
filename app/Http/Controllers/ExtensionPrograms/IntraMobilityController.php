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
    Chairperson,
    Dean,
    Employee,
    IntraMobility,
    IntraMobilityDocument,
    TemporaryFile,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};
use App\Services\DateContentService;

class IntraMobilityController extends Controller
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
        $this->authorize('manage', IntraMobility::class);

        $currentQuarterYear = Quarter::find(1);

        $intraMobilities = IntraMobility::where('user_id', auth()->id())
                                ->join('colleges', 'colleges.id', 'intra_mobilities.college_id')
                                ->select(DB::raw('intra_mobilities.*, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($intraMobilities as $intraMobility) {
            if($intraMobility->classification_of_person == '298'){
                if (LockController::isLocked($intraMobility->id, 36))
                    $submissionStatus[36][$intraMobility->id] = 1;
                else
                    $submissionStatus[36][$intraMobility->id] = 0;
                if (empty($reportdata->getDocuments(36, $intraMobility->id)))
                    $submissionStatus[36][$intraMobility->id] = 2;
            }
            else{
                if (LockController::isLocked($intraMobility->id, 34))
                    $submissionStatus[34][$intraMobility->id] = 1;
                else
                    $submissionStatus[34][$intraMobility->id] = 0;
                if (empty($reportdata->getDocuments(34, $intraMobility->id)))
                    $submissionStatus[34][$intraMobility->id] = 2;
            }

        }

        return view('extension-programs.intra-mobilities.index', compact('intraMobilities', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', IntraMobility::class);

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('8')");

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();
        
        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        $is_dean = Dean::where('user_id', auth()->id())->first();
        $is_chair = Chairperson::where('user_id', auth()->id())->first();

        return view('extension-programs.intra-mobilities.create', compact('mobilityFields', 'colleges', 'is_dean', 'is_chair'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', IntraMobility::class);

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
        return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);
        
        $intraMobility = IntraMobility::create($input);
        $intraMobility->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IntraM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    IntraMobilityDocument::create([
                        'intra_mobility_id' => $intraMobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added an intra-country mobility.');

        return redirect()->route('intra-mobility.index')->with('mobility_success', 'Intra-Country mobility has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        if (auth()->id() !== $intraMobility->user_id)
        abort(403);

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('8')");

        $documents = IntraMobilityDocument::where('intra_mobility_id', $intraMobility->id)->get()->toArray();

        $values = $intraMobility->toArray();

        return view('extension-programs.intra-mobilities.show', compact('intraMobility', 'mobilityFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        if (auth()->id() !== $intraMobility->user_id)
            abort(403);

        if($intraMobility->classification_of_person == '298')
            if(LockController::isLocked($intraMobility->id, 36)){
                return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
            }
        else
            if(LockController::isLocked($intraMobility->id, 34)){
                return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
            }

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('8')");

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $intraMobility->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $intraMobility->toArray();

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        $documents = IntraMobilityDocument::where('intra_mobility_id', $intraMobility->id)->get()->toArray();

        $is_dean = Dean::where('user_id', auth()->id())->first();
        $is_chair = Chairperson::where('user_id', auth()->id())->first();

        return view('extension-programs.intra-mobilities.edit', compact('intraMobility', 'mobilityFields', 'documents', 'values', 'colleges', 'collegeAndDepartment', 'is_dean', 'is_chair'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);


        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $intraMobility->update(['description' => '-clear']);

        $intraMobility->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IntraM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    IntraMobilityDocument::create([
                        'intra_mobility_id' => $intraMobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated an intra-country mobility.');


        return redirect()->route('intra-mobility.index')->with('mobility_success', 'Intra-Country mobility has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IntraMobility $intraMobility)
    {
        $this->authorize('manage', IntraMobility::class);

        if(LockController::isLocked($intraMobility->id, 34)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        IntraMobilityDocument::where('intra_mobility_id', $intraMobility->id)->delete();
        $intraMobility->delete();

        \LogActivity::addToLog('Had deleted an intra-country mobility.');

        return redirect()->route('intra-mobility.index')->with('mobility_success', 'Intra-Country mobility has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('manage', IntraMobility::class);

        if(ExtensionProgramForm::where('id', 8)->pluck('is_active')->first() == 0)
            return view('inactive');
        IntraMobilityDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of an intra-country mobility.');

        return true;
    }
}
