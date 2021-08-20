<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ResearchClass;
use App\Http\Controllers\Controller;

class ResearchClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $researchclasses = ResearchClass::all();
        return view('admin.maintenances.researchclass.index', compact('researchclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.researchclass.create');
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

        ResearchClass::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchclass')->with('success', 'Added successfully');
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
    public function edit(ResearchClass $research_classification)
    {
        return view('admin.maintenances.researchclass.edit', compact('research_classification'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchClass $research_classification)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $research_classification->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchclass')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchClass $research_classification)
    {
        $research_classification->delete();
        return redirect()->route('admin.maintenances.researchclass')->with('success','Deleted successfully.');
    }
}
