<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function getTen(){
        return \LogActivity::logActivityListsTen();
    }

    public function getTenIndi(){
        return \LogActivity::logActivityListsPerIdTen();
    }

    public function getAll(){
        $logs = \LogActivity::logActivityLists();
        return view('logs.all', compact('logs'));
    }

    public function getAllIndi(){
        $logs = \LogActivity::logActivityListsPerId();
        return view('logs.individual', compact('logs'));

    }
}
