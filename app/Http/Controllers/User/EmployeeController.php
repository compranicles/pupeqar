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
        $sectors = Sector::all();
        $cbco = College::all();
        return view('offices.create', compact('sectors', 'cbco'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Employee::create([
            'user_id' => auth()->id(),
            'sector_id' => $request->input('sector'),
            'college_id' => $request->input('cbco'),
        ]);

        $officeName = College::where('id', $request->input('cbco'))->first();
        \LogActivity::addToLog('Had added '.$officeName['name'].' as office to report with.');
        return redirect()->route('account')->with('success', 'College/Branch/Campus/Office has been added in your account.');
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
    public function destroy(Employee $office)
    {
        Employee::where('id', $office->id)->delete();
        $officeName = College::where('id', $office->id)->first();
        \LogActivity::addToLog('Had removed '.$officeName['name'].' in the offices reporting with.');
        return redirect()->route('account')->with('success', 'College/Branch/Campus/Office has been removed in your account.');
    }
}
