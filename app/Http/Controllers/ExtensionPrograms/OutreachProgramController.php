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
    OutreachProgram,
    OutreachProgramDocument,
    TemporaryFile,
    FormBuilder\ExtensionProgramForm,
    Maintenance\Quarter,
};

class OutreachProgramController extends Controller
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
        $this->authorize('viewAny', OutreachProgram::class);

        $currentQuarterYear = Quarter::find(1);

        $outreach_programs = OutreachProgram::where('user_id', auth()->id())
                                ->select(DB::raw('outreach_programs.*'))
                                ->orderBy('outreach_programs.updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($outreach_programs as $outreach_program) {
            if (LockController::isLocked($outreach_program->id, 22))
                $submissionStatus[22][$outreach_program->id] = 1;
            else 
                $submissionStatus[22][$outreach_program->id] = 0;
            if (empty($reportdata->getDocuments(22, $outreach_program->id)))
                $submissionStatus[22][$outreach_program->id] = 2;
        }
        
        return view('extension-programs.outreach-program.index', compact('outreach_programs', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', OutreachProgram::class);

        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $outreachFields = DB::select("CALL get_extension_program_fields_by_form_id('7')");

        return view('extension-programs.outreach-program.create', compact('outreachFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', OutreachProgram::class);

        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = date("Y-m-d", strtotime($request->input('date')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'date' => $date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $outreach = OutreachProgram::create($input);
        $outreach->update(['user_id' => auth()->id()]);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'OP-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    OutreachProgramDocument::create([
                        'outreach_program_id' => $outreach->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a community relations and outreach program "'.$request->input('title_of_the_program').'".');

        return redirect()->route('outreach-program.index')->with('outreach_success', 'Community relations and outreach program has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OutreachProgram $outreach_program)
    {
        $this->authorize('view', OutreachProgram::class);

        if (auth()->id() !== $outreach_program->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $outreachFields = DB::select("CALL get_extension_program_fields_by_form_id('7')");

        $values = $outreach_program->toArray();

        $documents = OutreachProgramDocument::where('outreach_program_id', $outreach_program->id)->get()->toArray();

        return view('extension-programs.outreach-program.show', compact('outreach_program', 'outreachFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OutreachProgram $outreach_program)
    {
        $this->authorize('update', OutreachProgram::class);

        if (auth()->id() !== $outreach_program->user_id)
            abort(403);
            
        if(LockController::isLocked($outreach_program->id, 22)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $outreachFields = DB::select("CALL get_extension_program_fields_by_form_id('7')");

        $values = $outreach_program->toArray();

        $documents = OutreachProgramDocument::where('outreach_program_id', $outreach_program->id)->get()->toArray();

        return view('extension-programs.outreach-program.edit', compact('outreach_program', 'outreachFields', 'documents', 'values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutreachProgram $outreach_program)
    {
        $this->authorize('update', OutreachProgram::class);

        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = date("Y-m-d", strtotime($request->input('date')));
        
        $request->merge([
            'date' => $date,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $outreach_program->update(['description' => '-clear']);

        $outreach_program->update($input);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'OP-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    OutreachProgramDocument::create([
                        'outreach_program_id' => $outreach_program->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated the community relations and outreach program "'.$outreach_program->title_of_the_program.'".');


        return redirect()->route('outreach-program.index')->with('outreach_success', 'Community relations and outreach program has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutreachProgram $outreach_program)
    {
        $this->authorize('delete', OutreachProgram::class);

        if(LockController::isLocked($outreach_program->id, 22)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }
        
        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        OutreachProgramDocument::where('outreach_program_id', $outreach_program->id)->delete();
        $outreach_program->delete();

        \LogActivity::addToLog('Had deleted the community relations and outreach program "'.$outreach_program->title_of_the_program.'".');

        return redirect()->route('outreach-program.index')->with('outreach_success', 'Community relations and outreach program has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', OutreachProgram::class);

        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        OutreachProgramDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a community relations and outreach program.');

        return true;
    }
}
