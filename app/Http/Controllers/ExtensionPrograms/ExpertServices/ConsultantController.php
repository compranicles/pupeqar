<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\ExpertServiceConsultant;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\StorageFileController;
use App\Models\ExpertServiceConsultantDocument;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

class ConsultantController extends Controller
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
        $this->authorize('viewAny', ExpertServiceConsultant::class);

        $currentQuarterYear = Quarter::find(1);

        $expertServicesConsultant = ExpertServiceConsultant::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_consultants.classification')
                                        ->join('colleges', 'colleges.id', 'expert_service_consultants.college_id')
                                        ->select(DB::raw('expert_service_consultants.*, dropdown_options.name as classification_name, colleges.name as college_name'))
                                        ->orderBy('expert_service_consultants.updated_at', 'desc')
                                        ->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($expertServicesConsultant as $consultant) {
            if (LockController::isLocked($consultant->id, 9))
                $submissionStatus[9][$consultant->id] = 1;
            else
                $submissionStatus[9][$consultant->id] = 0;
            if (empty($reportdata->getDocuments(9, $consultant->id)))
                $submissionStatus[9][$consultant->id] = 2;
        }

        return view('extension-programs.expert-services.consultant.index', compact('expertServicesConsultant',
             'currentQuarterYear', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceConsultantFields = DB::select("CALL get_extension_program_fields_by_form_id('1')");

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('extension-programs.expert-services.consultant.create', compact('expertServiceConsultantFields', 'colleges', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $request->validate([
            'to' => 'after_or_equal:from',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $esConsultant = ExpertServiceConsultant::create($input);
        $esConsultant->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ESCS-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceConsultantDocument::create([
                        'expert_service_consultant_id' => $esConsultant->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added an expert service rendered as consultant "'.$request->input('title').'".');

        return redirect()->route('expert-service-as-consultant.index')->with('edit_esconsultant_success', 'Expert service rendered as consultant has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('view', ExpertServiceConsultant::class);

        if (auth()->id() !== $expert_service_as_consultant->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $expertServiceConsultantFields = DB::select("CALL get_extension_program_fields_by_form_id('1')");

        $documents = ExpertServiceConsultantDocument::where('expert_service_consultant_id', $expert_service_as_consultant->id)->get()->toArray();

        $values = $expert_service_as_consultant->toArray();

        return view('extension-programs.expert-services.consultant.show', compact('expertServiceConsultantFields','expert_service_as_consultant', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('update', ExpertServiceConsultant::class);

        if (auth()->id() !== $expert_service_as_consultant->user_id)
            abort(403);

        if(LockController::isLocked($expert_service_as_consultant->id, 9)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        // dd($expert_service_as_consultant);
        $expertServiceConsultantFields = DB::select("CALL get_extension_program_fields_by_form_id('1')");

        $expertServiceConsultantDocuments = ExpertServiceConsultantDocument::where('expert_service_consultant_id', $expert_service_as_consultant->id)->get()->toArray();

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $value = $expert_service_as_consultant;
        $value->toArray();
        $value = collect($expert_service_as_consultant);
        $value = $value->toArray();

        return view('extension-programs.expert-services.consultant.edit', compact('expert_service_as_consultant', 'expertServiceConsultantFields', 'expertServiceConsultantDocuments', 'colleges', 'value', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('update', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));

        $request->merge([
            'from' => $from,
            'to' => $to,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $request->validate([
            'to' => 'after_or_equal:from',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $expert_service_as_consultant->update(['description' => '-clear']);

        $expert_service_as_consultant->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ESCS-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ExpertServiceConsultantDocument::create([
                        'expert_service_consultant_id' => $expert_service_as_consultant->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }


        \LogActivity::addToLog('Had updated the xpert service rendered as consultant "'.$expert_service_as_consultant->title.'".');

        return redirect()->route('expert-service-as-consultant.index')->with('edit_esconsultant_success', 'Expert service rendered as consultant has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertServiceConsultant $expert_service_as_consultant)
    {
        $this->authorize('delete', ExpertServiceConsultant::class);

        if(LockController::isLocked($expert_service_as_consultant->id, 9)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expert_service_as_consultant->delete();
        ExpertServiceConsultantDocument::where('expert_service_consultant_id', $expert_service_as_consultant->id)->delete();

        \LogActivity::addToLog('Had deleted the expert service rendered as consultant "'.$expert_service_as_consultant->title.'".');

        return redirect()->route('expert-service-as-consultant.index')->with('edit_esconsultant_success', 'Expert service rendered as consultant has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExpertServiceConsultant::class);

        if(ExtensionProgramForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        ExpertServiceConsultantDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);
        \LogActivity::addToLog('Had deleted a document of an expert service rendered as consultant.');

        return true;
    }
}
