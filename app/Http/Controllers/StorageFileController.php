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

        if($hris == '4' || $hris == '5'){
            $data = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCodeAndID N'$user->emp_code', '$id'");
        }
        elseif($hris == '3'){
            $data = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipMembershipByEmpCodeAndID N'$user->emp_code', '$id'");
        }
        elseif($hris == '1'){
            $data = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$id");
        }

        if($data['0']->Attachment == null){
            $path = storage_path('app/public/no-document-attached.jpg');
            $file = File::get($path);
            $type = File::mimeType($path);
            $headers = ['Content-Type: '.$type];

            return response()->file($path, $headers);
        }
        else{
            $image_file = Image::make($data['0']->Attachment);
        }
        $response = Response::make($image_file->encode('jpeg'));
        $response->header('Content-Type', 'image/jpeg');
        return $response;
    }
}
