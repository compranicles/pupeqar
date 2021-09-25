<?php

namespace App\Http\Controllers\FormBuilder;

use Illuminate\Http\Request;
use App\Models\FormBuilder\Form;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
    /**
     * lists all the form created
     */
    public function index()
    {
        $forms = Form::all();
        return view('formbuilder.forms.index', compact('forms'));
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
        Form::create([
            'name' => $request->input('name'),
            'form_name' => $request->input('form_name'),
            'javascript' => null,
        ]);

        return redirect()->route('admin.forms.index')->with('success', $request->input('name').' added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        return view('formbuilder.forms.show', compact('form'));
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
    public function update(Request $request, Form $form)
    {
        $form->update([
            'name' => $request->input('name'),
            'form_name' => $request->input('form_name'),
            'javascript' => $request->input('javascript') ?? null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()->route('admin.forms.index')->with('success', 'Form deleted successfully.');
    }
}
