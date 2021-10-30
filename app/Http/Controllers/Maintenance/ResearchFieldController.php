<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\FieldType;
use Illuminate\Support\Facades\Schema;
use App\Models\FormBuilder\ResearchForm;
use App\Models\FormBuilder\ResearchField;

class ResearchFieldController extends Controller
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
    public function store(ResearchForm $research_form, Request $request)
    {
        ResearchField::create([
            'research_form_id' => $research_form->id,
            'label' => $request->label,
            'name' => $request->field_name,
            'placeholder' => $request->placeholder,
            'size' => $request->size,
            'field_type_id' => $request->field_type,
            'dropdown_id' => $request->dropdown, 
            'required' => $request->required,
            'visibility' => $request->visibility,
            'order' => 99,
            'is_active' => 1,
        ]);

        switch($request->field_type){
            case 1: //text
                Schema::table($research_form->table_name, function (Blueprint $table) {
                    $table->string($request->field_name)->nullable();
                });
            break;
            case 2: // number
                Schema::table($research_form->table_name, function (Blueprint $table) {
                    $table->integer($request->field_name)->nullable();
                });
            break;
            case 3: // decimal
                Schema::table($research_form->table_name, function (Blueprint $table) {
                    $table->decimal($request->field_name, 9, 2)->nullable();
                });
            break; 
            case 4: // date
                Schema::table($research_form->table_name, function (Blueprint $table) {
                    $table->date($request->field_name)->nullable();
                });
            break; 
            case 5: // dropdown
                Schema::table($research_form->table_name, function (Blueprint $table) {
                    $table->foreignId($request->field_name)->nullable();
                });
            break; 
            case 8: // dropdown
                Schema::table($research_form->table_name, function (Blueprint $table) {
                    $table->foreignId($request->field_name)->nullable();
                });
            break; 
            default: 
        }
        return redirect()->route('research-forms.show', $research_form->id)->with('sucess', 'Research field added sucessfully.');
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
    public function edit(ResearchForm $research_form, ResearchField $research_field)
    {
        $fieldtypes = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.research.edit', compact('research_form', 'research_field', 'fieldtypes', 'dropdowns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchForm $research_form, ResearchField $research_field)
    {
        $input = $request->except(['_token', '_method']);

        ResearchField::where('research_form_id', $research_form->id)->where('id', $research_field->id)->update($input);
        return redirect()->route('research-forms.show', $research_form->id)->with('sucess', 'Research field updated sucessfully.');
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
        ResearchField::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        ResearchField::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }

    public function arrange(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            ResearchField::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
}
