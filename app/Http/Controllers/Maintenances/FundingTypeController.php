<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\FundingType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fundingtypes = FundingType::all();
        return view('admin.maintenances.fundingtype.index', compact('fundingtypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.fundingtype.create');
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

        FundingType::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.fundingtype')->with('success', 'Added successfully');
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
    public function edit(FundingType $funding_type)
    {
        return view('admin.maintenances.fundingtype.edit', compact('funding_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FundingType $funding_type)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $funding_type->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.fundingtype')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FundingType $funding_type)
    {
        $funding_type->delete();
        return redirect()->route('admin.maintenances.fundingtype')->with('success','Deleted successfully.');
    }
}
