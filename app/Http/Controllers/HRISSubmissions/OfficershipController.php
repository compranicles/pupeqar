<?php

namespace App\Http\Controllers\HRISSubmissions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OfficershipController extends Controller
{
    public function index(){
        
        $user = User::find(auth()->id());

        $db_ext = DB::connection('mysql_external');

        $officershipFinal = $db_ext->select("SET NOCOUNT ON; EXEC GetEmployeeOfficershipByEmpCode N'$user->emp_code'");

        return view('submissions.hris.officership.index', compact('officershipFinal'));
    }
}