<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\InventionStatus;
use App\Http\Controllers\Controller;

class InventionStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventionstatuses = InventionStatus::all();
        return view('admin.maintenances.inventionstatus.index', compact('inventionstatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.inventionstatus.create');
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

        InventionStatus::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.inventionstatus')->with('success', 'Added successfully');
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
    public function edit(InventionStatus $invention_status)
    {
        return view('admin.maintenances.inventionstatus.edit', compact('invention_status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventionStatus $invention_status)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $invention_status->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.inventionstatus')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventionStatus $invention_status)
    {
        $invention_status->delete();
        return redirect()->route('admin.maintenances.inventionstatus')->with('success','Deleted successfully.');
    }
}
