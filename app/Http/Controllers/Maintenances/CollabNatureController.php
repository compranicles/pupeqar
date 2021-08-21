<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\CollabNature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollabNatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collabnatures = CollabNature::all();
        return view('admin.maintenances.collabnature.index', compact('collabnatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.collabnature.create');
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

        CollabNature::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.collabnature')->with('success', 'Added successfully');
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
    public function edit(CollabNature $collaboration_nature)
    {
        return view('admin.maintenances.collabnature.edit', compact('collaboration_nature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CollabNature $collaboration_nature)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $collaboration_nature->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.collabnature')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollabNature $collaboration_nature)
    {
        $collaboration_nature->delete();
        return redirect()->route('admin.maintenances.collabnature')->with('success','Deleted successfully.');
    }
}
