<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Chairperson,
    Dean,
    TemporaryFile,
    ViableProject,
    ViableProjectDocument,
    FormBuilder\AcademicDevelopmentForm,
    Maintenance\College,
    Maintenance\Quarter,
};
use App\Services\DateContentService;

class ViableProjectController extends Controller
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
        $this->authorize('viewAny', ViableProject::class);

        $currentQuarterYear = Quarter::find(1);

        $viable_projects = ViableProject::where('user_id', auth()->id())
                            ->join('colleges', 'colleges.id', 'viable_projects.college_id')
                            ->orderBy('viable_projects.updated_at', 'desc')
                            ->select('viable_projects.*', 'colleges.name as college_name')
                            ->get();
                            
        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($viable_projects as $viable_project) {
            if (LockController::isLocked($viable_project->id, 20))
                $submissionStatus[20][$viable_project->id] = 1;
            else 
                $submissionStatus[20][$viable_project->id] = 0;
            if (empty($reportdata->getDocuments(20, $viable_project->id)))
                $submissionStatus[20][$viable_project->id] = 2;
        }

        return view('academic-development.viable-project.index', compact('viable_projects', 'currentQuarterYear',
            'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ViableProject::class);

        if(AcademicDevelopmentForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $projectFields = DB::select("CALL get_academic_development_fields_by_form_id(5)");

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('academic-development.viable-project.create', compact('projectFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ViableProject::class);

        $request->validate([
            'rate_of_return' => 'numeric'
        ]);
        $value = $request->input('revenue');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $value2 = $request->input('cost');
        $value2 = (float) str_replace(",", "", $value2);
        $value2 = number_format($value2,2,'.','');

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'revenue' => $value,
            'cost' => $value2,
            'start_date' => $start_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $request->except(['_token', '_method', 'document', 'rate_of_return']);
        if(AcademicDevelopmentForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $viable_project = ViableProject::create($input);
        $viable_project->update(['user_id' => auth()->id()]);
        
        $return_rate = ($request->input('rate_of_return') / 100 );
        
        $viable_project->update(['rate_of_return' => $return_rate]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'VP-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ViableProjectDocument::create([
                        'viable_project_id' => $viable_project->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a viable demo project "'.$request->input('name').'".');


        return redirect()->route('viable-project.index')->with('project_success', 'Viable demonstration project has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ViableProject $viable_project)
    {
        $this->authorize('view', ViableProject::class);

        if (auth()->id() !== $viable_project->user_id)
            abort(403);

        if(AcademicDevelopmentForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $projectFields = DB::select("CALL get_academic_development_fields_by_form_id(5)");

        // dd($viable_project);
        $documents = ViableProjectDocument::where('viable_project_id', $viable_project->id)->get()->toArray();

        $values = $viable_project->toArray();

        return view('academic-development.viable-project.show', compact('projectFields', 'viable_project', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ViableProject $viable_project)
    {
        $this->authorize('update', ViableProject::class);

        if (auth()->id() !== $viable_project->user_id)
            abort(403);

        if(LockController::isLocked($viable_project->id, 20)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(AcademicDevelopmentForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $projectFields = DB::select("CALL get_academic_development_fields_by_form_id(5)");

        $documents = ViableProjectDocument::where('viable_project_id', $viable_project->id)->get()->toArray();

        $viable_project->rate_of_return = $viable_project->rate_of_return * 100;
        $values = $viable_project->toArray();

        $deans = Dean::where('user_id', auth()->id())->pluck('college_id')->all();
        $chairpersons = Chairperson::where('user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')->pluck('departments.college_id')->all();
        $colleges = array_merge($deans, $chairpersons);

        $colleges = College::whereIn('id', array_values($colleges))
                    ->select('colleges.*')->get();

        return view('academic-development.viable-project.edit', compact('projectFields', 'viable_project', 'documents', 'values', 'colleges'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ViableProject $viable_project)
    {
        $this->authorize('update', ViableProject::class);

        $request->validate([
            'rate_of_return' => 'numeric'
        ]);
        $value = $request->input('revenue');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $value2 = $request->input('cost');
        $value2 = (float) str_replace(",", "", $value2);
        $value2 = number_format($value2,2,'.','');

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");

        $request->merge([
            'revenue' => $value,
            'cost' => $value2,
            'start_date' => $start_date,
        ]);
        
        if(AcademicDevelopmentForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $viable_project->update(['description' => '-clear']);

        $viable_project->update($input);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'VP-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ViableProjectDocument::create([
                        'viable_project_id' => $viable_project->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated the viable demo project "'.$viable_project->name.'".');


        return redirect()->route('viable-project.index')->with('project_success', 'Viable demonstration project has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ViableProject $viable_project)
    {
        $this->authorize('delete', ViableProject::class);

        if(LockController::isLocked($viable_project->id, 20)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(AcademicDevelopmentForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        ViableProjectDocument::where('viable_project_id', $viable_project->id)->delete();
        $viable_project->delete();

        \LogActivity::addToLog('Had deleted the viable demo project "'.$viable_project->name.'".');

        return redirect()->route('viable-project.index')->with('project_success', 'Viable demonstration project has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ViableProject::class);

        if(AcademicDevelopmentForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');
        ViableProjectDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a viable demo project.');

        return true;
    }
}
