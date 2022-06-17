<?php

namespace App\Http\Controllers\IPCR;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\CollegeFunction;
use App\Models\AttendanceFunction;
use App\Models\UniversityFunction;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Quarter;
use App\Http\Controllers\Controller;
use App\Models\FormBuilder\IPCRForm;
use App\Models\FormBuilder\IPCRField;
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

        $universityFunctions = UniversityFunction::all();
        $collegeFunctions = CollegeFunction::whereIn('college_functions.college_id', $colleges)
                            ->join('colleges', 'colleges.id', 'college_functions.college_id')
                            ->select('college_functions.*', 'colleges.name as college_name')->get();

        $attendedFunctions = AttendanceFunction::where('user_id', auth()->id())->get();

        $roles = UserRole::where('user_id', auth()->id())->pluck('role_id')->all();

        return view('ipcr.attendance-function.index',
                        compact('colleges',
                                    'attendedFunctions', 'currentQuarterYear',
                                    'roles', 'universityFunctions', 'collegeFunctions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(IPCRForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $fields = IPCRField::select('i_p_c_r_fields.*', 'field_types.name as field_type_name')
            ->where('i_p_c_r_fields.i_p_c_r_form_id', 4)->where('i_p_c_r_fields.is_active', 1)
            ->join('field_types', 'field_types.id', 'i_p_c_r_fields.field_type_id')
            ->orderBy('i_p_c_r_fields.order')->get();

        $colleges = Employee::where('user_id', auth()->id())->join('colleges', 'colleges.id', 'employees.college_id')->select('colleges.*')->get();
        $values = UniversityFunction::where('id', $request->get('id'))->first()->toArray();

        if($request->get('type') == 'college'){
            $values = CollegeFunction::where('id', $request->get('id'))->first()->toArray();
            $colleges = College::where('id', $values['college_id'])->get();
        }

        return view('ipcr.attendance-function.create', compact('fields', 'colleges', 'values'));
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
