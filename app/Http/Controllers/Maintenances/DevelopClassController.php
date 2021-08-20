<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\DevelopClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DevelopClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $developclasses = DevelopClass::all();
        return view('admin.maintenances.developclass.index', compact('developclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.developclass.create');
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

        DevelopClass::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.developclass')->with('success', 'Added successfully');
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
    public function edit(DevelopClass $development_classification)
    {
        return view('admin.maintenances.developclass.edit', compact('development_classification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DevelopClass $development_classification)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $development_classification->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.developclass')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DevelopClass $development_classification)
    {
        $development_classification->delete();
        return redirect()->route('admin.maintenances.developclass')->with('success','Deleted successfully.');
    }
}
