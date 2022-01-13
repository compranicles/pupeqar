<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
   public function index() { 
        $db_ext = DB::connection('mysql_external');

        $departments = $db_ext->select(" EXEC GetDepartment");
        dd($departments);
    }
}
