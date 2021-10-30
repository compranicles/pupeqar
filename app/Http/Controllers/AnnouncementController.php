<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements = Announcement::orderByDesc('created_at')->get();
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required'],
            'status' => ['required']
        ]);        

        Announcement::create([
            'title' => $request->input('title'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required'],
            'status' => ['required']
        ]);        

        $announcement->update([
            'title' => $request->input('title'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('announcements.index')->with('success','Announcement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success','Announcement deleted successfully.');
    }

    public function hide(Announcement $announcement)
    {
        $announcement->update([
            'status' => 2,
        ]);
        return redirect()->route('announcements.index')->with('success','Announcement hidden successfully.');
    }

    public function activate(Announcement $announcement) 
    {
        $announcement->update([
            'status' => 1,
        ]);
        return redirect()->route('announcements.index')->with('success','Announcement activated successfully.');
    }
    
    public function showMessage($id){
        return Announcement::findOrFail($id);
   }
}