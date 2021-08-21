<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\TrainingClass;
use App\Http\Controllers\Controller;

class TrainingClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainclasses = TrainingClass::all();
        return view('admin.maintenances.trainclass.index', compact('trainclasses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.trainclass.create');
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

        TrainingClass::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.trainclass')->with('success', 'Added successfully');
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
    public function edit(TrainingClass $training_classification)
    {
        return view('admin.maintenances.trainclass.edit', compact('training_classification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainingClass $training_classification)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $training_classification->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.trainclass')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainingClass $training_classification)
    {
        $training_classification->delete();
        return redirect()->route('admin.maintenances.trainclass')->with('success','Deleted successfully.');
    }
}
