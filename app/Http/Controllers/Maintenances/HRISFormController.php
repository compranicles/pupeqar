<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    FormBuilder\Dropdown,
    Maintenance\HRISForm,
    FormBuilder\FieldType,
    Maintenance\HRISField,
};

class HRISFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', HRISForm::class);

        $hrisforms = HRISForm::all();
        return view('maintenances.hris.index', compact('hrisforms'));
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
    public function show(HRISForm $hris_form)
    {
        $this->authorize('manage', HRISForm::class);

        $hris_fields = HRISField::where('h_r_i_s_fields.h_r_i_s_form_id', $hris_form->id)->orderBy('h_r_i_s_fields.order')
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->select('h_r_i_s_fields.*', 'field_types.name as field_type_name')->get();

        $field_types = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.hris.show', compact('hris_form', 'hris_fields', 'field_types', 'dropdowns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(HRISForm $hris_form)
    {
        $this->authorize('manage', HRISForm::class);

        return view('maintenances.hris.rename', compact('hris_form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HRISForm $hris_form)
    {
        $this->authorize('manage', HRISForm::class);

        $request->validate([
            'label' => 'required'
        ]);

        $ipcr_form->update([
            'label' => $request->input('label'),
        ]);

        return redirect()->route('hris-forms.index')->with('success', 'Form renamed successfully.');
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
        HRISForm::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        HRISForm::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }
}
