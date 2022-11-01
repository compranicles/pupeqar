<?php
// =============================================================================================
// TITLE: COMMON SERVICE SERVICE
// DESCRIPTION: USED FOR HANDLING REPETATIVE FUNCTION IN THE PROGRAM
// DEVELOPER: TERRENCE CALZADA
// DATE: OCTOBER 16, $reportCategoryId022
// =============================================================================================

namespace App\Services;

use Exception;
use App\Models\TemporaryFile;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;

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
            $fileName = $additiveName . $fileDesc . '-' . now()->timestamp.uniqid() . '.' .  $file->extension();
            $file->storeAs('documents', $fileName, 'local');
            return $fileName;

        } catch (\Throwable $error) {
            return redirect()->route($route)->with( 'error', 'Request timeout, Unable to upload documents, Please try again later! : '. $error->getMessage());  
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



// $tempFile = TemporaryFile::where('folder', $file)->first();
// if($tempFile){
//     $temporaryPath = "documents/tmp/".$file."/".$tempFile->filename;
//     $info = pathinfo(storage_path().'/documents/tmp/'.$file."/".$tempFile->filename);
//     $ext = $info['extension'];
//     $fileName = $additiveName . $fileDesc . '-' . now()->timestamp.uniqid() . '.' . $ext;
//     Storage::move($temporaryPath, "documents/".$fileName);
//     Storage::deleteDirectory("documents/tmp/".$file);
//     $tempFile->delete();
//     return $fileName;
// }
// throw new Exception("1");

    /**
     * =============================================================================================
     * 
     * Get submission status function that returns an object or an associative array. 
     * 
     * @param Int $reportCategoryId this paramenter is the ID of report/form category.
     * 
     * @param Int $primaryId this parameter is the primary ID of record.
     * 
     * @return Object with key value pair; $submissionStatus and $submitRole.
     * 
     * =============================================================================================
     */

    public function getSubmissionStatus($primaryId, $reportCategoryId){
        $submissionStatus = array();
        $submitRole = array();
        $submitRole[$primaryId] = 0;
        $reportdata = new ReportDataController;
            if (LockController::isLocked($primaryId, $reportCategoryId)) {
                $submissionStatus[$reportCategoryId][$primaryId] = 1;
                $submitRole[$primaryId] = ReportDataController::getSubmitRole($primaryId, $reportCategoryId);
            }
            else
                $submissionStatus[$reportCategoryId][$primaryId] = 0;
            if (empty($reportdata->getDocuments($reportCategoryId, $primaryId)))
                $submissionStatus[$reportCategoryId][$primaryId] = 2;
            
            if ($submissionStatus[$reportCategoryId][$primaryId] == null)
                $submissionStatus[$reportCategoryId][$primaryId] = null;
        return [
            'submissionStatus' => $submissionStatus[$reportCategoryId][$primaryId],
            'submitRole' => $submitRole[$primaryId]
        ];
    }

    /**
     * =============================================================================================
     * 
     * File upload handler for external database function that returns an object or an associative array. 
     * 
     * @param Array $formFields this paramenter is contains an array of fields of a form.
     * 
     * @param Array $formValues this parameter contains the record values.
     * 
     * @return Array with key value pair; $formValues.
     * 
     * =============================================================================================
     */
    public function getDropdownValues($formFields, $formValues) {
        foreach($formFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $formValues[$field->name])->where('is_active', 1)->pluck('name')->first();
                if($dropdownOptions == null)
                    $dropdownOptions = "-";
                $formValues[$field->name] = $dropdownOptions;
            }
            elseif($field->field_type_name == "college"){
                if($formValues[$field->name] == '0'){
                    $formValues[$field->name] = 'N/A';
                }
                else{
                    $college = College::where('id', $formValues[$field->name])->pluck('name')->first();
                    $formValues[$field->name] = $college;
                }
            }
            elseif($field->field_type_name == "department"){
                if($formValues[$field->name] == '0'){
                    $formValues[$field->name] = 'N/A';
                }
                else{
                    $department = Department::where('id', $formValues[$field->name])->pluck('name')->first();
                    $formValues[$field->name] = $department;
                }
            }
        }

        return $formValues;
    }
}
