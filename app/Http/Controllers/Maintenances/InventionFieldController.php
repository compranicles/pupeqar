<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\FieldType;
use App\Models\FormBuilder\InventionForm;
use App\Models\FormBuilder\InventionField;

class InventionFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function edit(InventionForm $invention_form, InventionField $invention_field)
    {
        $fieldtypes = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.invention.edit', compact('invention_form', 'invention_field', 'fieldtypes', 'dropdowns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventionForm $invention_form, InventionField $invention_field)
    {
        $input = $request->except(['_token', '_method']);

        InventionField::where('invention_form_id', $invention_form->id)->where('id', $invention_field->id)->update($input);
        return redirect()->route('invention-forms.show', $invention_form->id)->with('sucess', 'Invention field updated sucessfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activate($id){
        InventionField::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        InventionField::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }

    public function arrange(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            InventionField::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
}
