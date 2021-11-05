<?php

namespace App\Http\Controllers\Maintenances;

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
        return view('maintenances.dropdowns.index', compact('dropdowns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenances.dropdowns.create');
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
                'name' => $label,
                'order' => $count,
                'is_active' => 1,
            ]);
            $count++;
        }

        return redirect()->route('dropdowns.index')->with('success',  $request->input('name').' added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dropdown $dropdown)
    {
        $dropdown_options = DropdownOption::where('dropdown_id', $dropdown->id)->orderBy('order')->get();

        return view('maintenances.dropdowns.show', compact('dropdown', 'dropdown_options'));
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

        return redirect()->route('dropdowns.show', $dropdown->id)->with('success',  $request->input('name').' updated successfully');
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
        return redirect()->route('dropdowns.index')->with('success', 'Dropdown deleted successfully');
    }
    
    /**
     * Get the dropdown's options from dropdown_options model
     */
    public function options($id){
        $options = DropdownOption::where('dropdown_id', $id)->where('is_active', 1)->orderBy('order')->get();
        
        return $options;
    }

    public function addOptions(Request $request, $id){
        
        DropdownOption::create([
            'dropdown_id' => $id,
            'name' => $request->input('name'),
            'order' => 99,
            'is_active' => 1,
        ]);

        return redirect()->route('dropdowns.show', $id)->with('success-options',  $request->input('name').' added successfully');
    }

    public function arrangeOptions(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            DropdownOption::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
    
    /**
     * activate
     *
     * @param  mixed $id Dropdown Option ID
     * @return void
     */
    public function activate($id){
        DropdownOption::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        DropdownOption::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }

}
