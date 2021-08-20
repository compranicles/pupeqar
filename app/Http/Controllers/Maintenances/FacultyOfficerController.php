<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\FacultyOfficer;
use App\Http\Controllers\Controller;

class FacultyOfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $facultyofficers = FacultyOfficer::all();
        return view('admin.maintenances.facultyofficer.index', compact('facultyofficers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.facultyofficer.create');
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

        FacultyOfficer::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.facultyofficer')->with('success', 'Added successfully');
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
    public function edit(FacultyOfficer $faculty_officership)
    {
        return view('admin.maintenances.facultyofficer.edit', compact('faculty_officership'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacultyOfficer $faculty_officership)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $faculty_officership->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.facultyofficer')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacultyOfficer $faculty_officership)
    {
        $faculty_officership->delete();
        return redirect()->route('admin.maintenances.facultyofficer')->with('success','Deleted successfully.');
    }
}
