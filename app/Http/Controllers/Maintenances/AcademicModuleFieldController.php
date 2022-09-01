<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    DocumentDescription,
    FormBuilder\AcademicDevelopmentField,
    FormBuilder\AcademicDevelopmentForm,
    FormBuilder\Dropdown,
    FormBuilder\FieldType,
};


class AcademicModuleFieldController extends Controller
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
    public function store(Request $request, AcademicDevelopmentForm $academic_module_form)
    {
        $this->authorize('manage', AcademicDevelopmentForm::class);

        $required = 1;
        $field_name = $request->field_name;
        if($request->required == null){
            $required = 0;
        }
        AcademicDevelopmentField::create([
            'academic_development_form_id' => $academic_module_form->id,
            'label' => $request->label,
            'name' => $request->field_name,
            'placeholder' => $request->placeholder,
            'size' => $request->size,
            'field_type_id' => $request->field_type,
            'dropdown_id' => $request->dropdown, 
            'required' => $required,
            'visibility' => $request->visibility,
            'order' => 99,
            'is_active' => 1,
        ]);

        switch($request->field_type){
            case 1: //text
                Schema::table($academic_module_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            break;
            case 2: // number
                Schema::table($academic_module_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->integer($field_name)->nullable();
                });
            break;
            case 11: // decimal
                Schema::table($academic_module_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->decimal($field_name, 9, 2)->nullable();
                });
            break; 
            case 4: // date
                Schema::table($academic_module_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->date($field_name)->nullable();
                });
            break; 
            case 5: // dropdown
                Schema::table($academic_module_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->foreignId($field_name)->nullable();
                });
            break; 
            case 8: // textarea
                Schema::table($academic_module_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->text($field_name)->nullable();
                });
            break; 
            case 14: // yes-no
                Schema::table($academic_module_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            default: 
        }
        return redirect()->route('academic-module-forms.show', $academic_module_form->id)->with('success', 'Academic Module field added sucessfully.');
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
    public function edit(AcademicDevelopmentForm $academic_module_form, AcademicDevelopmentField $academic_module_field)
    {
        $this->authorize('manage', AcademicDevelopmentForm::class);

        $fieldtypes = FieldType::all();
        $dropdowns = Dropdown::all();
        if ($academic_module_form->id == 1) {
            $descriptions = DocumentDescription::where('report_category_id', 15)->get();
        }
        elseif ($academic_module_form->id == 2) {
            $descriptions = DocumentDescription::where('report_category_id', 16)->get();
        }
        elseif ($academic_module_form->id == 3) {
            $descriptions = DocumentDescription::where('report_category_id', 18)->get();
        }
        elseif ($academic_module_form->id == 4) {
            $descriptions = DocumentDescription::where('report_category_id', 19)->get();
        }
        elseif ($academic_module_form->id == 5) {
            $descriptions = DocumentDescription::where('report_category_id', 20)->get();
        }
        elseif ($academic_module_form->id == 6) {
            $descriptions = DocumentDescription::where('report_category_id', 21)->get();
        } else
            $descriptions = [];
        return view('maintenances.academic-module.edit', compact('academic_module_form', 'academic_module_field', 'fieldtypes', 'dropdowns', 'descriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcademicDevelopmentForm $academic_module_form, AcademicDevelopmentField $academic_module_field)
    {
        $this->authorize('manage', AcademicDevelopmentForm::class);

        $input = $request->except(['_token', '_method']);

        AcademicDevelopmentField::where('academic_development_form_id', $academic_module_form->id)->where('id', $academic_module_field->id)->update($input);
        return redirect()->route('academic-module-forms.show', $academic_module_form->id)->with('success', 'Academic Module field updated sucessfully.');
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
        AcademicDevelopmentField::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        AcademicDevelopmentField::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }

    public function arrange(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            AcademicDevelopmentField::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
}
