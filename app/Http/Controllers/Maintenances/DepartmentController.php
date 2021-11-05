<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance\Department;
use App\Models\Maintenance\College;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::get();
        return view('maintenances.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colleges = College::get();
        return view('maintenances.departments.create', compact('colleges'));
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
            'name' => 'required|max:300',
            'college' => 'required'
        ]);

        $department = Department::create([
            'name' => $request->input('name'),
            'college_id' => $request->input('college')
        ]);

        return redirect()->route('departments.create')->with('add_department_success', 'Added department has been saved successfully.');
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
    public function edit(Department $department)
    {
        $collegeOfDept = $department->college->id;
        $colleges = College::get();
        return view('maintenances.departments.edit', compact('department', 'colleges', 'collegeOfDept'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        //
        $request->validate([
            'name' => 'required|max:300',
            'college' => 'required',
        ]);

        $department->update([
            'name' => $request->input('name'),
            'college_id' => $request->input('college')
        ]);

        return redirect()->route('departments.index')->with('edit_department_success', 'Edit in department has been saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
        $department->delete();
        return redirect()->route('departments.index')->with('edit_department_success', 'Department has been deleted.');
    }

    public function options($id){
        return Department::where('college_id', $id)->get();  
    }
}
