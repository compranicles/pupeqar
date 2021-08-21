<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ResearchLevel;
use App\Http\Controllers\Controller;

class ResearchLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $researchlevels = ResearchLevel::all();
        return view('admin.maintenances.researchlevel.index', compact('researchlevels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.researchlevel.create');
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

        ResearchLevel::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchlevel')->with('success', 'Added successfully');
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
    public function edit(ResearchLevel $research_level)
    {
        return view('admin.maintenances.researchlevel.edit', compact('research_level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchLevel $research_level)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $research_level->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchlevel')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchLevel $research_level)
    {
        $research_level->delete();
        return redirect()->route('admin.maintenances.researchlevel')->with('success','Deleted successfully.');
    }
}
