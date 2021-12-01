<?php

namespace App\Http\Controllers\ExtensionPrograms;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\OutreachProgram;
use Illuminate\Support\Facades\DB;
use App\Models\PartnershipDocument;
use App\Http\Controllers\Controller;
use App\Models\OutreachProgramDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ExtensionProgramForm;

class OutreachProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outreach_programs = OutreachProgram::where('user_id', auth()->id())->orderBy('updated_at', 'desc')->get();
        return view('extension-programs.outreach-program.index', compact('outreach_programs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
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
                    $fileName = 'OutreachProgram-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('outreach-program.index')->with('outreach_success', 'Your Accomplishment in Community Relations and Outreach Program has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OutreachProgram $outreach_program)
    {
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
        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $outreach_program->update($input);

        
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'OutreachProgram-'.$request->input('description').'-'.now()->timestamp.uniqid().'.'.$ext;
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

        return redirect()->route('outreach-program.index')->with('outreach_success', 'Your Accomplishment in Community Relations and Outreach Program has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutreachProgram $outreach_program)
    {
        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        OutreachProgramDocument::where('outreach_program_id', $outreach_program->id)->delete();
        $outreach_program->delete();
        return redirect()->route('outreach-program.index')->with('outreach_success', 'Your accomplishment in Community Relations and Outreach Program has been deleted.');
    }

    public function removeDoc($filename){
        if(ExtensionProgramForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        OutreachProgramDocument::where('filename', $filename)->delete();
        return true;
    }
}
