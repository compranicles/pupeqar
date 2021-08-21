<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ServiceJournal;
use App\Http\Controllers\Controller;

class ServiceJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicejournals = ServiceJournal::all();
        return view('admin.maintenances.servicejournal.index', compact('servicejournals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.servicejournal.create');
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
            'name' => 'required'
        ]);

        ServiceJournal::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.servicejournal')->with('success', 'Added successfully');
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
    public function edit(ServiceJournal $service_journal)
    {
        return view('admin.maintenances.servicejournal.edit', compact('service_journal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceJournal $service_journal)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $service_journal->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.servicejournal')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceJournal $service_journal)
    {
        $service_journal->delete();
        return redirect()->route('admin.maintenances.servicejournal')->with('success','Deleted successfully.');
    }
}
