<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Department;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colleges = College::get();
        return view('maintenances.colleges.index', compact('colleges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenances.colleges.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatecollege = $request->validate([
            'name' => 'required|max:200',
        ]);

        $college = College::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('colleges.create')->with('add_college_success', 'Added college has been saved.');
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
    public function edit(College $college)
    {
        //
        $departments = Department::select('name')->where('college_id', $college->id)->get();
        // dd($departments);

        return view('maintenances.colleges.edit', compact('college', 'departments'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, College $college)
    {
        //
        $request->validate([
            'name' => 'required|max:200',
        ]);

        $college->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('colleges.index')->with('edit_college_success', 'Edit in college has been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(College $college)
    {
        //
        $college->delete();

        return redirect()->route('colleges.index')->with('edit_college_success', 'College has been deleted.');
    }
}
