<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\FieldType;
use App\Models\FormBuilder\ExtensionProgramField;
use App\Models\FormBuilder\ExtensionProgramForm;

class ExtensionProgramFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $extension_program_forms = ExtensionProgramForm::all();
        return view('maintenances.extension-programs.index', compact('extension_program_forms'));
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExtensionProgramForm $extension_program_form)
    {
        $extension_program_fields = ExtensionProgramField::where('extension_program_fields.extension_programs_form_id', $extension_program_form->id)->orderBy('extension_program_fields.order')
                    ->join('field_types', 'field_types.id', 'extension_program_fields.field_type_id')
                    ->select('extension_program_fields.*', 'field_types.name as field_type_name')->get();

        $field_types = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.extension-programs.show', compact('extension_program_form', 'extension_program_fields', 'field_types', 'dropdowns'));
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
    public function update(Request $request, $id)
    {
        //
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
        ExtensionProgramForm::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        ExtensionProgram::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }
}
