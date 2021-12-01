<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\FieldType;
use Illuminate\Support\Facades\Schema;
use App\Models\FormBuilder\InventionForm;
use Illuminate\Database\Schema\Blueprint;
use App\Models\FormBuilder\InventionField;

class InventionFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', InventionForm::class);

        $inventionforms = InventionForm::all();
        return view('maintenances.invention.index', compact('inventionforms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InventionForm $invention_form, Request $request)
    {   
        $this->authorize('create', InventionForm::class);

        $required = 1;
        $field_name = $request->field_name;
        if($request->required == null){
            $required = 0;
        }
        InventionField::create([
            'invention_form_id' => $invention_form->id,
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
                Schema::table($invention_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->string($field_name)->nullable();
                });
            break;
            case 2: // number
                Schema::table($invention_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->integer($field_name)->nullable();
                });
            break;
            case 3: // decimal
                Schema::table($invention_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->decimal($field_name, 9, 2)->nullable();
                });
            break; 
            case 4: // date
                Schema::table($invention_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->date($field_name)->nullable();
                });
            break; 
            case 5: // dropdown
                Schema::table($invention_form->table_name, function (Blueprint $table) use ($field_name) {
                    $table->foreignId($field_name)->nullable();
                });
            break; 
            case 8: // textarea
                Schema::table($invention_form->table_name, function (Blueprint $table) use ($field_name){
                    $table->foreignId($field_name)->nullable();
                });
            break; 
            default: 
        }
        return redirect()->route('invention-forms.show', $invention_form->id)->with('sucess', 'Invention field added sucessfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(InventionForm $invention_form)
    {
        $this->authorize('view', InventionForm::class);

        $invention_fields = InventionField::where('invention_fields.invention_form_id', $invention_form->id)->orderBy('invention_fields.order')
        ->join('field_types', 'field_types.id', 'invention_fields.field_type_id')
        ->select('invention_fields.*', 'field_types.name as field_type_name')->get();

        $field_types = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.invention.show', compact('invention_form', 'invention_fields', 'field_types', 'dropdowns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
        
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
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
    
    public function activate($id){
        $this->authorize('update', InventionForm::class);

        InventionForm::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        $this->authorize('update', InventionForm::class);

        InventionForm::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }
}
