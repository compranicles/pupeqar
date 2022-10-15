<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Exception;
use Illuminate\Facades\Storage;

class UploadController extends Controller
{
    //Make sure that the input name in views is "document"
    public function store(Request $request){

        try {
            if($request->hasFile('document')){
                $files = $request->file('document');
                
                foreach($files as $file){
                    $folder = uniqid()."-".now()->timestamp;
                    $filename = time().rand(1,100).'.'.$file->extension();
                    $file->storeAs('documents/tmp/'.$folder, $filename);
    
                    TemporaryFile::create([
                        'folder' => $folder, 
                        'filename' => $filename,
                    ]);
                    
                    return $folder;
                }
            }
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        }
        
        // elseif($request->hasFile('image')){
        //     $files = $request->file('image');

        //     foreach($files as $file){
        //         $folder = uniqid()."-".now()->timestamp;
        //         $filename = $file->getClientOriginalName();
        //         $file->storeAs('images/tmp/'.$folder, $filename);

        //         TemporaryFile::create([
        //             'folder' => $folder,
        //             'filename' => $filename,
        //         ]);

        //         return $folder;
        //     }
        // }
        return '';
    }

    public function destroy(Request $request){
        
        // $temporaryFile = TemporaryFile::where('folder', $request->file('document'))->first();
        // if($temporaryFile){
        //     Storage::deleteDirectory("documents/tmp/".$folder);
        //     $temporaryFile->delete();
        // }
        
    }

    // public function destroyImage($request){
    //     $temporaryFile = TemporaryFile::where('folder', $folder)->first();
    //     if($temporaryFile){
    //         Storage::deleteDirectory("images/tmp/".$folder);
    //         $temporaryFile->delete();
    //     }
    // }
}
