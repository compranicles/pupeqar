<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\FormBuilder\Form;
use App\Models\FormBuilder\Field;
use App\Models\FormBuilder\QarForm;
use App\Http\Controllers\Controller;

class SubmissionController extends Controller
{ 
    /**
     * get the id of QAR forms
     * then get all the forms that are in QAR Forms
     * and submissions existed
     *
     * @return void
     */
    public function index()
    {
        $qarforms = QarForm::pluck('form_id')->all();
        $forms = Form::whereIn('id', $qarforms)->get();
        $submissions = Submission::join('forms', 'forms.id', 'submissions.form_id')->
            whereIn('submissions.form_id', $qarforms)->
            where('submissions.user_id', auth()->id())->
            orderBy('submissions.created_at', 'desc')->
            select('submissions.*', 'forms.name as form_name')->get();
        return view('submissions.index', compact('forms','submissions'));
    }
    
    /**
     * getting the selected forms and routing it to the create form
     *
     * @param  mixed $request
     * @return void
     */
    public function select(Request $request)
    {
        $formId = $request->input('form');
        $formSlug = Form::select('form_name')->where('id', $formId)->first();
        return redirect()->route('submissions.create', $formSlug->form_name);
    }
    
    /**
     * generation of form 
     * getting the form details and it fields
     *
     * @param  mixed $slug
     * @return void
     */
    public function create($slug){
        $formDetails = Form::where('form_name', $slug)->first();
        $formFields = Field::where('fields.form_id', $formDetails->id)->where('status', 'shown')
                        ->join('field_types', 'field_types.id', 'fields.field_type_id')
                        ->select('fields.*', 'field_types.name as field_type_name')
                        ->orderBy('order')->get();

        return view('submissions.add', compact('formDetails', 'formFields'));
    }
    
    /**
     * store
     *
     * @param  mixed $request
     * @param  mixed $id Form ID
     * @return void
     */
    public function store(Request $request, $id){

        $data = $request->all();
        unset($data['_token']);
        Submission::create([
            'user_id' => auth()->id(),
            'form_id' => $id,
            'data' => json_encode($data),
        ]);

        return redirect()->route('submissions.index')->with('success', 'Submission added successfully');
    }
    
    public function edit($id){
        $submission = Submission::where('id', $id)->first();
        $formDetails = Form::where('id', $submission->form_id)->first();
        $formFields = Field::where('fields.form_id', $submission->form_id)->where('status', 'shown')
                        ->join('field_types', 'field_types.id', 'fields.field_type_id')
                        ->select('fields.*', 'field_types.name as field_type_name')
                        ->orderBy('order')->get();
        $values = json_decode($submission->data, true);
        return view('submissions.edit', compact('formDetails', 'formFields', 'submission', 'values'));
    }

    public function update(Request $request, $id){
        $data = $request->all();
        unset($data['_token']);
        Submission::where('id', $id)->update([
            'data' => json_encode($data),
        ]);

        return redirect()->route('submissions.index')->with('success', 'Submission updated successfully');
    }

    public function destroy($id){
        Submission::where('id', $id)->delete();
        return redirect()->route('submissions.index')->with('success', 'Submission deleted successfully');
    }
}
