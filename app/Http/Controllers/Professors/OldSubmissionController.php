<?php

namespace App\Http\Controllers\Professors;

use App\Models\Event;
use App\Models\Document;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RahulHaque\Filepond\Facades\Filepond;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        $created_by = DB::table('users')->where('id', $event->created_by)->select('first_name', 'last_name')->get();
        $event_types = EventType::all();
        $documents = DB::table('documents')
                    ->join('users', 'users.id', '=', 'documents.user_id')
                    ->join('events', 'events.id', '=', 'documents.event_id')
                    ->select('documents.*', 'users.first_name', 'users.last_name')
                    ->orderBy('documents.created_at', 'desc')
                    // ->orderBy('documents.user_id')
                    ->where('documents.event_id', $event->id)
                    ->where('documents.deleted_at', NULL)
                    ->get();
        // print_r($documents);
        return view('professors.submissions.index', [
            'event' => $event,
            'created_by' => $created_by[0],
            'event_types' => $event_types,
            'documents' => $documents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event, Request $request)
    {
        $request->validate([
            'document' => ['required'],
        ]);
        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $newPath = "documents/".$temporaryFile->filename;
                    $fileName = $temporaryFile->filename;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    Document::create([
                        'filename' => $fileName,
                        'user_id' => Auth::id(),
                        'event_id' => $event->id,
                        'status' => 1,
                    ]);
                }
            }
        }
        // elseif($request->has('image')){
        //     $images = $request->input('image');
        //     foreach($images as $image){
        //         $temporaryFile = TemporaryFile::where('folder', $image)->first();
        //         if($temporaryFile){
        //             $temporaryPath = "images/tmp/".$image."/".$temporaryFile->filename;
        //             $newPath = "images/".$temporaryFile->filename;
        //             Storage::move($temporaryPath, $newPath);
        //             Storage::deleteDirectory("images/tmp/".$image);
        //             $temporaryFile->delete();
        //         }
        //     }
        // }
        return redirect()->route('professor.events.submissions.index', $event)->with('success_submission', 'Submission added successfully.');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteFile(Event $event, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.events.submissions.index', $event)->with('success_submission', 'Submission deleted successfully.');
    }
}
