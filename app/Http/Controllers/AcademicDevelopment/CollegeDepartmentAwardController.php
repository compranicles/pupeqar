<?php

namespace App\Http\Controllers\AcademicDevelopment;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\CollegeDepartmentAward;
use Illuminate\Support\Facades\Storage;
use App\Models\CollegeDepartmentAwardDocument;
use App\Models\FormBuilder\AcademicDevelopmentForm;
use App\Http\Controllers\Maintenances\LockController;

class CollegeDepartmentAwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('viewAny', CollegeDepartmentAward::class);

        $college_department_awards = CollegeDepartmentAward::where('user_id', auth()->id())
                                    ->select(DB::raw('college_department_awards.*, QUARTER(college_department_awards.updated_at) as quarter'))
                                    ->orderBy('college_department_awards.updated_at', 'desc')->get();
        return view('academic-development.college-department-award.index', compact('college_department_awards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('create', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $awardFields = DB::select("CALL get_academic_development_fields_by_form_id(6)");

        return view('academic-development.college-department-award.create', compact('awardFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize('create', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = date("Y-m-d", strtotime($request->input('date')));

        $currentQuarterYear = Quarter::find(1);
        
        $request->merge([
            'date' => $date,
            'report_quarter' => $currentQuarterYear->report_quarter,
            'report_year' => $currentQuarterYear->report_year,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $college_department_award = CollegeDepartmentAward::create($input);
        $college_department_award->update(['user_id' => auth()->id()]);

        $string = str_replace(' ', '-', $request->input('description')); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'CDAward-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    CollegeDepartmentAwardDocument::create([
                        'college_department_award_id' => $college_department_award->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Awards and recognition received by the college and department has been added.');

        return redirect()->route('college-department-award.index')->with('award_success', 'Awards and recognition received by the college and department has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CollegeDepartmentAward $college_department_award)
    {
        // $this->authorize('view', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        $awardFields = DB::select("CALL get_academic_development_fields_by_form_id(6)");

        $documents = CollegeDepartmentAwardDocument::where('college_department_award_id', $college_department_award->id)->get()->toArray();

        $values = $college_department_award->toArray();

        return view('academic-development.college-department-award.show', compact('awardFields', 'college_department_award', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CollegeDepartmentAward $college_department_award)
    {
        // $this->authorize('update', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($college_department_award->id, 21)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        $awardFields = DB::select("CALL get_academic_development_fields_by_form_id(6)");

        $documents = CollegeDepartmentAwardDocument::where('college_department_award_id', $college_department_award->id)->get()->toArray();

        $values = $college_department_award->toArray();

        return view('academic-development.college-department-award.edit', compact('awardFields', 'college_department_award', 'documents', 'values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CollegeDepartmentAward $college_department_award)
    {
        // $this->authorize('update', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date = date("Y-m-d", strtotime($request->input('date')));
        
        $request->merge([
            'date' => $date,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $college_department_award->update(['description' => '-clear']);

        $college_department_award->update($input);

        $string = str_replace(' ', '-', $request->input('description')); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'CDAward-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    CollegeDepartmentAwardDocument::create([
                        'college_department_award_id' => $college_department_award->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Awards and recognition received by the college and department has been updated.');

        return redirect()->route('college-department-award.index')->with('award_success', 'Awards and recognition received by the college and department has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollegeDepartmentAward $college_department_award)
    {
        // $this->authorize('delete', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($college_department_award->id, 21)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        CollegeDepartmentAwardDocument::where('college_department_award_id', $college_department_award->id)->delete();
        $college_department_award->delete();

        \LogActivity::addToLog('Awards and recognition received by the college and department has been deleted.');

        return redirect()->route('college-department-award.index')->with('award_success', 'Awards and recognition received by the college and department has been deleted.');
    }

    public function removeDoc($filename){
        // $this->authorize('delete', CollegeDepartmentAward::class);

        if(AcademicDevelopmentForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');
        CollegeDepartmentAwardDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Awards and recognition received by the college and department document has been removed.');

        return true;
    }
}
