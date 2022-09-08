<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\Chairperson;
use Illuminate\Http\Request;
use App\Models\DepartmentFunction;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Services\SingleAuthorizationService;

class DepartmentFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageDepartmentFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $departments = Chairperson::where('user_id', auth()->id())->pluck('department_id')->all();

        $departmentFunctions = DepartmentFunction::whereIn('department_functions.department_id', $departments)
                        ->join('departments', 'departments.id', 'department_functions.department_id')
                        ->select('department_functions.*', 'departments.name as department_name')
                        ->get();

        return view('maintenances.department-function.index', compact('departmentFunctions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Chairperson::where('chairpeople.user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')
                    ->select('departments.*')->get();

        $quarter = Quarter::first();
        return view('maintenances.department-function.create', compact('departments', 'quarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageDepartmentFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'activity_description' => 'required|string',
            'department_id' => 'numeric',
            'remarks' => 'required|string'
        ]);

        DepartmentFunction::create([
            'activity_description' => $request->input('activity_description'),
            'department_id' => $request->input('department_id'),
            'remarks' => $request->input('remarks'),
        ]);

        return redirect()->route('department-function-manager.index')->with('success', 'Department/Section Function has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DepartmentFunction $department_function_manager)
    {
        $departments = Chairperson::where('chairpeople.user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')
                    ->select('departments.*')->get();
        return view('maintenances.department-function.edit', compact('department_function_manager', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DepartmentFunction $department_function_manager)
    {
        $departments = Chairperson::where('chairpeople.user_id', auth()->id())->join('departments', 'departments.id', 'chairpeople.department_id')
                    ->select('departments.*')->get();
        return view('maintenances.department-function.edit', compact('department_function_manager', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DepartmentFunction $department_function_manager)
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageDepartmentFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'activity_description' => 'required|string',
            'department_id' => 'numeric',
        ]);

        $department_function_manager->update([
            'activity_description' => $request->input('activity_description'),
            'department_id' => $request->input('department_id'),
            'remarks' => $request->input('remarks'),
        ]);

        return redirect()->route('department-function-manager.index')->with('success', 'Department/Section Function has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepartmentFunction $department_function_manager)
    {
        $authorize = (new SingleAuthorizationService())->authorizeManageDepartmentFunction();
        if (!($authorize)) {
            abort(403, 'Unauthorized action.');
        }
        $department_function_manager->delete();

        return redirect()->route('department-function-manager.index')->with('success', 'Department/Section Function has been deleted.');
    }
}
