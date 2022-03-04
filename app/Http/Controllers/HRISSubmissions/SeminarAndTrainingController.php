<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SeminarAndTrainingController extends Controller
{
    public function index(){

        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $developmentFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeTrainingProgramByEmpCode N'$user->emp_code'");

        return view('submissions.hris.development.index', compact('developmentFinal'));
    }
}