<?php

namespace App\Http\Controllers\Reports;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AllController extends Controller
{
    public function index(){
        $this->authorize('viewAny', Report::class);
        
        $reportsToReview = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
        ->join('departments', 'reports.department_id', 'departments.id')
        ->join('colleges', 'reports.college_id', 'colleges.id')
        ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
        ->join('users', 'reports.user_id', 'users.id')
        ->where('chairperson_approval', 1)->where('dean_approval', 1)
        ->where('sector_approval', 1)->where('ipqmso_approval', 1)->get();

        return view('reports.all.index', compact('reportsToReview'));
    }
}
