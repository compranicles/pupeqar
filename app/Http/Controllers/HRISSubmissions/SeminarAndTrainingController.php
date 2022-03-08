<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Models\User;
use App\Models\Report;
use App\Models\HRISDocument;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\HRISField;
use Illuminate\Support\Facades\Storage;

class SeminarAndTrainingController extends Controller
{
    public function index(){

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $developmentFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        return view('submissions.hris.development.index', compact('developmentFinal'));
    }

    public function addSeminar($id){
        $user = User::find(auth()->id());

        $currentMonth = date('m');
        $quarter = 0;
        if ($currentMonth <= 3 && $currentMonth >= 1) {
            $quarter = 1;
        }
        if ($currentMonth <= 6 && $currentMonth >= 4) {
            $quarter = 2;
        }
        if ($currentMonth <= 9 && $currentMonth >= 7) {
            $quarter = 3;
        }
        if ($currentMonth <= 12 && $currentMonth >= 10) {
            $quarter = 4;
        }

        if(Report::where('report_reference_id', $id)->where(DB::raw('QUARTER(reports.updated_at)'), $quarter)
                    ->whereIn('report_category_id', [25, 26])
                    ->where('chairperson_approval', 1)->where('dean_approval', 1)->where('sector_approval', 1)->where('ipqmso_approval', 1)->exists()){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }
        if(Report::where('report_reference_id', $id)->where(DB::raw('QUARTER(reports.updated_at)'), $quarter)
                    ->whereIn('report_category_id', [25, 26])
                    ->where('chairperson_approval', null)->where('dean_approval', null)->where('sector_approval', null)->where('ipqmso_approval', null)->exists()){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $seminar;

        $seminarFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 4)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();
        
        foreach($developments as $development){
            if($development->EmployeeTrainingProgramID == $id){
                $seminar = $development;
                break;
            }
        }

        $values = [
            'title' => $seminar->TrainingProgram,
            'classification' => $seminar->Classification,
            'nature' => $seminar->Nature,
            'budget' => $seminar->Budget,
            'fund_source' => $seminar->SourceOfFund,
            'organizer' => $seminar->Conductor,
            'level' => $seminar->Level,
            'venue' => $seminar->Venue,
            'from' => $seminar->IncDateFrom,
            'to' => $seminar->IncDateTo,
            'total_hours' => $seminar->NumberOfHours
        ];

        $colleges = College::all();

        return view('submissions.hris.development.seminar.add', compact('id', 'seminar', 'seminarFields', 'values', 'colleges'));
    }

    public function saveSeminar(Request $request, $id){
        if($request->document[0] == null){
            return redirect()->back()->with('error', 'Document upload are required');
        }

        $values = collect($request->except(['_token', 'document']));

        $sector_id = College::where('id', $request->college_id)->pluck('sector_id')->first();

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'HRIS-ADP-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 4,
                        'reference_id' => $id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'report_category_id' => 25,
            'report_code' => null,
            'report_reference_id' => $id,
            'report_details' => json_encode($values),
            'report_documents' => json_encode(collect($request->document)),
            'report_date' => date("Y-m-d", time()),
        ]);

        return redirect()->route('submissions.development.index')->with('success','Report Submitted Successfully');
    }

    public function addTraining($id){
        $user = User::find(auth()->id());

        $currentMonth = date('m');
        $quarter = 0;
        if ($currentMonth <= 3 && $currentMonth >= 1) {
            $quarter = 1;
        }
        if ($currentMonth <= 6 && $currentMonth >= 4) {
            $quarter = 2;
        }
        if ($currentMonth <= 9 && $currentMonth >= 7) {
            $quarter = 3;
        }
        if ($currentMonth <= 12 && $currentMonth >= 10) {
            $quarter = 4;
        }

        if(Report::where('report_reference_id', $id)->where(DB::raw('QUARTER(reports.updated_at)'), $quarter)
                    ->whereIn('report_category_id', [25, 26])
                    ->where('chairperson_approval', 1)->where('dean_approval', 1)->where('sector_approval', 1)->where('ipqmso_approval', 1)->exists()){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }
        if(Report::where('report_reference_id', $id)->where(DB::raw('QUARTER(reports.updated_at)'), $quarter)
                    ->whereIn('report_category_id', [25, 26])
                    ->where('chairperson_approval', null)->where('dean_approval', null)->where('sector_approval', null)->where('ipqmso_approval', null)->exists()){
            return redirect()->back()->with('error', 'Already have submitted a report on this accomplishment');
        }

        $db_ext = DB::connection('mysql_external');

        $developments = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        $training;

        $trainingFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 5)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();
        
        foreach($developments as $development){
            if($development->EmployeeTrainingProgramID == $id){
                $training = $development;
                break;
            }
        }

        $values = [
            'title' => $training->TrainingProgram,
            'classification' => $training->Classification,
            'nature' => $training->Nature,
            'budget' => $training->Budget,
            'fund_source' => $training->SourceOfFund,
            'organizer' => $training->Conductor,
            'level' => $training->Level,
            'venue' => $training->Venue,
            'from' => $training->IncDateFrom,
            'to' => $training->IncDateTo,
            'total_hours' => $training->NumberOfHours
        ];

        $colleges = College::all();

        return view('submissions.hris.development.training.add', compact('id', 'training', 'trainingFields', 'values', 'colleges'));
    }

    public function saveTraining(Request $request, $id){
        
        if($request->document[0] == null){
            return redirect()->back()->with('error', 'Document upload are required');
        }

        $values = collect($request->except(['_token', 'document']));

        $sector_id = College::where('id', $request->college_id)->pluck('sector_id')->first();

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'HRIS-AT-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 5,
                        'reference_id' => $id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        Report::create([
            'user_id' =>  auth()->id(),
            'sector_id' => $sector_id,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'report_category_id' => 26,
            'report_code' => null,
            'report_reference_id' => $id,
            'report_details' => json_encode($values),
            'report_documents' => json_encode(collect($request->document)),
            'report_date' => date("Y-m-d", time()),
        ]);

        return redirect()->route('submissions.development.index')->with('success','Report Submitted Successfully');
    }
}