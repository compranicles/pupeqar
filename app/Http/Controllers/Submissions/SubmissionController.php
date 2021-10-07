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
    public function index(Request $request)
    {

        // get filter options 
        $quarter = $request->get('quarterFilter');
        $year = $request->get('yearFilter');
        $formF = $request->get('formFilter');

        if($request->get('reset') == "reset" ){
            $quarter = $this->quarterToday();
            $year = date('Y');
            $formF ='All';
        }

        $quarterFilter = $quarter ?? $this->quarterToday();
        $yearFilter = $year ?? date('Y');
        $formFilter = $formF ?? 'All';
        //put it in a filter
        $filter = [
            'quarter' => $quarterFilter,
            'year' => $yearFilter,
            'form' => $formFilter
        ];

        $forms = QarForm::join('forms', 'forms.id', 'qar_forms.form_id')->select('forms.*')->get();
        
        if($formFilter == 'All'){
            $submissionExisting = Submission::where('quarter', $quarterFilter)
            ->where('user_id', auth()->id())
            ->where('year', $yearFilter)->groupBy('form_id')->pluck('form_id')->all();
        }
        else{
            $submissionExisting = Submission::where('form_id', $formFilter)
                        ->where('quarter', $quarterFilter)
                        ->where('year', $yearFilter)
                        ->where('user_id', auth()->id())
                        ->groupBy('form_id')->pluck('form_id')->all();
        }

        $formsHasSubmission = QarForm::join('forms', 'forms.id', 'qar_forms.form_id')
                    ->whereIn('qar_forms.form_id', $submissionExisting)
                    ->select('forms.*')->get();
        $submissionsPerForm = [];
        $fieldsPerForm = [];

        foreach ($formsHasSubmission as $form){
            $fields = Field::where('form_id', $form->id)->where('status', 'shown')->orderBy('order')->get();
            $submissions = Submission::join('forms', 'forms.id', 'submissions.form_id')->where('submissions.form_id', $form->id)
                        ->where('submissions.user_id', auth()->id())->where('submissions.quarter', $quarterFilter)
                        ->where('submissions.year', $yearFilter)
                        ->select('forms.name as form_name', 'submissions.*')->get();
            array_push($submissionsPerForm, (object)[
                    $form->id => json_decode(json_encode($submissions), true)
            ]);
            array_push($fieldsPerForm, (object)[
                $form->id => $fields
            ]);
        }

        $submissionsPerForm = json_decode(json_encode($submissionsPerForm), true);
        $fieldsPerForm = json_decode(json_encode($fieldsPerForm), true);

        return view('submissions.index', compact('forms', 'formsHasSubmission','fieldsPerForm', 'submissionsPerForm', 'filter'));
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
        $quarter = $request->input('quarterSelect');
        $year = $request->input('yearSelect');
        $formSlug = Form::select('form_name')->where('id', $formId)->first();
        return redirect()->route('submissions.create', ['slug' => $formSlug->form_name, 'quarter' => $quarter, 'year' => $year]);
    }
    
    /**
     * generation of form 
     * getting the form details and it fields
     *
     * @param  mixed $slug
     * @return void
     */
    public function create($slug, Request $request){
        $quarter = $request->get('quarter');
        $year = $request->get('year');
        $formDetails = Form::where('form_name', $slug)->first();
        $formFields = Field::where('fields.form_id', $formDetails->id)->where('status', 'shown')
                        ->join('field_types', 'field_types.id', 'fields.field_type_id')
                        ->select('fields.*', 'field_types.name as field_type_name')
                        ->orderBy('order')->get();

        return view('submissions.add', compact('formDetails', 'formFields', 'quarter', 'year'));
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
        $quarter = $data['quarter'];
        $year = $data['year'];

        unset($data['_token']);
        unset($data['quarter']);
        unset($data['year']);

        Submission::create([
            'user_id' => auth()->id(),
            'form_id' => $id,
            'data' => json_encode($data),
            'quarter' => $quarter,
            'year' => $year,
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

    private function quarterToday(){
        if(date('Y-m-d', strtotime('01/01/'.date('Y'))) <= date('Y-m-d') && date('Y-m-d', strtotime('03/31/'.date('Y'))) >= date('Y-m-d'))
            return '1st';
        elseif(date('Y-m-d', strtotime('04/01/'.date('Y'))) <= date('Y-m-d') && date('Y-m-d', strtotime('06/30/'.date('Y'))) >= date('Y-m-d'))
            return '2nd';
        elseif(date('Y-m-d', strtotime('07/01/'.date('Y'))) <= date('Y-m-d') && date('Y-m-d', strtotime('09/30/'.date('Y'))) >= date('Y-m-d'))
            return '3rd';
        elseif(date('Y-m-d', strtotime('10/01/'.date('Y'))) <= date('Y-m-d') && date('Y-m-d', strtotime('12/31/'.date('Y'))) >= date('Y-m-d'))
            return '4th';
    }
}
