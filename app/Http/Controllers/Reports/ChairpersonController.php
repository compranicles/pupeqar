<?php

namespace App\Http\Controllers\Reports;

use App\Models\Report;
use App\Models\DenyReason;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChairpersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAnyChairpersonReport', Report::class);

        $departmentHeadOf = Chairperson::select('chairpeople.*', 'departments.name as department_name')->
            join('departments', 'chairpeople.department_id', 'departments.id')->where('user_id', auth()->id())->first();

        
        $reportsToReview = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                            ->join('departments', 'reports.department_id', 'departments.id')
                            ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                            ->join('users', 'reports.user_id', 'users.id')
                            ->where('department_id', $departmentHeadOf->department_id)->where('chairperson_approval', null)->get();

        $reportsDenied = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                            ->join('departments', 'reports.department_id', 'departments.id')
                            ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                            ->join('users', 'reports.user_id', 'users.id')
                            ->where('department_id', $departmentHeadOf->department_id)->where('chairperson_approval', 1)
                            ->where('dean_approval', 0)->get();

        return view('reports.chairpersons.index', compact('departmentHeadOf','reportsToReview', 'reportsDenied'));
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
        $this->authorize('validateByChairperson', Report::class);

        Report::where('id', $report_id)->update(['chairperson_approval' => 1]);

        return redirect()->route('chairperson.index')->with('success', 'Report Accepted');
    
    }
    public function rejectCreate($report_id){
        return view('reports.chairpersons.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
        $this->authorize('validateByChairperson', Report::class);

        DenyReason::create([
            'report_id' => $report_id,
            'user_id' => auth()->id(),
            'position_name' => 'chairperson',
            'reason' => $request->input('reason'),
        ]);

        Report::where('id', $report_id)->update([
            'chairperson_approval' => 0
        ]);
        return redirect()->route('chairperson.index')->with('success', 'Report Denied');
    }

    public function relay($report_id){
        $this->authorize('validateByChairperson', Report::class);

        Report::where('id', $report_id)->update(['chairperson_approval' => 0]);
        return redirect()->route('chairperson.index')->with('success', 'Report Denial successfully sent');
    }
}
