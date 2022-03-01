<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

        $educationLevel = $db_ext->select("SET NOCOUNT ON; EXEC GetEducationLevel");
        
        $educationData = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeEducationBackgroundByEmpCodeAndID N'$user->emp_code',$educID");

        return view('submissions.hris.education.add', compact('educationLevel', 'educationData'));
    }

    public function save(Request $request){
        
    }
}
