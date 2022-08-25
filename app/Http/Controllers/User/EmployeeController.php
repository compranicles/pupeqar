<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Employee;
use App\Models\Authentication\UserRole;
use App\Models\Maintenance\{
    Sector,
    College,
    Department
};

class EmployeeController extends Controller
{
    public function __construct(){
        $this->db_ext = DB::connection('mysql_external');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session(['url' => route('home') ]);

        $user = User::where('id', auth()->id())->first();
        $cbco = College::select('name', 'id')->get();

        // dd($existingCol);
        $currentPos = $this->db_ext->select(" EXEC GetEmployeeCurrentPositionByEmpCode N'$user->emp_code' ");
        if(empty($currentPos)){
            return false;
        }

        $role = "Faculty";

        //if admin
        if($currentPos[0]->EmployeeTypeID == '1')
        $role = "Admin";

        if ($role == "Admin") {
            $existingCol = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();
            //For with designation - existingCol2
            $existingCol2 = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        }
        else {
            $existingCol = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
            $existingCol2 = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();
        }

        return view('offices.create', compact('cbco', 'role', 'currentPos', 'existingCol', 'existingCol2'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Employee::where('user_id', auth()->id())->delete();
        foreach($request->input('cbco') as $cbco) {
            Employee::create([
                'user_id' => auth()->id(),
                'type' => $request->input('role_type'),
                'college_id' => $cbco,
            ]);
            $officeName = College::where('id', $cbco)->first();
            \LogActivity::addToLog('Had added '.$officeName['name'].' as office to report with.');
        }

        if ($request->has('yes')) {
            UserRole::where('user_id', auth()->id())->whereIn('role_id', [1,3])->delete();
            UserRole::create([
                'user_id' => auth()->id(),
                'role_id' => $request->input('role')
            ]);
            if ($request->input('role') == 3) {
                UserRole::create([
                    'user_id' => auth()->id(),
                    'role_id' => 1,
                ]);
            } else {
                UserRole::create([
                    'user_id' => auth()->id(),
                    'role_id' => 3,
                ]);
            }
        }
        if ($request->has('designee_cbco')){
            foreach($request->input('designee_cbco') as $cbco) {
                Employee::create([
                    'user_id' => auth()->id(),
                    'type' => $request->input('designee_type'),
                    'college_id' => $cbco,
                ]);
                $officeName = College::where('id', $cbco)->first();
                \LogActivity::addToLog('Had added '.$officeName['name'].' as office to report with as a designee.');
            }
        }

        if (session('url'))
            return redirect(session('url'));
        else
            return redirect()->route('account')->with('success', 'Your role and designation has been updated.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $office)
    {
        // $sectors = Sector::all();
        // $cbco = College::all();
        // return view('offices.edit', compact('sectors', 'cbco', 'office'));
        abort(404);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $office)
    {
        // Employee::where('id', $office->id)->update([
        //     'user_id' => auth()->id(),
        //     'sector_id' => $request->input('sector'),
        //     'college_id' => $request->input('cbco'),
        // ]);

        // \LogActivity::addToLog('Office reporting with was updated.');
        // return redirect()->route('account')->with('success', 'College/Branch/Campus/Office has been updated in your account.');
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($designee_type)
    {
        Employee::where('user_id', auth()->id())->where('type', $designee_type)->delete();
        if ($designee_type == 'A') {
            UserRole::where('user_id', auth()->id())->where('role_id', 3)->delete();
            \LogActivity::addToLog('Had removed designation as Admin.');
        }
        else {
            UserRole::where('user_id', auth()->id())->where('role_id', 1)->delete();
            \LogActivity::addToLog('Had removed designation as Faculty.');
        }
        return redirect()->route('account')->with('success', 'Designation has been removed in your account.');
    }
}
