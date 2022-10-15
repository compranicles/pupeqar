<?php
// =============================================================================================
// TITLE: COMMON SERVICE SERVICE
// DESCRIPTION: USED FOR HANDLING REPETATIVE FUNCTION IN THE PROGRAM
// DEVELOPER: TERRENCE CALZADA
// DATE: OCTOBER 16, 2022
// =============================================================================================

namespace App\Services;

use App\Http\Controllers\StorageFileController;
use App\Models\TemporaryFile;
use Exception;
use Illuminate\Support\Facades\Storage;

class CommonService {

    private $storageFileController;

    public function __construct(StorageFileController $storageFileController){
        $this->storageFileController = $storageFileController;
    }

    /**
     * =============================================================================================
     * 
     * File upload handler function that returns a string
     * 
     * @param file $file this paramenter is the file itself or input request
     * 
     * @param input $description this parameter is the description input request.
     * 
     * @param string $additiveName this parameter is added to the filename and is user define.
     * 
     * @param string $route this parameter is used to redirect in other page with an error message.
     * 
     * @return string the file name of the uploaded file.
     * 
     * =============================================================================================
     */
    public function fileUploadHandler($file, $description, $additiveName, $route){
        $fileName = "";
        try {
            $tempFile = TemporaryFile::where('folder', $file)->first();
            if($tempFile){
                $temporaryPath = "documents/tmp/".$file."/".$tempFile->filename;
                $info = pathinfo(storage_path().'/documents/tmp/'.$file."/".$tempFile->filename);
                $ext = $info['extension'];
                $fileName = $additiveName.$this->storageFileController->abbrev($description).'-'.now()->timestamp.uniqid().'.'.$ext;
                Storage::move($temporaryPath, "documents/".$fileName);
                Storage::deleteDirectory("documents/tmp/".$file);
                $tempFile->delete();
                return $fileName;
            }
            throw new Exception("1");
        } catch (Exception $error) {
            return redirect()->route($route)->with( 'error', 
                $error->getMessage() == "1" ? "Entry was saved but unable to upload documents, Please try reuploading the documents!" : 'Request timeout, Unable to upload documents, Please try again!'
            );
        }
    }
}