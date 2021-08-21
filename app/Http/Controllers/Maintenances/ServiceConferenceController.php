<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ServiceConference;
use App\Http\Controllers\Controller;

class ServiceConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serviceconferences = ServiceConference::all();
        return view('admin.maintenances.serviceconference.index', compact('serviceconferences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.serviceconference.create');
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

        ServiceConference::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.serviceconference')->with('success', 'Added successfully');
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
    public function edit(ServiceConference $service_conference)
    {
        return view('admin.maintenances.serviceconference.edit', compact('service_conference'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceConference $service_conference)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $service_conference->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.serviceconference')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceConference $service_conference)
    {
        $service_conference->delete();
        return redirect()->route('admin.maintenances.serviceconference')->with('success','Deleted successfully.');
    }
}
