<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
