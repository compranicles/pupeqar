<?php

namespace App\Http\Controllers\FormBuilder;

use Illuminate\Http\Request;
use App\Models\FormBuilder\Form;
use App\Models\FormBuilder\Field;
use App\Http\Controllers\Controller;

class FieldController extends Controller
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
    public function store(Request $request, Form $form)
    {
        $fieldId = Field::insertGetId([
            'form_id' => $form->id,
            'label' => $request->label,
            'name' => $request->field_name,
            'size' => $request->size,
            'field_type_id' => $request->field_type,
            'dropdown_id' => $request->dropdown ?? null,
            'required' => $request->required ?? null,
            'order' => 0,
            'status' => 'hidden',
        ]);
        return response()->json([
            'id' => $fieldId,
            'form_id' => $form->id
        ]);
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
    public function update(Request $request, Form $form, Field $field)
    {
        $field->update([
            'form_id' => $form->id,
            'label' => $request->label,
            'name' => $request->field_name,
            'size' => $request->size,
            'field_type_id' => $request->field_type,
            'dropdown_id' => $request->dropdown ?? null,
            'required' => $request->required ?? null,
        ]);

        return response()->json([
            'form_id' => $form->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form, Field $field)
    {
        $field->delete();
    }

    public function getInfo($id){
        return Field::find($id);
    }

    public function arrange(Request $request, $id){
        //update the status and order received from ajax request
        $hidden = json_decode($request->hidden, true);
        $shown = json_decode($request->shown, true);

        for($i = 0; $i < count($hidden); $i++){
            Field::find($hidden[$i]['id'])->update([
                'order' => 0,
                'status' => 'hidden'
            ]);
        }

        for($i = 0; $i < count($shown); $i++){
            Field::find($shown[$i]['id'])->update([
                'order' => $i+1,
                'status' => 'shown'
            ]);
        }
        return true;
    }
}
