<?php

namespace App\Http\Controllers\Submissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class DeniedController extends Controller
{
    public function index() {
        $reported_accomplishments = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category')
                    ->where('reports.user_id', auth()->id())->join('colleges', 'reports.college_id', 'colleges.id')->join('departments', 'reports.department_id', 'departments.id')
                    ->join('report_categories', 'reports.report_category_id', 'report_categories.id')->where('reports.chairperson_approval', 0)->orWhere('reports.dean_approval', 0)
                    ->orWhere('reports.sector_approval', 0)->orWhere('reports.ipqmso_approval', 0)->get();

        return view('submissions.denied.index', compact('reported_accomplishments'));
    }
}
