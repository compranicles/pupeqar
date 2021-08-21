<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\FacultyAward;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacultyAwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faculty_awards = FacultyAward::all();
        return view('admin.maintenances.facultyaward.index', compact('faculty_awards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.facultyaward.create');
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

        FacultyAward::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.facultyaward')->with('success', 'Added successfully');
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
    public function edit(FacultyAward $faculty_award)
    {
        return view('admin.maintenances.facultyaward.edit', compact('faculty_award'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FacultyAward $faculty_award)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $faculty_award->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.facultyaward')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacultyAward $faculty_award)
    {
        $faculty_award->delete();
        return redirect()->route('admin.maintenances.facultyaward')->with('success','Deleted successfully.');
    }
}
