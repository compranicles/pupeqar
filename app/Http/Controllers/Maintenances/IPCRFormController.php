<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    FormBuilder\Dropdown,
    FormBuilder\FieldType,
    FormBuilder\IPCRField,
    FormBuilder\IPCRForm,
};

class IPCRFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', IPCRForm::class);

        $ipcrforms = IPCRForm::all();
        return view('maintenances.ipcr.index', compact('ipcrforms'));
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
    public function show(IPCRForm $ipcr_form)
    {
        $this->authorize('manage', IPCRForm::class);

        $ipcr_fields = IPCRField::where('i_p_c_r_fields.i_p_c_r_form_id', $ipcr_form->id)->orderBy('i_p_c_r_fields.order')
                    ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
                    ->select('i_p_c_r_fields.*', 'field_types.name as field_type_name')->get();

        $field_types = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.ipcr.show', compact('ipcr_form', 'ipcr_fields', 'field_types', 'dropdowns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(IPCRForm $ipcr_form)
    {
        $this->authorize('manage', IPCRForm::class);

        return view('maintenances.ipcr.rename', compact('ipcr_form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IPCRForm $ipcr_form)
    {
        $this->authorize('manage', IPCRForm::class);

        $request->validate([
            'label' => 'required'
        ]);

        $ipcr_form->update([
            'label' => $request->input('label'),
        ]);

        return redirect()->route('ipcr-forms.index')->with('success', 'Form renamed successfully.');
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
        $this->authorize('manage', IPCRForm::class);

        IPCRForm::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        $this->authorize('manage', IPCRForm::class);

        IPCRForm::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }
}
