<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ExtensionStatus;
use App\Http\Controllers\Controller;

class ExtensionStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extensionstatuses = ExtensionStatus::all();
        return view('admin.maintenances.extensionstatus.index', compact('extensionstatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.extensionstatus.create');
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

        ExtensionStatus::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.extensionstatus')->with('success', 'Added successfully');
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
    public function edit(ExtensionStatus $extension_status)
    {
        return view('admin.maintenances.extensionstatus.edit', compact('accreditation_level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtensionStatus $extension_status)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $accreditation_level->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.extensionstatus')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionStatus $extension_status)
    {
        $accreditation_level->delete();
        return redirect()->route('admin.maintenances.extensionstatus')->with('success','Deleted successfully.');
    }
}
