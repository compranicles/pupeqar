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
        $this->authorize('viewAny', Announcement::class);

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
        $this->authorize('create', Announcement::class);

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
        $this->authorize('create', Announcement::class);
        
        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required'],
            'status' => ['required']
        ]);        

        Announcement::create([
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
        $this->authorize('view', Announcement::class);

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
        $this->authorize('update', Announcement::class);

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
        $this->authorize('update', Announcement::class);

        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required'],
            'status' => ['required']
        ]);        

        $announcement->update([
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
        $this->authorize('delete', Announcement::class);

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