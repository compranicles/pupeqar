<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\{
    Dropdown,
    FieldType,
    ResearchField,
    ResearchForm,
};

class ResearchFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', ResearchForm::class);

        $researchforms = ResearchForm::all();
        return view('maintenances.research.index', compact('researchforms'));
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
        abort(404);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ResearchForm $research_form)
    {
        $this->authorize('view', ResearchForm::class);

        $research_fields = ResearchField::where('research_fields.research_form_id', $research_form->id)->orderBy('research_fields.order')
                    ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
                    ->select('research_fields.*', 'field_types.name as field_type_name')->get();

        $field_types = FieldType::all();
        $dropdowns = Dropdown::all();
        return view('maintenances.research.show', compact('research_form', 'research_fields', 'field_types', 'dropdowns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchForm $research_form)
    {
        return view('maintenances.research.rename', compact('research_form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchForm $research_form)
    {
        $request->validate([
            'label' => 'required'
        ]);

        $research_form->update([
            'label' => $request->input('label'),
        ]);

        return redirect()->route('research-forms.index')->with('success', 'Form renamed successfully.');
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
        abort(404);

    }


    public function activate($id){
        $this->authorize('update', ResearchForm::class);

        ResearchForm::where('id', $id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($id){
        $this->authorize('update', ResearchForm::class);

        ResearchForm::where('id', $id)->update([
            'is_active' => 0
        ]);
        
        return true;
    }
}
