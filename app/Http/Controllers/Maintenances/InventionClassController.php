<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\InventionClass;
use App\Http\Controllers\Controller;

class InventionClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventionclasses = InventionClass::all();
        return view('admin.maintenances.inventionclass.index', compact('inventionclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.inventionclass.create');
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

        InventionClass::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.inventionclass')->with('success', 'Added successfully');
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
    public function edit(InventionClass $invention_classification)
    {
        return view('admin.maintenances.inventionclass.edit', compact('invention_classification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventionClass $invention_classification)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $invention_classification->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.inventionclass')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventionClass $invention_classification)
    {
        $invention_classification->delete();
        return redirect()->route('admin.maintenances.inventionclass')->with('success','Deleted successfully.');
    }
}
