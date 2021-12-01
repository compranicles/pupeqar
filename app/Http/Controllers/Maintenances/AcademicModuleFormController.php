<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\FieldType;
use App\Models\FormBuilder\AcademicDevelopmentForm;
use App\Models\FormBuilder\AcademicDevelopmentField;

class AcademicModuleFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academicforms = AcademicDevelopmentForm::all();
        return view('maintenances.academic-module.index', compact('academicforms'));
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
    public function show(AcademicDevelopmentForm $academic_module_form)
    {
        $academic_fields = AcademicDevelopmentField::where('academic_development_fields.academic_development_form_id', $academic_module_form->id)->orderBy('academic_development_fields.order')
                    ->join('field_types', 'field_types.id', 'academic_development_fields.field_type_id')
                    ->select('academic_development_fields.*', 'field_types.name as field_type_name')->get();

        $field_types = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.academic-module.show', compact('academic_module_form', 'academic_fields', 'field_types', 'dropdowns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AcademicDevelopmentForm $academic_module_form)
    {
        return view('maintenances.academic-module.rename', compact('academic_module_form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcademicDevelopmentForm $academic_module_form)
    {
        $request->validate([
            'label' => 'required'
        ]);

        $academic_module_form->update([
            'label' => $request->input('label'),
        ]);

        return redirect()->route('academic-module-forms.index')->with('success', 'Form renamed successfully.');
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
        AcademicDevelopmentForm::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        AcademicDevelopmentForm::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }
}
