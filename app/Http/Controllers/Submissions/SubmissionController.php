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
        $forms = QarForm::join('forms', 'forms.id', 'qar_forms.form_id')->select('forms.*')->get();
        $submissionExisting = Submission::groupBy('form_id')->pluck('form_id')->all();
        $formsHasSubmission = QarForm::join('forms', 'forms.id', 'qar_forms.form_id')->join('submissions', 'submissions.form_id', 'qar_forms.form_id')
                    ->whereIn('forms.id', $submissionExisting)
                    ->where('submissions.user_id', auth()->id())->select('forms.*')->get();
        $submissionsPerForm = [];
        $fieldsPerForm = [];

        foreach ($formsHasSubmission as $form){
            $fields = Field::where('form_id', $form->id)->where('status', 'shown')->orderBy('order')->get();
            $submissions = Submission::join('forms', 'forms.id', 'submissions.form_id')->where('submissions.form_id', $form->id)
                        ->where('submissions.user_id', auth()->id())->select('forms.name as form_name', 'submissions.*')->get();
            array_push($submissionsPerForm, (object)[
                    $form->id => json_decode(json_encode($submissions), true)
            ]);
            array_push($fieldsPerForm, (object)[
                $form->id => $fields
            ]);
        }
        $submissionsPerForm = json_decode(json_encode($submissionsPerForm), true);
        $fieldsPerForm = json_decode(json_encode($fieldsPerForm), true);

        // dd($submissionsPerForm);
        return view('submissions.index', compact('forms', 'formsHasSubmission','fieldsPerForm', 'submissionsPerForm'));
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
