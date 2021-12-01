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
        $this->authorize('viewAny', College::class);

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
        $this->authorize('create', College::class);

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
        $this->authorize('create', College::class);

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
        $this->authorize('view', College::class);
        
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
        $this->authorize('update', College::class);

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
        $this->authorize('update', College::class);

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
        $this->authorize('delete', College::class);

        //
        $college->delete();

        return redirect()->route('colleges.index')->with('edit_college_success', 'College has been deleted.');
    }

    public function getCollegeName($id){
        return College::where('id', $id)->pluck('name')->first();
    }
}
