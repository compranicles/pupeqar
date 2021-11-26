<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reference;
use App\Models\ReferenceDocument;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Department;
use App\Models\FormBuilder\DropdownOption;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allRtmmi = Reference::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'references.category')
                                        ->select('references.*', 'dropdown_options.name as category_name')
                                        ->get();
        
        return view('academic-development.references.index', compact('allRtmmi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $referenceFields1 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(1, 1, 12)");
        
        $referenceFields2 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(1, 13, 14)");
        
        $departments = Department::all();
        $colleges = College::all();
        return view('academic-development.references.create', compact('referenceFields1', 'referenceFields2', 'colleges', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', '_method', 'document', 'college_id']);

        $rtmmi = Reference::create($input);
        $rtmmi->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RTMMI-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('faculty.rtmmi.index')->with(['edit_rtmmi_success' => strtolower($accomplishment[0]), 'action' => 'saved.' ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Reference $rtmmi)
    {
        $referenceDocuments = ReferenceDocument::where('reference_id', $rtmmi->id)->get()->toArray();

        $category = DropdownOption::where('id', $rtmmi->category)
                            ->select('dropdown_options.name')->first();

        $level = DropdownOption::where('id', $rtmmi->level)
                            ->select('dropdown_options.name')->first();

        return view('academic-development.references.show', compact('rtmmi', 'referenceDocuments', 'category', 'level'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Reference $rtmmi)
    {
        $referenceFields1 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(1, 1, 12)");
        
        $referenceFields2 = DB::select("CALL get_academic_development_fields_by_form_id_and_field_ids(1, 13, 14)");

        $referenceDocuments = ReferenceDocument::where('reference_id', $rtmmi->id)->get()->toArray();

        $category = DropdownOption::where('id', $rtmmi->category)
                            ->select('dropdown_options.name')->first();

        $departments = Department::all();
        $colleges = College::all();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$rtmmi->department_id.")");
        
        $value = $rtmmi;
        $value->toArray();
        $value = collect($rtmmi);
        $value = $value->toArray();
        
        return view('academic-development.references.edit', compact('value', 'referenceFields1', 'referenceFields2', 'referenceDocuments', 'colleges', 'departments', 'category', 'collegeOfDepartment'));
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
        $input = $request->except(['_token', '_method', 'document', 'college_id']);

        $rtmmi->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RTMMI-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('faculty.rtmmi.index')->with('edit_rtmmi_success', strtolower($accomplishment[0]))
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
        $rtmmi->delete();
        ReferenceDocument::where('reference_id', $rtmmi->id)->delete();

        $accomplished = DB::select("CALL get_dropdown_name_by_id(".$rtmmi->category.")");

        $accomplished = collect($accomplished);
        $accomplishment = $accomplished->pluck('name');

        return redirect()->route('faculty.rtmmi.index')->with('edit_rtmmi_success', strtolower($accomplishment[0])
                            ->with('action', 'deleted.'));
    }
}
