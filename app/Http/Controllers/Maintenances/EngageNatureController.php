<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\EngageNature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EngageNatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $engagenatures = EngageNature::all();
        return view('admin.maintenances.engagenature.index', compact('engagenatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.engagenature.create');
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

        EngageNature::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.engagenature')->with('success', 'Added successfully');
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
    public function edit(EngageNature $engagement_nature)
    {
        return view('admin.maintenances.engagenature.edit', compact('engagement_nature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EngageNature $engagement_nature)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $engagement_nature->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.engagenature')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EngageNature $engagement_nature)
    {
        $engagement_nature->delete();
        return redirect()->route('admin.maintenances.engagenature')->with('success','Deleted successfully.');
    }
}
