<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\DevelopNature;
use App\Http\Controllers\Controller;

class DevelopNatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $developnatures = DevelopNature::all();
        return view('admin.maintenances.developnature.index', compact('developnatures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.developnature.create');
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

        DevelopNature::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.developnature')->with('success', 'Added successfully');
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
    public function edit(DevelopNature $development_nature)
    {
        return view('admin.maintenances.developnature.edit', compact('development_nature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DevelopNature $development_nature)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $development_nature->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.developnature')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DevelopNature $development_nature)
    {
        $development_nature->delete();
        return redirect()->route('admin.maintenances.developnature')->with('success','Deleted successfully.');
    }
}
