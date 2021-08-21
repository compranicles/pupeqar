<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ServiceConsultant;
use App\Http\Controllers\Controller;

class ServiceConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serviceconsultants = ServiceConsultant::all();
        return view('admin.maintenances.serviceconsultant.index', compact('serviceconsultants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.serviceconsultant.create');
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

        ServiceConsultant::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.serviceconsultant')->with('success', 'Added successfully');
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
    public function edit(ServiceConsultant $service_consultant)
    {
        return view('admin.maintenances.serviceconsultant.edit', compact('service_consultant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceConsultant $service_consultant)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $service_consultant->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.serviceconsultant')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceConsultant $service_consultant)
    {
        $service_consultant->delete();
        return redirect()->route('admin.maintenances.serviceconsultant')->with('success','Deleted successfully.');
    }
}
