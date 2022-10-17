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
     * @param File $file this paramenter is the file itself or input request
     * 
     * @param Input $description this parameter is the description input request.
     * 
     * @param String $additiveName this parameter is added to the filename and is user define.
     * 
     * @param String $route this parameter is used to redirect in other page with an error message.
     * 
     * @return String the file name of the uploaded file.
     * 
     * =============================================================================================
     */
    public function fileUploadHandler($file, $description, $additiveName, $route){
        $fileDesc = $description == "" ? "" : $this->storageFileController->abbrev($description);
        $fileName = "";
        try {
            $tempFile = TemporaryFile::where('folder', $file)->first();
            if($tempFile){
                $temporaryPath = "documents/tmp/".$file."/".$tempFile->filename;
                $info = pathinfo(storage_path().'/documents/tmp/'.$file."/".$tempFile->filename);
                $ext = $info['extension'];
                $fileName = $additiveName . $fileDesc . '-' . now()->timestamp.uniqid() . '.' . $ext;
                Storage::move($temporaryPath, "documents/".$fileName);
                Storage::deleteDirectory("documents/tmp/".$file);
                $tempFile->delete();
                return $fileName;
            }
        } catch (Exception $error) {
            return redirect()->route($route)->with( 'error', $error->getMessage()
                // $error->getMessage() == "1" ? "Entry was saved but unable to upload documents, Please try reuploading the documents!" : 'Request timeout, Unable to upload documents, Please try again later! : '. $error->getMessage()
            );
        }
    }

    /**
     * =============================================================================================
     * 
     * File upload handler for external database function that returns an object or an associative array. 
     * 
     * @param Request $request this paramenter is the whole request input.
     * 
     * @param String $requestName this parameter is the request name.
     * 
     * @param String $description this parameter is the description of file.
     * 
     * @return Object with key value pair; $isError, $imagedata, $memeType, $description and $message.
     * 
     * =============================================================================================
     */
    public function fileUploadHandlerForExternal($request, $requestName, $description = null){
        try {
            if($request->has($requestName)){
                $datastring = file_get_contents($request->file([$requestName]));
                $mimetype = $request->file($requestName)->getMimeType();
                $imagedata = unpack("H*hex", $datastring);
                $imagedata = '0x' . strtoupper($imagedata['hex']);
                return [
                    'isError' => false,
                    'image' => $imagedata,
                    'description' => $description,
                    'mimetype' => $mimetype,
                ];
            } else {
                return [
                    'isError' => false,
                    'image' => null,
                    'description' => null,
                    'mimetype' => null,
                ];
            }
            
        } catch (Exception $error) {
            echo("<script>console.log('PHP: ". $error->getMessage() ."');</script>");
            return [
                'isError' => true,
                'image' => null,
                'description' => null,
                'mimetype' => null,
                'message' => $error->getMessage()
            ];
        }
    }
}