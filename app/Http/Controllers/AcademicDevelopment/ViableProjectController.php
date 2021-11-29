<?php

namespace App\Http\Controllers\AcademicDevelopment;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ViableProject;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ViableProjectDocument;
use Illuminate\Support\Facades\Storage;

class ViableProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viable_projects = ViableProject::where('user_id', auth()->id())->get();
        return view('academic-development.viable-project.index', compact('viable_projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projectFields = DB::select("CALL get_academic_development_fields_by_form_id(5)");

        return view('academic-development.viable-project.create', compact('projectFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token', '_method', 'document']);

        $viable_project = ViableProject::create($input);
        $viable_project->update(['user_id' => auth()->id()]);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ViableProject-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('viable-project.index')->with('project_success', 'Your Accomplishment in Viable Demonstration Project has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ViableProject $viable_project)
    {
        $projectFields = DB::select("CALL get_academic_development_fields_by_form_id(5)");

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
        $projectFields = DB::select("CALL get_academic_development_fields_by_form_id(5)");

        $documents = ViableProjectDocument::where('viable_project_id', $viable_project->id)->get()->toArray();

        $values = $viable_project->toArray();

        return view('academic-development.viable-project.edit', compact('projectFields', 'viable_project', 'documents', 'values'));
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
        $input = $request->except(['_token', '_method', 'document']);

        $viable_project->update($input);
        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'ViableProject-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('viable-project.index')->with('project_success', 'Your Accomplishment in Viable Demonstration Project has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ViableProject $viable_project)
    {
        ViableProjectDocument::where('viable_project_id', $viable_project->id)->delete();
        $viable_project->delete();
        return redirect()->route('viable-project.index')->with('project_success', 'Your accomplishment in Viable Demonstration Project has been deleted.');
    }

    public function removeDoc($filename){
        ViableProjectDocument::where('filename', $filename)->delete();
        return true;
    }
}
