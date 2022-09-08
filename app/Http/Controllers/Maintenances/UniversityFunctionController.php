<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\UniversityFunction;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Services\SingleAuthorizationService;

class UniversityFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageUniversityFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $universityFunctions = UniversityFunction::all();
        return view('maintenances.university-function.index', compact('universityFunctions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quarter = Quarter::first();
        return view('maintenances.university-function.create', compact('quarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageUniversityFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'activity_description' => 'required|string',
            'remarks' => 'required|string'
        ]);

        UniversityFunction::create([
            'activity_description' => $request->input('activity_description'),
            'remarks' => $request->input('remarks'),
        ]);

        return redirect()->route('university-function-manager.index')->with('success', 'University Function added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(UniversityFunction $university_function)
    {
        return view('maintenances.university-function.edit', compact('university_function_manager'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UniversityFunction $university_function_manager)
    {
        return view('maintenances.university-function.edit', compact('university_function_manager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UniversityFunction $university_function_manager)
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageUniversityFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'activity_description' => 'required|string'
        ]);

        $university_function_manager->update([
            'activity_description' => $request->input('activity_description'),
            'remarks' => $request->input('remarks'),
        ]);

        return redirect()->route('university-function-manager.index')->with('success', 'University Function updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UniversityFunction $university_function_manager)
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageUniversityFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        
        $university_function_manager->delete();

        return redirect()->route('university-function-manager.index')->with('success', 'University Function deleted successfully');
    }
}
