<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\PartnerType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartnerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partnertypes = PartnerType::all();
        return view('admin.maintenances.partnertype.index', compact('partnertypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.partnertype.create');
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

        PartnerType::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.partnertype')->with('success', 'Added successfully');
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
    public function edit(PartnerType $partner_type)
    {
        return view('admin.maintenances.partnertype.edit', compact('partner_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartnerType $partner_type)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $partner_type->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.partnertype')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnerType $partner_type)
    {
        $partner_type->delete();
        return redirect()->route('admin.maintenances.partnertype')->with('success','Deleted successfully.');
    }
}
