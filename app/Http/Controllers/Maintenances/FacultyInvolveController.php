<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\FacultyInvolve;
use App\Http\Controllers\Controller;

class FacultyInvolveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facultyinvolves = FacultyInvolve::all();
        return view('admin.maintenances.facultyinvolve.index', compact('facultyinvolves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.facultyinvolve.create');
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

        FacultyInvolve::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.facultyinvolve')->with('success', 'Added successfully');
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
    public function edit(FacultyInvolve $faculty_involvement)
    {
        return view('admin.maintenances.facultyinvolve.edit', compact('faculty_involvement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacultyInvolve $faculty_involvement)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $faculty_involvement->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.facultyinvolve')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacultyInvolve $faculty_involvement)
    {
        $faculty_involvement->delete();
        return redirect()->route('admin.maintenances.facultyinvolve')->with('success','Deleted successfully.');
    }
}
