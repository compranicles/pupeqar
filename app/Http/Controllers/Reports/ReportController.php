<?php

namespace App\Http\Controllers\Reports;

use App\Models\User;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\FormBuilder\Form;
use App\Models\FormBuilder\Field;
use App\Models\FormBuilder\QarForm;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(Request $request){

        // get filter options 
        $quarter = $request->get('quarterFilter');
        $year = $request->get('yearFilter');
        $formF = $request->get('formFilter');
        $faculty = $request->get('facultyFilter');

        if($request->get('reset') == "reset" ){
            $quarter = $this->quarterToday();
            $year = date('Y');
            $formF ='All';
            $faculty ='All';
        }

        $quarterFilter = $quarter ?? $this->quarterToday();
        $yearFilter = $year ?? date('Y');
        $formFilter = $formF ?? 'All';
        $facultyFilter = $faculty ?? 'All';
        //put it in a filter
        $filter = [
            'quarter' => $quarterFilter,
            'year' => $yearFilter,
            'form' => $formFilter,
            'faculty' => $facultyFilter
        ];

        $qarforms = QarForm::pluck('form_id')->all();

        if($formFilter == 'All' && $facultyFilter == 'All'){
            $submissionExisting = Submission::where('quarter', $quarterFilter)
            ->where('year', $yearFilter)->groupBy('form_id')->pluck('form_id')->all();
        }
        elseif($formFilter != 'All' && $facultyFilter == 'All'){
            $submissionExisting = Submission::where('form_id', $formFilter)
                        ->where('quarter', $quarterFilter)
                        ->where('year', $yearFilter)
                        ->groupBy('form_id')->pluck('form_id')->all();
        }
        elseif($formFilter == 'All' && $facultyFilter != 'All'){
            $submissionExisting = Submission::where('user_id', $facultyFilter)
                    ->where('quarter', $quarterFilter)
                    ->where('year', $yearFilter)
                    ->groupBy('form_id')->pluck('form_id')->all();
        }
        else{
            $submissionExisting = Submission::where('user_id', $facultyFilter)
                    ->where('user_id', $facultyFilter)
                    ->where('quarter', $quarterFilter)
                    ->where('year', $yearFilter)
                    ->groupBy('form_id')->pluck('form_id')->all();
        }

        
        $users = User::select('id', 'first_name', 'middle_name', 'last_name', 'suffix')->get();
        $forms = QarForm::join('forms', 'forms.id', 'qar_forms.form_id')->select('forms.*')->get();
        $formsHasSubmission = QarForm::join('forms', 'forms.id', 'qar_forms.form_id')
                    ->whereIn('qar_forms.form_id', $submissionExisting)
                    ->select('forms.*')->get();
        $submissionsPerForm = [];
        $fieldsPerForm = [];

        foreach ($formsHasSubmission as $form){
            $fields = Field::where('form_id', $form->id)->where('status', 'shown')->orderBy('order')->get();
            if($facultyFilter == 'All'){
                $submissions = Submission::join('users', 'users.id', 'submissions.user_id')->where('submissions.form_id', $form->id)
                        ->where('submissions.quarter', $quarterFilter)
                        ->where('submissions.year', $yearFilter)
                        ->select('users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix', 'submissions.*')->get();
            }
            else{
                $submissions = Submission::join('users', 'users.id', 'submissions.user_id')->where('submissions.form_id', $form->id)
                        ->where('submissions.quarter', $quarterFilter)
                        ->where('submissions.year', $yearFilter)
                        ->where('user_id', $facultyFilter)
                        ->select('users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix', 'submissions.*')->get();
            }
            
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
        return view('reports.index', compact('forms', 'formsHasSubmission','fieldsPerForm', 'submissionsPerForm', 'filter', 'users'));
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
