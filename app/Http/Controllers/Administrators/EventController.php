<?php

namespace App\Http\Controllers\Administrators;

use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::query()
        ->join('users', 'users.id', '=','events.created_by')
        ->select('events.*', 'users.first_name', 'users.last_name')
        ->orderByDesc('id')->get(); 
        $event_types = EventType::all();

        return view('admin.events.index', [
            'events' => $events,
            'event_types' => $event_types,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $event_types = EventType::all();
        return view('admin.events.create')->with('event_types', $event_types);
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
            'event_type' => ['required'],
            'event_name' => ['required'],
            'date_started' => ['required'],
            'date_ended' => ['required'],
            'location' => ['required']
        ]);

        Event::create([
            'event_type_id' => $request->input('event_type'),
            'name' => $request->input('event_name'),
            'description' => $request->input('description') ?? null,
            'organizer' => $request->input('organizer') ?? null,
            'sponsor' => $request->input('sponsor') ?? null,
            'start_date' => $request->input('date_started'),
            'end_date' => $request->input('date_ended'),
            'location' => $request->input('location'),
            'status' => 0,
            'modified_by' => Auth::id() ?? null,
            'created_by' => Auth::id() ?? null
        ]);
        
        return redirect()->route('admin.events.index')->with('success_event', 'Event added successfully.');
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
    public function edit(Event $event)
    {
        $event_types = EventType::all();
        return view('admin.events.edit', [
            'event' => $event,
            'event_types' => $event_types
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_type' => ['required'],
            'event_name' => ['required'],
            'date_started' => ['required'],
            'date_ended' => ['required'],
            'location' => ['required']
        ]);

        $event->update([
            'event_type_id' => $request->input('event_type'),
            'name' => $request->input('event_name'),
            'description' => $request->input('description') ?? '',
            'organizer' => $request->input('organizer') ?? '',
            'sponsor' => $request->input('sponsor') ?? '',
            'start_date' => $request->input('date_started'),
            'end_date' => $request->input('date_ended'),
            'location' => $request->input('location'),
            'status' => 0,
            'modified_by' => Auth::id()
        ]);
        return redirect()->route('admin.events.index')->with('success_event', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success_event', 'Event deleted successfully');
    }

    public function accept(Event $event){
        $event->update([
            'status' => 1
        ]);
        return redirect()->route('admin.events.index')->with('success_event', 'Event accepted successfully');

    }

    public function reject(Event $event){
        $event->update([
            'status' => 2
        ]);
        return redirect()->route('admin.events.index')->with('success_event', 'Event rejected successfully');

    }
    
    public function close(Event $event){
        $event->update([
            'status' => 3
        ]);
        return redirect()->route('admin.events.index')->with('success_event', 'Event closed successfully');

    }
}