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
    Mobility,
    MobilityDocument,
    TemporaryFile,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};

class MobilityController extends Controller
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
        $this->authorize('viewAny', Mobility::class);

        $currentQuarterYear = Quarter::find(1);

        $mobilities = Mobility::where('user_id', auth()->id())
                                ->join('colleges', 'colleges.id', 'mobilities.college_id')
                                ->select(DB::raw('mobilities.*, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($mobilities as $mobility) {
            if (LockController::isLocked($mobility->id, 14))
                $submissionStatus[14][$mobility->id] = 1;
            else 
                $submissionStatus[14][$mobility->id] = 0;
            if (empty($reportdata->getDocuments(14, $mobility->id)))
                $submissionStatus[14][$mobility->id] = 2;
        }

        return view('extension-programs.mobility.index', compact('mobilities', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Mobility::class);

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        return view('extension-programs.mobility.create', compact('mobilityFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Mobility::class);

        $start_date = date("Y-m-d", strtotime($request->input('start_date')));
        $end_date = date("Y-m-d", strtotime($request->input('end_date')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $mobility = Mobility::create($input);
        $mobility->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'InterM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    MobilityDocument::create([
                        'mobility_id' => $mobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added an inter-country mobility.');

        return redirect()->route('mobility.index')->with('mobility_success', 'Inter-Country mobility has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mobility $mobility)
    {
        $this->authorize('view', Mobility::class);

        if (auth()->id() !== $mobility->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $documents = MobilityDocument::where('mobility_id', $mobility->id)->get()->toArray();
    
        $values = $mobility->toArray();
        
        return view('extension-programs.mobility.show', compact('mobility', 'mobilityFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Mobility $mobility)
    {
        $this->authorize('update', Mobility::class);

        if (auth()->id() !== $mobility->user_id)
            abort(403);
            
        if(LockController::isLocked($mobility->id, 14)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $mobilityFields = DB::select("CALL get_extension_program_fields_by_form_id('6')");

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $mobility->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $mobility->toArray();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        $documents = MobilityDocument::where('mobility_id', $mobility->id)->get()->toArray();

        return view('extension-programs.mobility.edit', compact('mobility', 'mobilityFields', 'documents', 'values', 'colleges', 'collegeAndDepartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mobility $mobility)
    {
        $this->authorize('update', Mobility::class);

        $start_date = date("Y-m-d", strtotime($request->input('start_date')));
        $end_date = date("Y-m-d", strtotime($request->input('end_date')));

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
        
        
        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $mobility->update(['description' => '-clear']);

        $mobility->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'InterM-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    MobilityDocument::create([
                        'mobility_id' => $mobility->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated an inter-country mobility.');


        return redirect()->route('mobility.index')->with('mobility_success', 'Inter-Country mobility has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mobility $mobility)
    {
        $this->authorize('delete', Mobility::class);

        if(LockController::isLocked($mobility->id, 14)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        MobilityDocument::where('mobility_id', $mobility->id)->delete();
        $mobility->delete();
        \LogActivity::addToLog('Had deleted an inter-country mobility.');

        return redirect()->route('mobility.index')->with('mobility_success', 'Inter-Country mobility has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', Mobility::class);

        if(ExtensionProgramForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        MobilityDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of an inter-country mobility.');

        return true;
    }
}
