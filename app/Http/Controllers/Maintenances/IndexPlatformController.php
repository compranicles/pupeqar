<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\IndexPlatform;
use App\Http\Controllers\Controller;

class IndexPlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indexplatforms = IndexPlatform::all();
        return view('admin.maintenances.indexplatform.index', compact('indexplatforms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.indexplatform.create');
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

        IndexPlatform::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.indexplatform')->with('success', 'Added successfully');
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
    public function edit(IndexPlatform $index_platform)
    {
        return view('admin.maintenances.indexplatform.edit', compact('index_platform'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IndexPlatform $index_platform)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $index_platform->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.indexplatform')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IndexPlatform $index_platform)
    {
        $index_platform->delete();
        return redirect()->route('admin.maintenances.indexplatform')->with('success','Deleted successfully.');
    }
}
