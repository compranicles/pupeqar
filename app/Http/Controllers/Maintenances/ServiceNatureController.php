<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ServiceNature;
use App\Http\Controllers\Controller;

class ServiceNatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicenatures = ServiceNature::all();
        return view('admin.maintenances.servicenature.index', compact('servicenatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.servicenature.create');
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

        ServiceNature::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.servicenature')->with('success', 'Added successfully');
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
    public function edit(ServiceNature $service_nature)
    {
        return view('admin.maintenances.servicenature.edit', compact('service_nature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceNature $service_nature)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $service_nature->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.servicenature')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceNature $service_nature)
    {
        $service_nature->delete();
        return redirect()->route('admin.maintenances.servicenature')->with('success','Deleted successfully.');
    }
}
