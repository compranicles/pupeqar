<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\Level;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = Level::all();
        return view('admin.maintenances.level.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.level.create');
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

        Level::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.level')->with('success', 'Added successfully');
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
    public function edit(Level $level)
    {
        return view('admin.maintenances.level.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Level $level)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $level->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.level')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {
        $level->delete();
        return redirect()->route('admin.maintenances.level')->with('success','Deleted successfully.');
    }
}
