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
    public function edit(InventionForm $invention_form)
    {
        return view('maintenances.invention.rename', compact('invention_form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventionForm $invention_form)
    {
        $request->validate([
            'label' => 'required'
        ]);

        $invention_form->update([
            'label' => $request->input('label'),
        ]);

        return redirect()->route('invention-forms.index')->with('success', 'Form renamed successfully.');
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
        InventionForm::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        InventionForm::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }
}
