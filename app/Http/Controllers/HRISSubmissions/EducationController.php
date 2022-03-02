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
use App\Models\Maintenance\Currency;
use App\Models\Maintenance\HRISField;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\DropdownOption;

class EducationController extends Controller
{
    public function index(){

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $educationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");

        $educationFinal = [];

        foreach($educationLevel as $level){

            $educationTemp = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndEducationLevelID N'$user->emp_code',$level->EducationLevelID");

            $educationFinal = array_merge($educationFinal, $educationTemp);
        }

        return view('submissions.hris.education.index', compact('educationFinal', 'educationLevel'));
    }

    public function add($educID){

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');
        
        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$educID");

        $educFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
                ->where('h_r_i_s_fields.h_r_i_s_form_id', 1)->where('h_r_i_s_fields.is_active', 1)
                ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
                ->orderBy('h_r_i_s_fields.order')->get();
        $values = [
            'degree' =>  $educationData[0]->Degree,
            'school_name' => $educationData[0]->SchoolName,
            'from' => $educationData[0]->IncYearFrom,
            'to' => $educationData[0]->IncYearTo,
            'units_earned' => $educationData[0]->UnitsEarned,
        ];

        $colleges = College::all();

        return view('submissions.hris.education.add', compact('educID', 'educationData', 'educFields', 'values', 'colleges'));
    }

    public function save(Request $request, $educID){


        $educFields = HRISField::select('h_r_i_s_fields.*', 'field_types.name as field_type_name')
            ->where('h_r_i_s_fields.h_r_i_s_form_id', 1)->where('h_r_i_s_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'h_r_i_s_fields.field_type_id')
            ->orderBy('h_r_i_s_fields.order')->get();
        $data = [];

        foreach($educFields as $field){
            if($field->field_type_id == '5'){
                $data[$field->name] = DropdownOption::where('id', $request->input($field->name))->pluck('name')->first();
            }
            elseif($field->field_type_id == '3'){
                $currency_name = Currency::where('id', $request->input('currency_'.$field->name))->pluck('code')->first();
                $data[$field->name] = $currency_name.' '.$request->input($field->name);
            }
            elseif($field->field_type_id == '10'){
                continue;
            }
            elseif($field->field_type_id == '12'){
                $data[$field->name] = College::where('id', $request->input($field->name))->pluck('name')->first();
            }
            elseif($field->field_type_id == '13'){
                $data[$field->name] = Department::where('id', $request->input($field->name))->pluck('name')->first();
            }
            else{
                $data[$field->name] = $request->input($field->name);
            }
        }

        $data = collect($data);

        $sector_id = College::where('id', $request->college_id)->pluck('sector_id')->first();

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'HRIS-OAPS-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    HRISDocument::create([
                        'hris_form_id' => 1,
                        'reference_id' => $educID,
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
            'report_category_id' => 24,
            'report_code' => null,
            'report_reference_id' => $educID,
            'report_details' => json_encode($data),
            'report_documents' => json_encode(collect($request->document)),
            'report_date' => date("Y-m-d", time()),
        ]);

        return redirect()->route('submissions.educ.index')->with('success','Report Submitted Successfully');
    }
}
