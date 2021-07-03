<?php

namespace App\Http\Controllers\Administrators;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventtypes = EventType::where('status', 1)->get();
        return view('admin.events.types.index', compact('eventtypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.events.types.create');
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required','string', 'max:255'],
        ]);

        EventType::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'status' => 1,
        ]);

        return redirect()->route('admin.event_types.index')->with('success','Event Type added successfully');
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
    public function edit(EventType $eventType)
    {
        return view('admin.events.types.edit', compact('eventType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventType $eventType)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required','string', 'max:255'],
        ]);

        $eventType->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.event_types.index')->with('success','Event Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventType $eventType)
    {
        $eventType->update([
            'status' => 0,
        ]);
        return redirect()->route('admin.event_types.index')->with('success','Event Type deleted successfully.');
    }
}
