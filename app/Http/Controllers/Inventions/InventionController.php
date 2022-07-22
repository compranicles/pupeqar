<?php

namespace App\Http\Controllers\Inventions;

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
    Invention,
    InventionDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\InventionField,
    FormBuilder\InventionForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};
use App\Services\DateContentService;

class InventionController extends Controller
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
        $this->authorize('viewAny', Invention::class);

        $year = "created";

        $currentQuarterYear = Quarter::find(1);
        $inventions = Invention::where('inventions.report_quarter', $currentQuarterYear->current_quarter)
                        ->where('inventions.report_year', $currentQuarterYear->current_year)
                        ->where('inventions.user_id', auth()->id())
                        ->join('dropdown_options', 'dropdown_options.id', 'inventions.status')
                        ->join('colleges', 'colleges.id', 'inventions.college_id')
                        ->select('inventions.*', 'dropdown_options.name as status_name', 'colleges.name as college_name')
                        ->orderBy('inventions.updated_at', 'DESC')
                        ->get();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($inventions as $invention) {
            if (LockController::isLocked($invention->id, 8))
                $submissionStatus[8][$invention->id] = 1;
            else
                $submissionStatus[8][$invention->id] = 0;
            if (empty($reportdata->getDocuments(8, $invention->id)))
                $submissionStatus[8][$invention->id] = 2;
        }

        return view('inventions.index', compact('inventions', 'year', 'currentQuarterYear',
            'submissionStatus'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Invention::class);
        if(InventionForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $inventionFields = DB::select("CALL get_invention_fields_by_form_id(1)");

        $dropdown_options = [];
        foreach($inventionFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('inventions.create', compact('inventionFields', 'colleges', 'departments', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Invention::class);

        if(InventionForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $value = $request->input('funding_amount');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");
        $issue_date = (new DateContentService())->checkDateContent($request, "issue_date");
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'issue_date' => $issue_date,
            'funding_amount' => $value,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $iicw = Invention::create($input);
        $iicw->update(['user_id' => auth()->id()]);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IICW-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    InventionDocument::create([
                        'invention_id' => $iicw->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        $classification = DB::select("CALL get_dropdown_name_by_id($iicw->classification)");

        \LogActivity::addToLog("Had added ".ucfirst($classification[0]->name).' entitled "'.$request->input('title').'".');

        // dd($classification);
        return redirect()->route('invention-innovation-creative.index')->with('edit_iicw_success', ucfirst($classification[0]->name).' has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invention $invention_innovation_creative)
    {
        $this->authorize('view', Invention::class);

        if (auth()->id() !== $invention_innovation_creative->user_id)
            abort(403);

        if(InventionForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $inventionFields = DB::select("CALL get_invention_fields_by_form_id(1)");

        $classification = DB::select("CALL get_dropdown_name_by_id($invention_innovation_creative->classification)");

        $values = $invention_innovation_creative->toArray();

        foreach($inventionFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $values[$field->name])->pluck('name')->first();
                if($dropdownOptions == null)
                    $dropdownOptions = "-";
                $values[$field->name] = $dropdownOptions;
            }
            elseif($field->field_type_name == "college"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $college = College::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $college;
                }
            }
            elseif($field->field_type_name == "department"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $department = Department::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $department;
                }
            }
        }

        $documents = InventionDocument::where('invention_id', $invention_innovation_creative->id)->get()->toArray();

        return view('inventions.show', compact('invention_innovation_creative','inventionFields', 'values', 'documents', 'classification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Invention $invention_innovation_creative)
    {
        $this->authorize('update', Invention::class);

        if (auth()->id() !== $invention_innovation_creative->user_id)
            abort(403);

        if(InventionForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($invention_innovation_creative->id, 8)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        $inventionFields = DB::select("CALL get_invention_fields_by_form_id(1)");

        $dropdown_options = [];
        foreach($inventionFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $inventionDocuments = InventionDocument::where('invention_id', $invention_innovation_creative->id)->get()->toArray();

        // $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();


        if ($invention_innovation_creative->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$invention_innovation_creative->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $classification = DB::select("CALL get_dropdown_name_by_id($invention_innovation_creative->classification)");

        $value = $invention_innovation_creative;
        $value->toArray();
        $value = collect($invention_innovation_creative);
        $value = $value->toArray();
        // dd($value);

        return view('inventions.edit', compact('value', 'inventionFields', 'inventionDocuments', 'colleges', 'collegeOfDepartment', 'classification', 'departments', 'dropdown_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invention $invention_innovation_creative)
    {
        $this->authorize('update', Invention::class);

        if(InventionForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $value = $request->input('funding_amount');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $start_date = (new DateContentService())->checkDateContent($request, "start_date");
        $end_date = (new DateContentService())->checkDateContent($request, "end_date");
        $issue_date = (new DateContentService())->checkDateContent($request, "issue_date");

        $request->merge([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'issue_date' => $issue_date,
            'funding_amount' => $value,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);
        $invention_innovation_creative->update(['description' => '-clear']);
        $invention_innovation_creative->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'IICW-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    InventionDocument::create([
                        'invention_id' => $invention_innovation_creative->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        $classification = DB::select("CALL get_dropdown_name_by_id($invention_innovation_creative->classification)");

        \LogActivity::addToLog("Had updated ".ucfirst($classification[0]->name).'.');

        return redirect()->route('invention-innovation-creative.index')->with('edit_iicw_success', ucfirst($classification[0]->name).' has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invention $invention_innovation_creative)
    {
        $this->authorize('delete', Invention::class);

        if(LockController::isLocked($invention_innovation_creative->id, 8)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        if(InventionForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');


        $invention_innovation_creative->delete();
        InventionDocument::where('invention_id', $invention_innovation_creative->id)->delete();

        $classification = DB::select("CALL get_dropdown_name_by_id($invention_innovation_creative->classification)");

        \LogActivity::addToLog("Had deleted ".ucfirst($classification[0]->name).' entitled "'.$invention_innovation_creative->title.'".');

        return redirect()->route('invention-innovation-creative.index')->with('edit_iicw_success', ucfirst($classification[0]->name).' has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', Invention::class);

        if(InventionForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        InventionDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);

        \LogActivity::addToLog('Had deleted a document of an Invention/Innovation/Creative Work.');

        return true;
    }

    public function inventionYearFilter($year, $filter) {

        if($filter == "created") {
            if ($year == "created") {
                return redirect()->route('invention-innovation-creative.index');
            }
            else {
                $inventions = Invention::where('user_id', auth()->id())
                                    ->join('dropdown_options', 'dropdown_options.id', 'inventions.status')
                                    ->join('colleges', 'colleges.id', 'inventions.college_id')
                                    ->select(DB::raw('inventions.*, dropdown_options.name as status_name, colleges.name as college_name, QUARTER(inventions.updated_at) as quarter'))
                                    ->whereYear('inventions.created_at', $year)
                                    ->orderBy('inventions.updated_at', 'desc')->get();
            }
        }
        else {
            return redirect()->route('invention-innovation-creative.index');
        }

        $inventionStatus = DropdownOption::where('dropdown_id', 13)->get();
        $iicw_in_colleges = Invention::join('colleges', 'inventions.college_id', 'colleges.id')
                                ->select('colleges.name')->where('inventions.user_id', auth()->id())
                                ->distinct()
                                ->get();

        $inventionYears = Invention::selectRaw("YEAR(inventions.created_at) as created")->where('inventions.user_id', auth()->id())
                        ->distinct()
                        ->get();

        return view('inventions.index', compact('inventions', 'iicw_in_colleges', 'inventionStatus', 'inventionYears', 'year'));
    }
}
