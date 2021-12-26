<?php

namespace App\Http\Controllers\Reports;

use App\Models\Dean;
use App\Models\Report;
use App\Models\DenyReason;
use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Authentication\UserRole;

class ChairpersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departmentHeadOf = Chairperson::select('chairpeople.*', 'departments.name as department_name')->
            join('departments', 'chairpeople.department_id', 'departments.id')->where('user_id', auth()->id())->first();

        
        $reportsToReview = Report::select('reports.*', 'departments.name as department_name', 'report_categories.name as report_category', 'users.last_name', 'users.first_name','users.middle_name', 'users.suffix')
                            ->join('departments', 'reports.department_id', 'departments.id')
                            ->join('report_categories', 'reports.report_category_id', 'report_categories.id')
                            ->join('users', 'reports.user_id', 'users.id')
                            ->where('department_id', $departmentHeadOf->department_id)->where('chairperson_approval', null)->get();
        
        //role and department/ college id
        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
        $department_id = '';
        $college_id = '';
        if(in_array(5, $roles)){
            $department_id = Chairperson::where('user_id', auth()->id())->pluck('department_id')->first();
        }
        // dd($department_id);
        if(in_array(6, $roles)){
            $college_id = Dean::where('user_id', auth()->id())->pluck('college_id')->first();
        }

        return view('reports.chairpersons.index', compact('departmentHeadOf','reportsToReview', 'roles', 'department_id', 'college_id'));
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
        Report::where('id', $report_id)->update(['chairperson_approval' => 1]);

        return redirect()->route('chairperson.index')->with('success', 'Report Accepted');
    
    }
    public function rejectCreate($report_id){
        return view('reports.chairpersons.reject', compact('report_id'));
    }

    public function reject($report_id, Request $request){
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
        Report::where('id', $report_id)->update(['chairperson_approval' => 0]);
        return redirect()->route('chairperson.index')->with('success', 'Report Denial successfully sent');
    }
}
