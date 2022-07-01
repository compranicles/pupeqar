<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Models\Dean;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\OtherDeptAccomplishment;
use Illuminate\Support\Facades\Storage;
use App\Models\OtherAccomplishmentDocument;
use App\Http\Controllers\StorageFileController;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class OtherDeptAccomplishmentController extends Controller
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

        $otherAccomplishments = OtherDeptAccomplishment::where('user_id', auth()->id())
                                ->join('dropdown_options', 'dropdown_options.id', 'other_dept_accomplishments.level')
                                ->join('colleges', 'colleges.id', 'other_dept_accomplishments.college_id')
                                ->select(DB::raw('other_dept_accomplishments.*, dropdown_options.name as accomplishment_level, colleges.name as college_name'))
                                ->orderBy('updated_at', 'desc')->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($otherAccomplishments as $otherAccomplishment) {
            if (LockController::isLocked($otherAccomplishment->id, 39))
                $submissionStatus[39][$otherAccomplishment->id] = 1;
            else
                $submissionStatus[39][$otherAccomplishment->id] = 0;
            if (empty($reportdata->getDocuments(39, $otherAccomplishment->id)))
                $submissionStatus[39][$otherAccomplishment->id] = 2;
        }

        return view('extension-programs.other-dept-accomplishments.index', compact('otherAccomplishments', 'currentQuarterYear',
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

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('extension-programs.other-dept-accomplishments.create', compact('otherAccomplishmentFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize('create', OtherDeptAccomplishment::class);

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

        $otherDeptAccomplishment = OtherDeptAccomplishment::create($input);
        $otherDeptAccomplishment->update(['user_id' => auth()->id()]);

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
                        'other_accomplishment_id' => $otherDeptAccomplishment->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }
        \LogActivity::addToLog('Had added other department/college accomplishment.');

        return redirect()->route('other-dept-accomplishment.index')->with('other_dept_success', 'Other department/college accomplishment has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OtherDeptAccomplishment $otherDeptAccomplishment)
    {
        // $this->authorize('view', OtherDeptAccomplishment::class);

        if (auth()->id() !== $otherDeptAccomplishment->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $documents = OtherDeptAccomplishmentDocument::where('other_accomplishment_id', $otherDeptAccomplishment->id)->get()->toArray();

        $values = $otherDeptAccomplishment->toArray();

        return view('extension-programs.other-dept-accomplishments.show', compact('otherAccomplishment', 'otherAccomplishmentFields', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherDeptAccomplishment $otherDeptAccomplishment)
    {
        // $this->authorize('update', OtherDeptAccomplishment::class);

        if (auth()->id() !== $otherDeptAccomplishment->user_id)
            abort(403);

        if(LockController::isLocked($otherDeptAccomplishment->id, 39)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $otherAccomplishmentFields = DB::select("CALL get_extension_program_fields_by_form_id('10')");

        $collegeAndDepartment = Department::join('colleges', 'colleges.id', 'departments.college_id')
                ->where('colleges.id', $otherDeptAccomplishment->college_id)
                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                ->first();

        $values = $otherDeptAccomplishment->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();
        $documents = OtherDeptAccomplishmentDocument::where('other_accomplishment_id', $otherDeptAccomplishment->id)->get()->toArray();

        return view('extension-programs.other-dept-accomplishments.edit', compact('otherAccomplishment', 'otherAccomplishmentFields', 'documents', 'values', 'colleges', 'collegeAndDepartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->authorize('update', OtherDeptAccomplishment::class);

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));

        $request->merge([
            'from' => $from,
            'to' => $to,
        ]);


        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $otherDeptAccomplishment->update(['description' => '-clear']);

        $otherDeptAccomplishment->update($input);

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

                    OtherDeptAccomplishmentDocument::create([
                        'other_accomplishment_id' => $otherDeptAccomplishment->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated other department/college accomplishment.');


        return redirect()->route('other-dept-accomplishment.index')->with('other_dept_success', 'Other department/college accomplishment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OtherDeptAccomplishment $otherDeptAccomplishment)
    {
        // $this->authorize('delete', OtherDeptAccomplishment::class);

        if(LockController::isLocked($otherDeptAccomplishment->id, 39)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherDeptAccomplishmentDocument::where('other_accomplishment_id', $otherDeptAccomplishment->id)->delete();
        $otherDeptAccomplishment->delete();
        \LogActivity::addToLog('Had deleted other department/college accomplishment.');

        return redirect()->route('other-dept-accomplishment.index')->with('other_dept_success', 'Other department/college accomplishment has been deleted.');
    }

    public function removeDoc($filename){
        // $this->authorize('delete', OtherDeptAccomplishment::class);

        if(ExtensionProgramForm::where('id', 10)->pluck('is_active')->first() == 0)
            return view('inactive');
        OtherDeptAccomplishmentDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of other department/college accomplishment.');

        return true;
    }
}
