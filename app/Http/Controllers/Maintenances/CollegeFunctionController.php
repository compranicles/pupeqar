<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\Dean;
use Illuminate\Http\Request;
use App\Models\CollegeFunction;
use App\Http\Controllers\Controller;

class CollegeFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colleges = Dean::where('user_id', auth()->id())->pluck('college_id')->all();

        $collegeFunctions = CollegeFunction::whereIn('college_functions.college_id', [$colleges])
                        ->join('colleges', 'colleges.id', 'college_functions.college_id')
                        ->select('college_functions.*', 'colleges.name as college_name')
                        ->get();
                        
        return view('maintenances.college-function.index', compact('collegeFunctions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $colleges = Dean::where('deans.user_id', auth()->id())->join('colleges', 'colleges.id', 'deans.college_id')
                    ->select('colleges.*')->get();

        return view('maintenances.college-function.create', compact('colleges'));
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
