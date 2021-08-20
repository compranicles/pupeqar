<?php

namespace App\Http\Controllers\Maintenances;

use App\Models\SupportType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supporttypes = SupportType::all();
        return view('admin.maintenances.supporttype.index', compact('supporttypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.supporttype.create');
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

        SupportType::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.support')->with('success', 'Added successfully');
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
    public function edit(SupportType $support_type)
    {
        return view('admin.maintenances.supporttype.edit', compact('support_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupportType $support_type)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $support_type->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.support')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportType $support_type)
    {
        $support_type->delete();
        return redirect()->route('admin.maintenances.support')->with('success','Deleted successfully.');
    }
}
