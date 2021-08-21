<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\TargetBeneficiary;
use App\Http\Controllers\Controller;

class TargetBeneficiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $targetbeneficiaries = TargetBeneficiary::all();
        return view('admin.maintenances.targetbeneficiary.index', compact('targetbeneficiaries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.targetbeneficiary.create');
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
            'name' => 'required'
        ]);

        TargetBeneficiary::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.targetbeneficiary')->with('success', 'Added successfully');
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
    public function edit(TargetBeneficiary $target_beneficiary)
    {
        return view('admin.maintenances.targetbeneficiary.edit', compact('target_beneficiary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TargetBeneficiary $target_beneficiary)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $target_beneficiary->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.targetbeneficiary')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TargetBeneficiary $target_beneficiary)
    {
        $target_beneficiary->delete();
        return redirect()->route('admin.maintenances.targetbeneficiary')->with('success','Deleted successfully.');
    }
}
