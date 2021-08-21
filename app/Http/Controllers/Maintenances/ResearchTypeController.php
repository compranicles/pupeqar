<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\ResearchType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResearchTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $researchtypes = ResearchType::all();
        return view('admin.maintenances.researchtype.index', compact('researchtypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.researchtype.create');
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

        ResearchType::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchtype')->with('success', 'Added successfully');
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
    public function edit(ResearchType $research_type)
    {
        return view('admin.maintenances.researchtype.edit', compact('research_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchType $research_type)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $research_type->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchtype')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchType $research_type)
    {
        $research_type->delete();
        return redirect()->route('admin.maintenances.researchtype')->with('success','Deleted successfully.');
    }
}
