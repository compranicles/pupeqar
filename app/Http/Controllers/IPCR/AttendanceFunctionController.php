<?php

namespace App\Http\Controllers\IPCR;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\AttendanceFunction;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\Authentication\UserRole;

class AttendanceFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentQuarterYear = Quarter::find(1);

        $colleges = Employee::where('user_id', auth()->id())->pluck('college_id')->all();

        $attendedFunctions = AttendanceFunction::where('user_id', auth()->id())->get();

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();
    
        return view('ipcr.attendance-function.index', compact('colleges', 'attendedFunctions', 'currentQuarterYear', 'roles'));
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
}
