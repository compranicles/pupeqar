<?php

namespace App\Http\Controllers\AcademicDevelopment;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    TechnicalExtension,
    TechnicalExtensionDocument,
    TemporaryFile,
    FormBuilder\AcademicDevelopmentForm,
    Maintenance\Quarter,
};

class TechnicalExtensionController extends Controller
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
        $this->authorize('viewAny', TechnicalExtension::class);

        $currentQuarterYear = Quarter::find(1);

        $technical_extensions = TechnicalExtension::where('user_id', auth()->id())
                                ->select(DB::raw('technical_extensions.*'))
                                ->orderBy('technical_extensions.updated_at', 'desc')->get();
                                
        return view('academic-development.technical-extension.index', compact('technical_extensions', 'currentQuarterYear'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', TechnicalExtension::class);

        if(AcademicDevelopmentForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extensionFields = DB::select("CALL get_academic_development_fields_by_form_id(7)");

        return view('academic-development.technical-extension.create', compact('extensionFields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', TechnicalExtension::class);

        $value = $request->input('total_profit');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'total_profit' => $value,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);
        $request->validate([
            'moa_code' => 'required',
            // 'total_profit' => 'numeric',
        ]);

        if(AcademicDevelopmentForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $technical_extension = TechnicalExtension::create($input);
        $technical_extension->update(['user_id' => auth()->id()]);

        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'TEPPA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    TechnicalExtensionDocument::create([
                        'technical_extension_id' => $technical_extension->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a technical extension program, project, or activity.');


        return redirect()->route('technical-extension.index')->with('extension_success', 'Technical extension program, project, or activity has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TechnicalExtension $technical_extension)
    {
        $this->authorize('view', TechnicalExtension::class);

        if (auth()->id() !== $technical_extension->user_id)
            abort(403);

        if(AcademicDevelopmentForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extensionFields = DB::select("CALL get_academic_development_fields_by_form_id(7)");

        $documents = TechnicalExtensionDocument::where('technical_extension_id', $technical_extension->id)->get()->toArray();

        $values = $technical_extension->toArray();

        return view('academic-development.technical-extension.show', compact('extensionFields', 'technical_extension', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TechnicalExtension $technical_extension)
    {
        $this->authorize('update', TechnicalExtension::class);

        if (auth()->id() !== $technical_extension->user_id)
            abort(403);

        if(LockController::isLocked($technical_extension->id, 23)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(AcademicDevelopmentForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $extensionFields = DB::select("CALL get_academic_development_fields_by_form_id(7)");

        $documents = TechnicalExtensionDocument::where('technical_extension_id', $technical_extension->id)->get()->toArray();

        $values = $technical_extension->toArray();

        return view('academic-development.technical-extension.edit', compact('extensionFields', 'technical_extension', 'documents', 'values'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TechnicalExtension $technical_extension)
    {
        $this->authorize('update', TechnicalExtension::class);

        $value = $request->input('total_profit');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        $request->merge([
            'total_profit' => $value,
        ]);
        
        $request->validate([
            'moa_code' => 'required',
            // 'total_profit' => 'numeric',
        ]);

        if(AcademicDevelopmentForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $input = $request->except(['_token', '_method', 'document']);

        $technical_extension->update(['description' => '-clear']);

        $technical_extension->update($input);
        
        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'TEPPA-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    TechnicalExtensionDocument::create([
                        'technical_extension_id' => $technical_extension->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated a technical extension program, project, or activity.');


        return redirect()->route('technical-extension.index')->with('extension_success', 'Technical extension program, project, or activity has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechnicalExtension $technical_extension)
    {
        $this->authorize('delete', TechnicalExtension::class);

        if(LockController::isLocked($technical_extension->id, 23)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(AcademicDevelopmentForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        TechnicalExtensionDocument::where('technical_extension_id', $technical_extension->id)->delete();
        $technical_extension->delete();

        \LogActivity::addToLog('Had deleted a technical extension program, project, or activity.');

        return redirect()->route('technical-extension.index')->with('extension_success', 'Technical extension program, project, or activity has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', TechnicalExtension::class);

        if(AcademicDevelopmentForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        TechnicalExtensionDocument::where('filename', $filename)->delete();

        \LogActivity::addToLog('Had deleted a document of a technical extension program, project, or activity.');

        return true;
    }
}
