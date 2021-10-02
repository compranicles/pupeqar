<?php

namespace App\Http\Controllers\FormBuilder;

use Illuminate\Http\Request;
use App\Models\FormBuilder\Form;
use App\Models\FormBuilder\Field;
use App\Models\FormBuilder\QarForm;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\FieldType;
use App\Models\FormBuilder\NonQarForm;

class FormController extends Controller
{
    /**
     * lists all the form created
     */
    public function index()
    {
        //get all forms
        $forms = Form::all();

        // get forms that are not assign using the ids from the other 2 tables
        $qarFormsId = QarForm::pluck('form_id')->all();
        $nonQarFormsId = NonQarForm::pluck('form_id')->all();
        $notAssignedForms = Form::whereNotIn('id', $qarFormsId)->whereNotIn('id',$nonQarFormsId)->get();
        
        return view('formbuilder.forms.index', compact('forms', 'notAssignedForms', 'qarFormsId', 'nonQarFormsId'));
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
        $fieldtypes = FieldType::all();
        $dropdowns = Dropdown::all();
        $fields = Field::where('form_id', $form->id)->orderBy('order')->get();
        return view('formbuilder.forms.show', compact('form', 'fieldtypes', 'dropdowns', 'fields'));
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
    
    /**
     * saves the arrangement of QAR and Non Qar Forms
     */
    public function arrange(Request $request){
        // delete all existing records
        QarForm::truncate();
        NonQarForm::truncate();
    
        //insert the new records sent from ajax request
        $qar = json_decode($request->qar, true);
        $nonqar = json_decode($request->nonqar, true);
        for($i = 0; $i < count($qar); $i++){
            QarForm::insert([
                'form_id' => (int) $qar[$i]['form_id']
            ]);
        }

        for($i = 0; $i < count($nonqar); $i++){
            NonQarForm::insert([
                'form_id' => (int) $nonqar[$i]['form_id']
            ]);
        }
        return true;
    }
}