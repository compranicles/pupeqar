<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\{
    DocumentDescription,
    FormBuilder\Dropdown,
    Maintenance\HRISForm,
    FormBuilder\FieldType,
    Maintenance\HRISField,
};

class HRISFieldController extends Controller
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
    public function store(Request $request, HRISForm $hris_form)
    {
        $required = 1;
        $field_name = $request->field_name;
        if($request->required == null){
            $required = 0;
        }
        HRISField::create([
            'h_r_i_s_form_id' => $h_r_i_s_form->id,
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
                Schema::table($hris_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            break;
            case 2: // number
                Schema::table($hris_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->integer($field_name)->nullable();
                });
            break;
            case 11: // decimal
                Schema::table($hris_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->decimal($field_name, 9, 2)->nullable();
                });
            break; 
            case 4: // date
                Schema::table($hris_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->date($field_name)->nullable();
                });
            break; 
            case 5: // dropdown
                Schema::table($hris_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->foreignId($field_name)->nullable();
                });
            break; 
            case 8: // textarea
                Schema::table($hris_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->text($field_name)->nullable();
                });
            break; 
            case 14: // yes-no
                Schema::table($hris_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            default: 
        }
        return redirect()->route('hris-forms.show', $hris_form->id)->with('success', 'HRIS field added sucessfully.');
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
    public function edit(HRISForm $hris_form, HRISField $hris_field)
    {
        $fieldtypes = FieldType::all();
        $dropdowns = Dropdown::all();
        if ($hris_form->id == 1) {
            $descriptions = DocumentDescription::where('report_category_id', 24)->get();
        }
        return view('maintenances.ipcr.edit', compact('hris_form', 'hris_field', 'fieldtypes', 'dropdowns', 'descriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HRISForm $hris_form, HRISField $hris_field)
    {
        $input = $request->except(['_token', '_method']);

        HRISField::where('h_r_i_s_form_id', $hris_form->id)->where('id', $hris_field->id)->update($input);
        return redirect()->route('hris-forms.show', $ipcr_form->id)->with('success', 'HRIS field has been updated.');
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
        HRISField::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        HRISField::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }

    public function arrange(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            HRISField::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
}
