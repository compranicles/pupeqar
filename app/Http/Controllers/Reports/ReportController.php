<?php

namespace App\Http\Controllers\Reports;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\FormBuilder\Form;
use App\Models\FormBuilder\Field;
use App\Models\FormBuilder\QarForm;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(){
        $qarforms = QarForm::pluck('form_id')->all();
        $submissionExisting = Submission::groupBy('form_id')->pluck('form_id')->all();
        $forms = Form::whereIn('id', $qarforms)->whereIn('id', $submissionExisting)->get();
        $submissionsPerForm = [];
        $fieldsPerForm = [];

        foreach ($forms as $form){
            $fields = Field::where('form_id', $form->id)->where('status', 'shown')->orderBy('order')->get();
            $submissions = Submission::join('users', 'users.id', 'submissions.user_id')->where('submissions.form_id', $form->id)
                        ->select('users.last_name', 'users.first_name', 'users.middle_name', 'submissions.*')->get();
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
        return view('reports.index', compact('forms', 'fieldsPerForm', 'submissionsPerForm'));
    }
}
