<?php

namespace App\Http\Controllers;

use Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class StorageFileController extends Controller
{
    public function getDocumentFile($filename)
    {
        $path = storage_path('app/documents/'. $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);

        $response->header("Content-Type", $type);

        return $response;

    }

    public function downloadFile($filename){
        $path = storage_path('app/documents/'.$filename);
        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        // $response = Response::make($file, 200);
        // $response->header("Content-Type", $type);
        $headers = ['Content-Type: '.$type];
        return response()->download($path, $filename, $headers);
    }

    public function viewFile($filename){
        $path = storage_path('app/documents/'.$filename);
        $file = File::get($path);
        $type = File::mimeType($path);
        $headers = ['Content-Type: '.$type];

        return response()->file($path, $headers);
    }

    public function abbrev($string){

        if($string == '' || is_null($string))
            return 'NA';
        $parts = explode(' ',$string);
        $initials = '';
        $count = 0;
        foreach($parts as $part) {
            if($count >= 5) break;
            $initials .= ucwords(strtoupper($part[0]));
            $count++;
        }
        return $initials;
    }

    public function fetch_image($id, $hris){
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");
        if($hris == '4' || $hris == '5'){
            foreach($developments as $development){
                if($development->EmployeeTrainingProgramID == $id){
                    $data = $development;
                    break;
                }
            }
        }
        elseif($hris == '3'){
            $data = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code', '$id'");
        }
        elseif($hris == '1'){
            $data = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$id");
        }
        elseif($hris == '2'){
            $data = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOutstandingAchievementByEmpCodeAndID N'$user->emp_code',$id");
        }

        // if($data['0']->Attachment == null){
        //     $path = storage_path('app/public/no-document-attached.jpg');
        //     $file = File::get($path);
        //     $type = File::mimeType($path);
        //     $headers = ['Content-Type: image/jpeg'];

        //     return response()->file($path, $headers);
        //     return "No Document";
        // }
// dd($data);
        $imagejpeg = ['image/jpeg', 'image/pjpeg', 'image/jpg', 'image/jfif', 'image/pjp'];

        if ($hris >= 1 && $hris <= 3) {
            if(isset($data['0']->MimeType)){
                if(in_array($data['0']->MimeType, $imagejpeg)){
                    $image = $data['0']->Attachment;
                    $response = Response::make($image);
                    $response->header('Content-Type', 'image/jpeg');
                    return $response;
                }
                elseif($data['0']->MimeType == 'image/png' || $data['0']->MimeType == 'image/x-png'){
                    $image = Image::make($data['0']->Attachment);
                    $response = Response::make($image->encode('png'));
                    $response->header('Content-Type', 'image/png');
                    return $response;
                }
                elseif($data['0']->MimeType == 'application/pdf'){
                    $pdfstring = $data['0']->Attachment;
                    $response = Response::make($pdfstring);
                    $response->header('Content-Type', 'application/pdf');
                    return $response;
                }
            }
            else{
                $image = Image::make($data['0']->AttachmentPic);
                $response = Response::make($image->encode('png'));
                $response->header('Content-Type', 'image/png');
                return $response;
            }
        }
    }

    public function fetch_images($id, $hris, $docNumber) {
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");
        foreach($developments as $development){
            if($development->EmployeeTrainingProgramID == $id){
                $data = $development;
                break;
            }
        }

        $imagejpeg = ['image/jpeg', 'image/pjpeg', 'image/jpg', 'image/jfif', 'image/pjp'];

        if($hris == '4' || $hris == '5'){
            if ($docNumber == 1) {
                if(isset($data->MimeTypeSO)){
                    if(in_array($data->MimeTypeSO, $imagejpeg)){
                        $image = $data->AttachmentSO;
                        $response = Response::make($image);
                        $response->header('Content-Type', 'image/jpeg');
                        return $response;
                    }
                    elseif($data->MimeTypeSO == 'image/png' || $data->MimeTypeSO == 'image/x-png'){
                        $image = Image::make($data->AttachmentSO);
                        $response = Response::make($image->encode('png'));
                        $response->header('Content-Type', 'image/png');
                        return $response;
                    }
                    elseif($data->MimeTypeSO == 'application/pdf'){
                        $pdfstring = $data->AttachmentSO;
                        $response = Response::make($pdfstring);
                        $response->header('Content-Type', 'application/pdf');
                        return $response;
                    }
                }
            }
            elseif ($docNumber == 2) {
                if(isset($data->MimeTypeCert)){
                    if(in_array($data->MimeTypeCert, $imagejpeg)){
                        $image = $data->AttachmentCert;
                        $response = Response::make($image);
                        $response->header('Content-Type', 'image/jpeg');
                        return $response;
                    }
                    elseif($data->MimeTypeCert == 'image/png' || $data->MimeTypeCert == 'image/x-png'){
                        $image = Image::make($data->AttachmentCert);
                        $response = Response::make($image->encode('png'));
                        $response->header('Content-Type', 'image/png');
                        return $response;
                    }
                    elseif($data->MimeTypeCert == 'application/pdf'){
                        $pdfstring = $data->AttachmentCert;
                        $response = Response::make($pdfstring);
                        $response->header('Content-Type', 'application/pdf');
                        return $response;
                    }
                }
            }
            else {
                if(isset($data->MimeTypePic)){
                    if(in_array($data->MimeTypePic, $imagejpeg)){
                        $image = $data->AttachmentPic;
                        $response = Response::make($image);
                        $response->header('Content-Type', 'image/jpeg');
                        return $response;
                    }
                    elseif($data->MimeTypePic == 'image/png' || $data->MimeTypePic == 'image/x-png'){
                        $image = Image::make($data->AttachmentPic);
                        $response = Response::make($image->encode('png'));
                        $response->header('Content-Type', 'image/png');
                        return $response;
                    }
                    elseif($data->MimeTypePic == 'application/pdf'){
                        $pdfstring = $data->AttachmentPic;
                        $response = Response::make($pdfstring);
                        $response->header('Content-Type', 'application/pdf');
                        return $response;
                    }
                }
            }
        }
    }
}
