<?php

namespace App\Http\Controllers\FormBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\DropdownOption;

class DropdownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dropdowns = Dropdown::all();
        return view('formbuilder.dropdowns.index', compact('dropdowns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('formbuilder.dropdowns.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //save dropdown name to dropdown table and get the Id
        $dropdownId = Dropdown::create([
            'name' => $request->input('name'),
        ])->id;

        //save the options to the dropdown option table
        $count = 0;
        $labels = $request->input('label');
        $values = $request->input('value');
        foreach($labels as $label){
            DropdownOption::insert([
                'dropdown_id' => $dropdownId,
                'label' => $label,
                'value' => $values[$count],
            ]);
            $count++;
        }

        return redirect()->route('admin.dropdowns.index')->with('success',  $request->input('name').' added successfully');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dropdown $dropdown)
    {
        // updating name 
        $dropdown->update([
            'name' => $request->input('name'),
        ]);
        
        //delete existing options and save the new options to the dropdown option table
        DropdownOption::where('dropdown_id', $dropdown->id)->delete();
        $count = 0;
        $labels = $request->input('label');
        $values = $request->input('value');
        foreach($labels as $label){
            DropdownOption::insert([
                'dropdown_id' => $dropdown->id,
                'label' => $label,
                'value' => $values[$count],
            ]);
            $count++;
        }

        return redirect()->route('admin.dropdowns.index')->with('success',  $request->input('name').' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dropdown $dropdown)
    {
        DropdownOption::where('dropdown_id', $dropdown->id)->delete();
        $dropdown->delete();
        return redirect()->route('admin.dropdowns.index')->with('success', 'Dropdown deleted successfully');
    }

    public function dropdowndata($id){
        return Dropdown::find($id);
    }
    
    /**
     * Get the dropdown's options from dropdown_options model
     */
    public function options($id){
        $options = DropdownOption::where('dropdown_id', $id)->get();
        
        return $options;
    }
}
