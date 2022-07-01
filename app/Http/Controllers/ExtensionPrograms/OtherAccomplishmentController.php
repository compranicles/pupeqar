<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\Quarter;
use App\Models\OtherAccomplishment;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\OtherAccomplishmentDocument;
use App\Http\Controllers\StorageFileController;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class OtherAccomplishmentController extends Controller
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
        // $this->authorize('viewAny', OtherAccomplishment::class);

        $currentQuarterYear = Quarter::find(1);

        $otherAccomplishments = OtherAccomplishment::where('user_id', auth()->id())
                                ->join('dropdown_options', 'dropdown_options.id', 'other_accomplishments.level')
                                ->join('colleges', 'colleges.id', 'other_accomplishments.college_id')
                                ->select(DB::raw('other_accomplishments.*, dropdown_options.name as accomplishment_level, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($otherAccomplishments as $otherAccomplishment) {
            if (LockController::isLocked($otherAccomplishment->id, 38))
                $submissionStatus[38][$otherAccomplishment->id] = 1;
            else
                $submissionStatus[38][$otherAccomplishment->id] = 0;
            if (empty($reportdata->getDocuments(38, $otherAccomplishment->id)))
                $submissionStatus[38][$otherAccomplishment->id] = 2;
        }


        return view('extension-programs.other-accomplishments.index', compact('otherAccomplishments', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();
        $departments = Department::whereIn('college_id', $colleges)->get();
        return view('extension-programs.other-accomplishments.create', compact('otherAccomplishmentFields', 'colleges', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize('create', OtherAccomplishment::class);

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $otherAccomplishment = OtherAccomplishment::create($input);
        $otherAccomplishment->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'OA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    OtherAccomplishmentDocument::create([
                        'other_accomplishment_id' => $otherAccomplishment->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added other individual accomplishment.');

        return redirect()->route('other-accomplishment.index')->with('other_success', 'Other accomplishment has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OtherAccomplishment $otherAccomplishment)
    {
        // $this->authorize('view', OtherAccomplishment::class);

        if (auth()->id() !== $otherAccomplishment->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $documents = OtherAccomplishmentDocument::where('other_accomplishment_id', $otherAccomplishment->id)->get()->toArray();

        $values = $otherAccomplishment->toArray();

        return view('extension-programs.other-accomplishments.show', compact('otherAccomplishment', 'otherAccomplishmentFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherAccomplishment $otherAccomplishment)
    {
        // $this->authorize('update', OtherAccomplishment::class);

        if (auth()->id() !== $otherAccomplishment->user_id)
            abort(403);

        if(LockController::isLocked($otherAccomplishment->id, 38)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $otherAccomplishment->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $otherAccomplishment->toArray();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();

        $documents = OtherAccomplishmentDocument::where('other_accomplishment_id', $otherAccomplishment->id)->get()->toArray();

        return view('extension-programs.other-accomplishments.edit', compact('otherAccomplishment', 'otherAccomplishmentFields', 'documents', 'values', 'colleges', 'collegeAndDepartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OtherAccomplishment $otherAccomplishment)
    {
        // $this->authorize('update', OtherAccomplishment::class);

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));

        $request->merge([
            'from' => $from,
            'to' => $to,
        ]);


        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $otherAccomplishment->update(['description' => '-clear']);

        $otherAccomplishment->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'OA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    OtherAccomplishmentDocument::create([
                        'other_accomplishment_id' => $otherAccomplishment->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated other accomplishment.');


        return redirect()->route('other-accomplishment.index')->with('other_success', 'Other accomplishment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OtherAccomplishment $otherAccomplishment)
    {
        // $this->authorize('delete', OtherAccomplishment::class);

        if(LockController::isLocked($otherAccomplishment->id, 38)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherAccomplishmentDocument::where('other_accomplishment_id', $otherAccomplishment->id)->delete();
        $otherAccomplishment->delete();
        \LogActivity::addToLog('Had deleted other accomplishment.');

        return redirect()->route('other-accomplishment.index')->with('other_success', 'Other accomplishment has been deleted.');
    }

    public function removeDoc($filename){
        // $this->authorize('delete', OtherAccomplishment::class);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherAccomplishmentDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of other accomplishment.');

        return true;
    }
}
