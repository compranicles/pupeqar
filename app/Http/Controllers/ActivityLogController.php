<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function getTen(){
        return \LogActivity::logActivityListsTen();
    }
}
