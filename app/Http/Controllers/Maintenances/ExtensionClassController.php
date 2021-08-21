<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ExtensionClass;
use App\Http\Controllers\Controller;

class ExtensionClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extensionclasses = ExtensionClass::all();
        return view('admin.maintenances.extensionclass.index', compact('extensionclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.extensionclass.create');
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

        ExtensionClass::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.extensionclass')->with('success', 'Added successfully');
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
    public function edit(ExtensionClass $extension_classification)
    {
        return view('admin.maintenances.extensionclass.edit', compact('extension_classification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtensionClass $extension_classification)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $extension_classification->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.extensionclass')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionClass $extension_classification)
    {
        $extension_classification->delete();
        return redirect()->route('admin.maintenances.extensionclass')->with('success','Deleted successfully.');
    }
}
