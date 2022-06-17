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
        $request->validate([
            'activity_description' => 'required|string',
            'college_id' => 'numeric'
        ]);

        CollegeFunction::create([
            'activity_description' => $request->input('activity_description'),
            'college_id' => $request->input('college_id'),
        ]);

        return redirect()->route('college-function-manager.index')->with('success', 'College Function added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CollegeFunction $college_function_manager)
    {
        $colleges = Dean::where('deans.user_id', auth()->id())->join('colleges', 'colleges.id', 'deans.college_id')
                    ->select('colleges.*')->get();
        return view('maintenances.college-function.edit', compact('college_function_manager', 'colleges'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CollegeFunction $college_function_manager)
    {
        $colleges = Dean::where('deans.user_id', auth()->id())->join('colleges', 'colleges.id', 'deans.college_id')
                    ->select('colleges.*')->get();
        return view('maintenances.college-function.edit', compact('college_function_manager', 'colleges'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CollegeFunction $college_function_manager)
    {
        $request->validate([
            'activity_description' => 'required|string',
            'college_id' => 'numeric'
        ]);

        $college_function_manager->update([
            'activity_description' => $request->input('activity_description'),
            'college_id' => $request->input('college_id'),
        ]);

        return redirect()->route('college-function-manager.index')->with('success', 'College Function updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CollegeFunction $college_function_manager)
    {
        $college_function_manager->delete();

        return redirect()->route('college-function-manager.index')->with('success', 'College Function deleted successfully');
    }
}
