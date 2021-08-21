<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\AccreLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccreLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accrelevels = AccreLevel::all();
        return view('admin.maintenances.accrelevel.index', compact('accrelevels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.accrelevel.create');
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

        AccreLevel::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.accrelevel')->with('success', 'Added successfully');
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
    public function edit(AccreLevel $accreditation_level)
    {
        return view('admin.maintenances.accrelevel.edit', compact('accreditation_level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccreLevel $accreditation_level)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $accreditation_level->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.accrelevel')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccreLevel $accreditation_level)
    {
        $accreditation_level->delete();
        return redirect()->route('admin.maintenances.accrelevel')->with('success','Deleted successfully.');
    }
}
