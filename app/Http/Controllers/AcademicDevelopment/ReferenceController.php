<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Models\Reference;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ReferenceDocument;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Models\FormBuilder\AcademicDevelopmentForm;
use App\Http\Controllers\Maintenances\LockController;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Reference::class);

        $categories = DropdownOption::where('dropdown_id', 37)->get();
        $allRtmmi = Reference::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'references.category')
                                        ->join('colleges', 'colleges.id', 'references.college_id')
                                        ->select('references.*', 'dropdown_options.name as category_name', 'colleges.name as college_name', DB::raw('QUARTER(references.updated_at) as quarter'))
                                        ->orderBy('references.updated_at', 'desc')
                                        ->get();
        
        $rtmmi_in_colleges = Reference::join('colleges', 'references.college_id', 'colleges.id')
                                        ->select('colleges.name')
                                        ->distinct()
                                        ->get();

        return view('academic-development.references.index', compact('allRtmmi', 'rtmmi_in_colleges', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $referenceFields = DB::select("CALL get_academic_development_fields_by_form_id(1)");

        $colleges = College::all();
        return view('academic-development.references.create', compact('referenceFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
      
        $date_started = date("Y-m-d", strtotime($request->input('date_started')));
        $date_completed = date("Y-m-d", strtotime($request->input('date_completed')));
        $date_published = date("Y-m-d", strtotime($request->input('date_published')));
        
        $request->merge([
            'date_started' => $date_started,
            'date_completed' => $date_completed,
            'date_published' => $date_published,
        ]);

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $rtmmi = Reference::create($input);
        $rtmmi->update(['user_id' => auth()->id()]);

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
                    $fileName = 'RTMMI-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ReferenceDocument::create([
                        'reference_id' => $rtmmi->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        $accomplished = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $accomplished = collect($accomplished);
        $accomplishment = $accomplished->pluck('name');

        \LogActivity::addToLog('Reference, Textbook, Module, Monograph, or Instructional Material added.');


        return redirect()->route('rtmmi.index')->with(['edit_rtmmi_success' => ucfirst($accomplishment[0]), 'action' => 'added.' ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Reference $rtmmi)
    {
        $this->authorize('view', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $referenceDocuments = ReferenceDocument::where('reference_id', $rtmmi->id)->get()->toArray();

        $category = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $referenceFields = DB::select("CALL get_academic_development_fields_by_form_id(1)");

        $values = $rtmmi->toArray();

        return view('academic-development.references.show', compact('rtmmi', 'referenceDocuments', 'category', 'referenceFields', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Reference $rtmmi)
    {
        $this->authorize('update', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        if(LockController::isLocked($rtmmi->id, 15)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        $referenceFields = DB::select("CALL get_academic_development_fields_by_form_id(1)");

        $referenceDocuments = ReferenceDocument::where('reference_id', $rtmmi->id)->get()->toArray();

        $category = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $colleges = College::all();
      
        if ($rtmmi->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$rtmmi->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $value = $rtmmi;
        $value->toArray();
        $value = collect($rtmmi);
        $value = $value->toArray();
        
        return view('academic-development.references.edit', compact('value', 'referenceFields', 'referenceDocuments', 'colleges', 'category', 'collegeOfDepartment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request,
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reference $rtmmi)
    {
        $this->authorize('update', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_started = date("Y-m-d", strtotime($request->input('date_started')));
        $date_completed = date("Y-m-d", strtotime($request->input('date_completed')));
        $date_published = date("Y-m-d", strtotime($request->input('date_published')));
        
        $request->merge([
            'date_started' => $date_started,
            'date_completed' => $date_completed,
            'date_published' => $date_published,
        ]);

        $request->validate([
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $rtmmi->update(['description' => '-clear']);

        $rtmmi->update($input);

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
                    $fileName = 'RTMMI-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ReferenceDocument::create([
                        'reference_id' => $rtmmi->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        $accomplished = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $accomplished = collect($accomplished);
        $accomplishment = $accomplished->pluck('name');

        \LogActivity::addToLog('Reference, Textbook, Module, Monograph, or Instructional Material updated.');

        return redirect()->route('rtmmi.index')->with('edit_rtmmi_success', ucfirst($accomplishment[0]))
                                ->with('action', 'updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reference $rtmmi)
    {
        $this->authorize('delete', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        
        if(LockController::isLocked($rtmmi->id, 15)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited.');
        }

        $rtmmi->delete();
        ReferenceDocument::where('reference_id', $rtmmi->id)->delete();

        $accomplished = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $accomplished = collect($accomplished);
        $accomplishment = $accomplished->pluck('name');

        \LogActivity::addToLog('Reference, Textbook, Module, Monograph, or Instructional Material deleted.');

        return redirect()->route('rtmmi.index')->with('edit_rtmmi_success', ucfirst($accomplishment[0]))
                            ->with('action', 'deleted.');
    }

    public function removeDoc($filename){
        
        $this->authorize('delete', Reference::class);

        if(AcademicDevelopmentForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        ReferenceDocument::where('filename', $filename)->delete();
        // Storage::delete('documents/'.$filename);

        \LogActivity::addToLog('Reference, Textbook, Module, Monograph, or Instructional Material document deleted.');

        return true;
    }
}
