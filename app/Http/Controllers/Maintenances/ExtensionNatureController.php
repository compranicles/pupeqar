<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ExtensionNature;
use App\Http\Controllers\Controller;

class ExtensionNatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extensionnatures = ExtensionNature::all();
        return view('admin.maintenances.extensionnature.index', compact('extensionnatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.extensionnature.create');
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

        ExtensionNature::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.extensionnature')->with('success', 'Added successfully');
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
    public function edit(ExtensionNature $extension_nature)
    {
        return view('admin.maintenances.extensionnature.edit', compact('extension_nature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtensionNature $extension_nature)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $extension_nature->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.extensionnature')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionNature $extension_nature)
    {
        $extension_nature->delete();
        return redirect()->route('admin.maintenances.extensionnature')->with('success','Deleted successfully.');
    }
}
