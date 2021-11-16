<?php

namespace App\Http\Controllers\Reports;

use App\Models\Report;
use App\Models\DenyReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IpqmsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAnyIpmsoReport', Report::class);

        $reportsToReview = Report::select('reports.*', 'colleges.name as college_name', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
            ->join('departments', 'reports.department_id', 'departments.id')
            ->join('colleges', 'reports.college_id', 'colleges.id')
            ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
            ->join('users', 'reports.user_id', 'users.id')
            ->where('chairperson_approval', 1)->where('dean_approval', 1)
            ->where('sector_approval', 1)->where('ipqmso_approval', null)->get();

        return view('reports.ipqmso.index', compact('reportsToReview'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function accept($report_id){
        $this->authorize('validateByIpqmso', Report::class);

        Report::where('id', $report_id)->update(['ipqmso_approval' => 1]);

        return redirect()->route('ipqmso.index')->with('success', 'Report Accepted');
    }

    public function rejectCreate($report_id){
        $this->authorize('validateByIpqmso', Report::class);

        return view('reports.ipqmso.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        $this->authorize('validateByIpqmso', Report::class);

        DenyReason::create([
            'report_id' => $report_id,
            'user_id' => auth()->id(),
            'position_name' => 'ipqmso',
            'reason' => $request->input('reason'),
        ]);

        Report::where('id', $report_id)->update([
            'ipqmso_approval' => 0
        ]);
        return redirect()->route('ipqmso.index')->with('success', 'Report Denied');
    }
}
