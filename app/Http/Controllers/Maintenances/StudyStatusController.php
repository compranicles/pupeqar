<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\StudyStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudyStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studystatuses = StudyStatus::all();
        return view('admin.maintenances.studystatus.index', compact('studystatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.studystatus.create');
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

        StudyStatus::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.studystatus')->with('success', 'Added successfully');
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
    public function edit(StudyStatus $study_status)
    {
        return view('admin.maintenances.studystatus.edit', compact('study_status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudyStatus $study_status)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $study_status->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.studystatus')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudyStatus $study_status)
    {
        $study_status->delete();
        return redirect()->route('admin.maintenances.studystatus')->with('success','Deleted successfully.');
    }
}
