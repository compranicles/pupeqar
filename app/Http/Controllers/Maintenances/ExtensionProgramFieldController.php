<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    DocumentDescription,
    FormBuilder\Dropdown,
    FormBuilder\ExtensionProgramField,
    FormBuilder\ExtensionProgramForm,
    FormBuilder\FieldType,
};

class ExtensionProgramFieldController extends Controller
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
    public function store(ExtensionProgramForm $extension_program_form, Request $request)
    {
        $required = 1;
        $field_name = $request->field_name;
        if($request->required == null){
            $required = 0;
        }
        ExtensionProgramField::create([
            'extension_program_form_id' => $extension_program_form->id,
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
                Schema::table($extension_program_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            break;
            case 2: // number
                Schema::table($extension_program_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->integer($field_name)->nullable();
                });
            break;
            case 11: // decimal
                Schema::table($extension_program_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->decimal($field_name, 9, 2)->nullable();
                });
            break; 
            case 4: // date
                Schema::table($extension_program_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->date($field_name)->nullable();
                });
            break; 
            case 5: // dropdown
                Schema::table($extension_program_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->foreignId($field_name)->nullable();
                });
            break; 
            case 8: // textarea
                Schema::table($extension_program_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->text($field_name)->nullable();
                });
            break; 
            case 14: // yes-no
                Schema::table($extension_program_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            default: 
        }
        return redirect()->route('extension-program-forms.show', $extension_program_form->id)->with('success', 'Extension Program field added sucessfully.');
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
    public function edit(ExtensionProgramForm $extension_program_form, ExtensionProgramField $extension_program_field)
    {
        $fieldtypes = FieldType::all();
        $dropdowns = Dropdown::all();
        if ($extension_program_form->id == 1) {
            $descriptions = DocumentDescription::where('report_category_id', 9)->get();
        }
        elseif ($extension_program_form->id == 2) {
            $descriptions = DocumentDescription::where('report_category_id', 10)->get();
        }
        elseif ($extension_program_form->id == 3) {
            $descriptions = DocumentDescription::where('report_category_id', 11)->get();
        }
        elseif ($extension_program_form->id == 4) {
            $descriptions = DocumentDescription::where('report_category_id', 12)->get();
        }
        elseif ($extension_program_form->id == 5) {
            $descriptions = DocumentDescription::where('report_category_id', 13)->get();
        }
        elseif ($extension_program_form->id == 6) {
            $descriptions = DocumentDescription::where('report_category_id', 14)->get();
        }
        elseif ($extension_program_form->id == 7) {
            $descriptions = DocumentDescription::where('report_category_id', 22)->get();
        }
        return view('maintenances.extension-programs.edit', compact('extension_program_form', 'extension_program_field', 'fieldtypes', 'dropdowns', 'descriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExtensionProgramForm $extension_program_form, ExtensionProgramField $extension_program_field)
    {
        $input = $request->except(['_token', '_method']);

        ExtensionProgramField::where('extension_program_form_id', $extension_program_form->id)->where('id', $extension_program_field->id)->update($input);
        return redirect()->route('extension-program-forms.show', $extension_program_form->id)->with('success', 'Extension program field updated sucessfully.');
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
        ExtensionProgramField::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        ExtensionProgramField::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }

    public function arrange(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            ExtensionProgramField::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
}
