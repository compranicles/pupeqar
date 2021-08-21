<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\CollabDeliver;
use App\Http\Controllers\Controller;

class CollabDeliverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collabdelivers = CollabDeliver::all();
        return view('admin.maintenances.collabdeliver.index', compact('collabdelivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.collabdeliver.create');
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

        CollabDeliver::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.collabdeliver')->with('success', 'Added successfully');
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
    public function edit(CollabDeliver $collaboration_deliverable)
    {
        return view('admin.maintenances.collabdeliver.edit', compact('collaboration_deliverable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CollabDeliver $collaboration_deliverable)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $collaboration_deliverable->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.collabdeliver')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CollabDeliver $collaboration_deliverable)
    {
        $collaboration_deliverable->delete();
        return redirect()->route('admin.maintenances.collabdeliver')->with('success','Deleted successfully.');
    }
}
