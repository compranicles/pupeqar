<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ResearchInvolve;
use App\Http\Controllers\Controller;

class ResearchInvolveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $researchinvolves = ResearchInvolve::all();
        return view('admin.maintenances.researchinvolve.index', compact('researchinvolves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.researchinvolve.create');
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

        ResearchInvolve::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchinvolve')->with('success', 'Added successfully');
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
    public function edit(ResearchInvolve $research_involvement)
    {
        return view('admin.maintenances.researchinvolve.edit', compact('research_involvement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchInvolve $research_involvement)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $research_involvement->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchinvolve')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchInvolve $research_involvement)
    {
        $research_involvement->delete();
        return redirect()->route('admin.maintenances.researchinvolve')->with('success','Deleted successfully.');
    }
}
