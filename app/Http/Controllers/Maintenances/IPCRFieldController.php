<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\IPCRForm;
use App\Models\FormBuilder\FieldType;
use App\Models\FormBuilder\IPCRField;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\DocumentDescription;

class IPCRFieldController extends Controller
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
    public function store(Request $request, IPCRForm $ipcr_form)
    {
        $required = 1;
        $field_name = $request->field_name;
        if($request->required == null){
            $required = 0;
        }
        IPCRField::create([
            'i_p_c_r_form_id' => $ipcr_form->id,
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
                Schema::table($ipcr_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            break;
            case 2: // number
                Schema::table($ipcr_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->integer($field_name)->nullable();
                });
            break;
            case 11: // decimal
                Schema::table($ipcr_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->decimal($field_name, 9, 2)->nullable();
                });
            break; 
            case 4: // date
                Schema::table($ipcr_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->date($field_name)->nullable();
                });
            break; 
            case 5: // dropdown
                Schema::table($ipcr_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->foreignId($field_name)->nullable();
                });
            break; 
            case 8: // textarea
                Schema::table($ipcr_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->text($field_name)->nullable();
                });
            break; 
            case 14: // yes-no
                Schema::table($ipcr_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            default: 
        }
        return redirect()->route('ipcr-forms.show', $ipcr_form->id)->with('success', 'IPCR field added sucessfully.');
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
    public function edit(IPCRForm $ipcr_form, IPCRField $ipcr_field)
    {
        $fieldtypes = FieldType::all();
        $dropdowns = Dropdown::all();
        if ($ipcr_form->id == 1) {
            $descriptions = DocumentDescription::where('report_category_id', 17)->get();
        }
        return view('maintenances.ipcr.edit', compact('ipcr_form', 'ipcr_field', 'fieldtypes', 'dropdowns', 'descriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IPCRForm $ipcr_form, IPCRField $ipcr_field)
    {
        $input = $request->except(['_token', '_method']);

        IPCRField::where('i_p_c_r_form_id', $ipcr_form->id)->where('id', $ipcr_field->id)->update($input);
        return redirect()->route('ipcr-forms.show', $ipcr_form->id)->with('success', 'IPCR field has been updated.');
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
        IPCRField::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        IPCRField::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }

    public function arrange(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            IPCRField::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
}
